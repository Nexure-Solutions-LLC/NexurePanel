<?php

    require $_SERVER["DOCUMENT_ROOT"].'/configuration/index.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    function handleError($message) {

        echo $message;
        exit;
        
    }

    function redirect($url) {

        echo "<script type='text/javascript'>window.location = '$url'</script>";
        exit;

    }

    function formatAmountForStripe($amount) {

        return intval($amount * 100); // Converts dollars to cents

    }

    function getModulePath($serviceName) {

        global $con; // Ensure the database connection is accessible in this function

        $serviceName = mysqli_real_escape_string($con, $serviceName);

        $query = "SELECT modulePath FROM caliweb_modules WHERE matchingService = '$serviceName'";

        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);
            return $row['modulePath'];

        }

        return ''; // Return an empty string if no matching module is found

    }

    if ($variableDefinitionX->apiKeysecret && $variableDefinitionX->paymentgatewaystatus === "active") {

        \Stripe\Stripe::setApiKey($variableDefinitionX->apiKeysecret);

        if ($pagetitle == "Onboarding Billing") {

            $caliemail = $_SESSION['caliid'];
            $stripeID = $currentAccount->stripe_id;

            $token = json_decode(file_get_contents('php://input'), true)['token'] ?? '';

            try {

                \Stripe\Customer::createSource($stripeID, ['source' => $token]);
                
                redirect("/onboarding/completeOnboarding");

            } catch (\Stripe\Exception\ApiErrorException $e) {

                redirect("/error/genericSystemError");

            } catch (Exception $e) {

                redirect("/error/genericSystemError");

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);

            }

        } elseif ($pagetitle == "Onboarding Complete") {

            $caliemail = $_SESSION['caliid'];
            $stripeID = $currentAccount->stripe_id;

            try {

                $customer = \Stripe\Customer::retrieve($stripeID);
                $defaultSource = $customer->default_source;

                if (!$defaultSource) {

                    redirect("/onboarding/decision/deniedApp");

                }

                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => 125,
                    'currency' => 'usd',
                    'customer' => $stripeID,
                    'payment_method' => $defaultSource,
                    'off_session' => true,
                    'confirm' => true,
                ]);

                $retrievedPaymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntent->id);
                $riskScore = $retrievedPaymentIntent->charges->data[0]->outcome->risk_score ?? null;

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

                    $userProfileUpdated = $currentAccount->multiChangeAttr([
                        ["attName" => "accountStatus", "attValue" => $action["status"], "useStringSyntax" => true],
                        ["attName" => "statusReason", "attValue" => $action["reason"] ?? '', "useStringSyntax" => true],
                        ["attName" => "accountNotes", "attValue" => $action["notes"] ?? '', "useStringSyntax" => true],
                    ]);

                    if ($userProfileUpdated) {

                        redirect($action['redirect']);

                    } else {

                        redirect("/error/genericSystemError");

                    }

                } else {

                    $updateQuery = "UPDATE `caliweb_users` SET `accountStatus` = 'Terminated', `statusReason`='The customer could not be scored on the risk scoring system.', `accountNotes`='Make sure the system is not in test mode.' WHERE email = '$caliemail'";
                    $updateResult = mysqli_query($con, $updateQuery);

                    if ($updateResult) {

                        redirect("/onboarding/decision/deniedApp");
                    } else {

                        redirect("/error/genericSystemError");
                    }

                }

            } catch (\Stripe\Exception\ApiErrorException $e) {

                redirect("/error/genericSystemError");

            } catch (Exception $e) {

                redirect("/error/genericSystemError");

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);

            }

        } elseif ($pagetitle == "Services" && $pagesubtitle == "Create Order") {

            $customerprofilequery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '$accountnumber'");
            $customerprofileresult = mysqli_fetch_array($customerprofilequery);
            mysqli_free_result($customerprofilequery);

            $customerstripeID = $customerprofileresult['stripeID'] ?? '';
            $amountPrice = $amountPrice;
            $stripeAmount = formatAmountForStripe($amountPrice);

            try {

                $customer = \Stripe\Customer::retrieve($customerstripeID);
                $defaultSource = $customer->default_source;

                \Stripe\PaymentIntent::create([
                    'amount' => $stripeAmount,
                    'currency' => 'usd',
                    'customer' => $customerstripeID,
                    'payment_method' => $defaultSource,
                    'off_session' => true,
                    'confirm' => true,
                ]);

                $module = getModulePath($purchasableItem);

                function generateUniqueCode() {

                    $year = date('Y');
                    
                    $randomDigits = mt_rand(10000, 99999);
                    
                    $uniqueCode = "CWD-$year-$randomDigits";
                    
                    return $uniqueCode;

                }

                $serviceID = generateUniqueCode();

                if ($module) {

                    $orderInsertRequest = "INSERT INTO `caliweb_services`(`serviceIdentifier`, `serviceName`, `serviceType`, `serviceStartDate`, `serviceEndDate`, `serviceStatus`, `accountNumber`, `serviceCost`, `linkedServiceName`, `serviceCatagory`) VALUES ('$serviceID', '$purchasableItem','$purchasableType','$orderdate','$endDate','$serviceStatus','$accountnumber','$amountPrice','$module','$purchasableCatagory')";
                    
                    if (mysqli_query($con, $orderInsertRequest)) {

                        $pagetitle = "Internal Payments";
                        $pagesubtitle = "Order Success";
                        $pagetype = "Administration";

                        redirect("$module/deploy");

                    } else {

                        redirect("/error/genericSystemError");

                    }
                }

            } catch (\Stripe\Exception\ApiErrorException $e) {

                redirect("/error/genericSystemError");

            } catch (Exception $e) {

                redirect("/error/genericSystemError");

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);

            }

        } else if ($pagetitle == "Administration Add Card to File") {

            $stripeID = $_SESSION['stripe_id'];
            
            $accountnumber = $_SESSION['ACCOUNTNUMBERCUST'];

            var_dump($_SESSION['ACCOUNTNUMBERCUST']);
            var_dump($_SESSION['stripe_id']);

            $token = json_decode(file_get_contents('php://input'), true)['token'] ?? '';

            try {

                \Stripe\Customer::createSource($stripeID, ['source' => $token]);
                
                redirect("/dashboard/administration/accounts/manageAccount/paymentMethods/?account_number=$accountnumber");

            } catch (\Stripe\Exception\ApiErrorException $e) {

                redirect("/error/genericSystemError");

            } catch (Exception $e) {

                redirect("/error/genericSystemError");

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);

            }
            
        } else {

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

                    redirect("/error/genericSystemError");

                } catch (Exception $e) {

                    redirect("/error/genericSystemError");

                } catch (\Throwable $exception) {

                    \Sentry\captureException($exception);

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

                    redirect("/error/genericSystemError");

                } catch (Exception $e) {

                    redirect("/error/genericSystemError");

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

                    redirect("/error/genericSystemError");

                } catch (Exception $e) {

                    redirect("/error/genericSystemError");

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

                    redirect("/error/genericSystemError");

                } catch (Exception $e) {

                    redirect("/error/genericSystemError");

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

                    redirect("/error/genericSystemError");

                } catch (Exception $e) {

                    redirect("/error/genericSystemError");

                } catch (\Throwable $exception) {

                    \Sentry\captureException($exception);

                }

            }

        }

    } else {

        redirect("/error/genericSystemError");
        
    }

?>
