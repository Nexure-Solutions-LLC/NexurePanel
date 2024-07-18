<?php

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    $caliemail = $_SESSION['caliid'];

    $userprofileresult = mysqli_query($con, "SELECT * FROM caliweb_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($userprofileresult);
    mysqli_free_result($userprofileresult);

    $stripeID = $userinfo['stripeID'];

    $result = mysqli_query($con, "SELECT * FROM caliweb_paymentconfig WHERE id = '1'");
    $paymentgateway = mysqli_fetch_array($result);

    // Free payment proccessor check result set

    mysqli_free_result($result);

    $apikeysecret = $paymentgateway['secretKey'];
    $apikeypublic = $paymentgateway['publicKey'];
    $paymentgatewaystatus = $paymentgateway['status'];
    $paymentProccessorName = $paymentgateway['processorName'];

    // Checks type of payment proccessor.

    if ($apikeysecret != "" && $paymentgatewaystatus == "Active" || $paymentgatewaystatus == "active") {

        if ($paymentProccessorName == "Stripe") {

            if (($_SESSION['pagetitle']) == "Onboarding Billing") {

                \Stripe\Stripe::setApiKey($apikeysecret);
            
                $token = json_decode(file_get_contents('php://input'), true)['token'];
        
                try {
        
                    $source = \Stripe\Customer::createSource(
                        $stripeID,
                        ['source' => $token]
                    );
        
                    echo '<script>window.location.href = "/onboarding/completeOnboarding";</script>';
        
                } catch (\Stripe\Exception\ApiErrorException $e) {
            
                    echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';
            
                } catch (Exception $e) {
            
                    echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';
            
                }
            
            } else if (($_SESSION['pagetitle']) == "Create Authorized User Payment Method") {

                // If users other than the account holder wants to add their own payment
                // method the logic for that is going to go here.

            } else if (($_SESSION['pagetitle']) == "Onboarding Complete") {

                \Stripe\Stripe::setApiKey($apikeysecret);

                try {

                    // Run the charge to the card on file.

                    $customer = \Stripe\Customer::retrieve($stripeID);
                    $defaultSource = $customer->default_source;

                    if (!$defaultSource) {

                        echo '<script type="text/javascript">window.location = "/onboarding/decision/deniedApp"</script>';

                    }

                    $paymentIntent = \Stripe\PaymentIntent::create([
                        'amount' => 125,
                        'currency' => 'usd',
                        'customer' => $stripeID,
                        'payment_method' => $defaultSource,
                        'off_session' => true,
                        'confirm' => true,
                    ]);

                    // Run the Risk Score on the last payment taken and review the score.  

                    $retrievedPaymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntent->id);

                    if (isset($retrievedPaymentIntent->charges->data[0]->outcome->risk_score)) {

                        $riskScore = $retrievedPaymentIntent->charges->data[0]->outcome->risk_score;

                    } else {

                        $riskScore = null;
                        
                        $userProfileUpdateQuery = "UPDATE `caliweb_users` SET `accountStatus` = 'Terminated', `statusReason`='The customer could not be scored on the risk scoring system.', `accountNotes`='The customer could not be scored on the risk scoring system. Make sure the system is not in test mode. If it is not then ask the customer to pay using another payment method.' WHERE email = '$caliemail'";
                        $userProfileUpdateResult = mysqli_query($con, $userProfileUpdateQuery);
                
                        if ($userProfileUpdateResult) {

                            echo '<script type="text/javascript">window.location = "/onboarding/decision/deniedApp"</script>';

                        } else {

                            echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

                        }

                        exit;

                    }
                    
                    $actions = [
                        ['min' => 0, 'max' => 15, 'status' => 'Active', 'reason' => '', 'notes' => '', 'redirect' => '/onboarding/decision/approvedApp'],
                        ['min' => 16, 'max' => 25, 'status' => 'Under Review', 'reason' => 'The customers risk score flagged for review and needs to be approved by a Cali Web Design Team Member.', 'notes' => 'The customers risk score flagged for review and needs to be approved by a Cali Web Design Team Member.', 'redirect' => '/onboarding/decision/manualReview'],
                        ['min' => 26, 'max' => 35, 'status' => 'Under Review', 'reason' => 'This customer needs to speak to the Online Team, transfer them. FOR ONLINE TEAM USE ONLY. The account was flagged for unusual activity, verify customer.', 'notes' => 'This customer needs to speak to the Online Team, transfer them. FOR ONLINE TEAM USE ONLY. The account was flagged for unusual activity, verify customer.', 'redirect' => '/onboarding/decision/callOnlineTeam'],
                        ['min' => 36, 'max' => 45, 'status' => 'Under Review', 'reason' => 'DO NOT ASSIST OVER PHONE. Have customer email the internal risk team. FOR INTERNAL RISK TEAM. The customer flagged high on Stripe. Check with Stripe to see further actions.', 'notes' => 'DO NOT ASSIST OVER PHONE. Have customer email the internal risk team. FOR INTERNAL RISK TEAM. The customer flagged high on Stripe. Check with Stripe to see further actions.', 'redirect' => '/onboarding/decision/emailRiskTeam'],
                        ['min' => 46, 'max' => 60, 'status' => 'Under Review', 'reason' => 'Customer needs to verify identity at a branch, do not assist over the phone or email. Close after 60 days if they dont present to a branch.', 'notes' => 'Customer needs to verify identity at a branch, do not assist over the phone or email. Close after 60 days if they dont present to a branch.', 'redirect' => '/onboarding/decision/presentBranch'],
                        ['min' => 61, 'max' => 70, 'status' => 'Closed', 'reason' => 'The customer scored too high on the risk score and we cant serve this customer.', 'notes' => 'The customer scored too high on the risk score and we cant serve this customer.', 'redirect' => '/onboarding/decision/deniedApp'],
                    ];
                    
                    $action = null;

                    foreach ($actions as $a) {

                        if ($riskScore >= $a['min'] && $riskScore <= $a['max']) {

                            $action = $a;
                            break;

                        }

                    }
            
                    if ($action) {

                        $userProfileUpdateQuery = "UPDATE `caliweb_users` SET `accountStatus` = '{$action['status']}', `statusReason`='{$action['reason']}', `accountNotes`='{$action['notes']}' WHERE email = '$caliemail'";
                        $userProfileUpdateResult = mysqli_query($con, $userProfileUpdateQuery);
                
                        if ($userProfileUpdateResult) {

                            echo '<script type="text/javascript">window.location = "' . $action['redirect'] . '"</script>';

                        } else {

                            echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

                        }

                    } else {

                        $userProfileUpdateQuery = "UPDATE `caliweb_users` SET `accountStatus` = 'Terminated', `statusReason`='The customer could not be scored on the risk scoring system.', `accountNotes`='The customer could not be scored on the risk scoring system. Make sure the system is not in test mode. If it is not then ask the customer to pay using another payment method.' WHERE email = '$caliemail'";
                        $userProfileUpdateResult = mysqli_query($con, $userProfileUpdateQuery);
                
                        if ($userProfileUpdateResult) {

                            echo '<script type="text/javascript">window.location = "/onboarding/decision/deniedApp"</script>';

                        } else {

                            echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

                        }

                    }

                } catch (\Stripe\Exception\ApiErrorException $e) {

                    echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';
    
                } catch (Exception $e) {

                    echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

                }

            } else if (($_SESSION['pagetitle']) == "Order Services as Staff") {

                $customerprofilequery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '$accountnumber'");
                $customerprofileresult = mysqli_fetch_array($customerprofilequery);
                mysqli_free_result($customerprofilequery);

                $customerstripeID = $customerprofileresult['stripeID'];

                \Stripe\Stripe::setApiKey($apikeysecret);

                function formatAmountForStripe($amount) {

                    return intval($amount * 100);

                }

                try {

                    // Run the charge to the card on file for the ordered service.

                    $customer = \Stripe\Customer::retrieve($customerstripeID);
                    $defaultSource = $customer->default_source;

                    $dollarAmount = $amountPrice;
                    $stripeAmount = formatAmountForStripe($dollarAmount);

                    $paymentIntent = \Stripe\PaymentIntent::create([
                        'amount' => $stripeAmount,
                        'currency' => 'usd',
                        'customer' => $customerstripeID,
                        'payment_method' => $defaultSource,
                        'off_session' => true,
                        'confirm' => true,
                    ]);

                    // Insert order into the database

                    $orderInsertRequest = "INSERT INTO `caliweb_services`(`serviceName`, `serviceType`, `serviceStartDate`, `serviceEndDate`, `serviceStatus`, `accountNumber`, `serviceCost`, `linkedServiceName`) VALUES ('$purchasableItem','$purchasableType','$orderdate','$endDate','$serviceStatus','$accountnumber','$amountPrice','')";
                    $orderInsertResult = mysqli_query($con, $orderInsertRequest);

                    if ($orderInsertResult) {

                        $purchasableTypeLower = strtolower($purchasableType);

                        switch ($purchasableTypeLower) {
                            case "web development":
                                echo '<script type="text/javascript">window.location = "/modules/webDesignModule/deploy"</script>';
                                break;
                            case "web hosting":
                                echo '<script type="text/javascript">window.location = "/modules/webHostModule/deploy"</script>';
                                break;
                            case "cloud computing":
                                echo '<script type="text/javascript">window.location = "/modules/cloudComputeModule/deploy"</script>';
                                break;
                            case "seo":
                                echo '<script type="text/javascript">window.location = "/modules/seoModule/deploy"</script>';
                                break;
                            case "social media management":
                                echo '<script type="text/javascript">window.location = "/modules/seoModule/deploy"</script>';
                                break;
                            case "graphic design":
                                echo '<script type="text/javascript">window.location = "/modules/seoModule/deploy"</script>';
                                break;
                            case "merchant proccessing":
                                echo '<script type="text/javascript">window.location = "/modules/paymentModule/'.strtolower($paymentProccessorName).'/paymentProccessing/merchantSignup"</script>';
                                break;
                            
                            // Add more depending on the business you have.
                        }

                    } else {

                        echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

                    }

                } catch (\Stripe\Exception\ApiErrorException $e) {

                    echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';
    
                } catch (Exception $e) {

                    echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

                }            

            } else {

                echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

            }

        } else {

            echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

        }

    } else {

        echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

    }

?>