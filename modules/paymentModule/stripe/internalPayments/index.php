<?php

    require $_SERVER["DOCUMENT_ROOT"].'/configuration/index.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    if ($variableDefinitionX->apiKeysecret && $variableDefinitionX->paymentgatewaystatus === "active") {

        \Stripe\Stripe::setApiKey($variableDefinitionX->apiKeysecret);

        function handleOnboardingBilling($currentAccount) {

            $stripeID = $currentAccount->stripe_id;

            $token = json_decode(file_get_contents('php://input'), true)['token'] ?? '';
            
            try {

                \Stripe\Customer::createSource($stripeID, ['source' => $token]);

                header("/onboarding/completeOnboarding");

            } catch (\Throwable $exception) {

                handleError($exception);

            }

        }
        
        function handleOnboardingComplete($currentAccount, $caliemail, $con) {

            $stripeID = $currentAccount->stripe_id;
            
            try {

                $customer = \Stripe\Customer::retrieve($stripeID);

                $defaultSource = $customer->default_source;
                
                if (!$defaultSource) {

                    header("/onboarding/decision/deniedApp");

                }
                
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => 125,
                    'currency' => 'usd',
                    'customer' => $stripeID,
                    'payment_method' => $defaultSource,
                    'off_session' => true,
                    'confirm' => true,
                ]);
                
                processRiskScore($paymentIntent, $currentAccount, $caliemail, $con);

            } catch (\Throwable $exception) {

                handleError($exception);

            }

        }
        
        function processRiskScore($paymentIntent, $currentAccount, $caliemail, $con) {
            
            $riskScore = $paymentIntent->charges->data[0]->outcome->risk_score ?? null;
            
            $actions = [
                ['min' => 0, 'max' => 15, 'status' => 'Active', 'redirect' => '/onboarding/decision/approvedApp'],
                ['min' => 16, 'max' => 25, 'status' => 'Under Review', 'redirect' => '/onboarding/decision/manualReview'],
                ['min' => 26, 'max' => 35, 'status' => 'Under Review', 'redirect' => '/onboarding/decision/callOnlineTeam'],
                ['min' => 36, 'max' => 45, 'status' => 'Under Review', 'redirect' => '/onboarding/decision/emailRiskTeam'],
                ['min' => 46, 'max' => 60, 'status' => 'Under Review', 'redirect' => '/onboarding/decision/presentBranch'],
                ['min' => 61, 'max' => 70, 'status' => 'Closed', 'redirect' => '/onboarding/decision/deniedApp'],
            ];
            
            $action = array_filter($actions, fn($a) => $riskScore >= $a['min'] && $riskScore <= $a['max']);

            $action = reset($action);
            
            if ($action) {

                $currentAccount->multiChangeAttr([
                    ["attName" => "accountStatus", "attValue" => $action["status"], "useStringSyntax" => true],
                ]);

                function redirect($url) {
                    header("Location: " . $url);
                    exit();
                }

                redirect($action['redirect']);

            } else {

                mysqli_query($con, "UPDATE `nexure_users` SET `accountStatus` = 'Terminated' WHERE email = '$caliemail'");

                header("/onboarding/decision/deniedApp");

            }

        }
        
        function handleError($exception) {

            \Sentry\captureException($exception);

            header("/error/genericSystemError");

        }
        
        function handleAddCard($stripeID, $token, $redirectURL) {

            try {

                \Stripe\Customer::createSource($stripeID, ['source' => $token]);

                function redirect($url) {

                    echo "<script type='text/javascript'>window.location = '$url'</script>";
                    exit;
        
                }

                redirect($redirectURL);

            } catch (\Throwable $exception) {

                handleError($exception);

            }
            
        }
        
        switch ($pagetitle) {
            case "Onboarding Billing":
                handleOnboardingBilling($currentAccount);
                break;
            case "Onboarding Complete":
                handleOnboardingComplete($currentAccount, $_SESSION['caliid'], $con);
                break;
            case "Administration Add Card to File":
                handleAddCard($_SESSION['stripe_id'], $_SESSION['stripe_token'], "/dashboard/administration/accounts/manageAccount/paymentMethods/?account_number={$_SESSION['ACCOUNTNUMBERCUST']}");
                break;
            case "Customer Add Card to File":
                handleAddCard($_SESSION['stripe_id'], $_SESSION['stripe_token'], "/dashboard/customers/billingCenter/");
                break;
            default:
                break;
        }

        function formatAmountForStripe($amount) {

            return intval($amount * 100);

        }

        function getModulePath($serviceName) {

            global $con;

            $serviceName = mysqli_real_escape_string($con, $serviceName);

            $query = "SELECT modulePath FROM nexure_modules WHERE matchingService = '$serviceName'";

            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {

                $row = mysqli_fetch_assoc($result);
                return $row['modulePath'];

            }

            return '';

        }

        function delete_paymentmethod($payment_id) {

            $paymentMethod = \Stripe\PaymentMethod::retrieve($payment_id);
            
            $paymentMethod->detach();

        }

        function add_customer($legalname, $emailaddress, $phonenumber, $builtaccountnumber) {

            $cu = \Stripe\Customer::create([
                'name' => $legalname,
                'email' => $emailaddress,
                'phone' => $phonenumber,
                'description' => "Account Number: " . $builtaccountnumber,
            ]);

            $SS_STRIPE_ID = $cu['id'];

            return $SS_STRIPE_ID;

        }

        function delete_customer($stripeid) {

            $customer = \Stripe\Customer::retrieve($stripeid);

            $customer->delete();

            return true;

        }

        function getCreditBalance($customerId) {

            try {

                $customer = \Stripe\Customer::retrieve($customerId);

                $creditBalance = isset($customer->balance) ? $customer->balance : 0;

                $formattedBalance = number_format($creditBalance / 100, 2);

                if ($creditBalance < 0) {

                    return "<span style='color: #ff6161;'>" . $formattedBalance . "</span>";

                } else {

                    return  $formattedBalance;

                }

            } catch (\Stripe\Exception\ApiErrorException $e) {

                header("/error/genericSystemError");

            } catch (Exception $e) {

                header("/error/genericSystemError");

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);

            }

        }

        function getSubscriptionDueDate($customerId) {

            try {

                $subscriptions = \Stripe\Subscription::all([
                    'customer' => $customerId,
                    'status' => 'active',
                    'limit' => 1
                ]);

                if (empty($subscriptions->data)) {

                    return '——';

                }
                
                $subscription = $subscriptions->data[0];

                $dueDate = date('F d, Y', $subscription->current_period_end);

                return $dueDate;

            } catch (\Exception $e) {

                return '——';

            }

        }

        function updateCreditBalance($customerId, $amount) {

            try {
                
                $amountInCents = $amount * 100;

                $customer = \Stripe\Customer::retrieve($customerId);

                $customer->balance = $amountInCents;

                $customer->save();

                return "Success";

            } catch (\Stripe\Exception\ApiErrorException $e) {

                header("/error/genericSystemError");

            } catch (Exception $e) {

                header("/error/genericSystemError");

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);

            }

        }

        function chargeCustomer($customerId, $amount) {

            try {
                
                $amountInCents = $amount * 100;

                $customer = \Stripe\Customer::retrieve($customerId);

                $defaultSource = $customer->default_source;

                \Stripe\PaymentIntent::create([
                    'amount' => $amountInCents,
                    'currency' => 'usd',
                    'customer' => $customerId,
                    'payment_method' => $defaultSource,
                    'off_session' => true,
                    'confirm' => true,
                ]);

                $currentBalance = isset($customer->balance) ? $customer->balance : 0;

                $newBalance = $currentBalance - $amountInCents;

                $customer->balance = $newBalance;

                $customer->save();

                return "Success";

            } catch (\Stripe\Exception\ApiErrorException $e) {

                header("/error/genericSystemError");

            } catch (Exception $e) {

                header("/error/genericSystemError");

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);

            }
            
        }

        function getTotalPayments($customerId) {

            $totalAmount = 0;

            try {
                
                $charges = \Stripe\Charge::all(['customer' => $customerId]);

                foreach ($charges as $charge) {

                    $totalAmount += $charge->amount;

                }

            } catch (\Stripe\Exception\ApiErrorException $e) {

                header("/error/genericSystemError");

            } catch (Exception $e) {

                header("/error/genericSystemError");

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);

            }

            return $totalAmount / 100;
            
        }

        function getTaxStatus($customerId) {

            try {

                $customer = \Stripe\Customer::retrieve($customerId);

                $taxExempt = isset($customer->tax_exempt) ? ucfirst($customer->tax_exempt) : "None";

                $taxStatus = ($taxExempt == "None") ? "Taxable" : $taxExempt;

                return $taxStatus;

            } catch (\Stripe\Exception\ApiErrorException $e) {

                header("/error/genericSystemError");

            } catch (Exception $e) {

                header("/error/genericSystemError");

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);

            }

        }

    } else {

        header("/error/genericSystemError");
        
    }

?>