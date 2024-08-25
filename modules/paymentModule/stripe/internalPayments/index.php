<?php

    require $_SERVER["DOCUMENT_ROOT"].'/configuration/index.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    $caliemail = $_SESSION['caliid'];
    $stripeID = $currentAccount->stripe_id;

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

                if ($module) {

                    $orderInsertRequest = "INSERT INTO `caliweb_services`(`serviceName`, `serviceType`, `serviceStartDate`, `serviceEndDate`, `serviceStatus`, `accountNumber`, `serviceCost`, `linkedServiceName`) VALUES ('$purchasableItem','$purchasableType','$orderdate','$endDate','$serviceStatus','$accountnumber','$amountPrice','$module')";
                    
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

        } else {

            redirect("/error/genericSystemError");

        }

    } else {

        redirect("/error/genericSystemError");
        
    }

?>
