<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

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
            
                    header ("location: /error/genericSystemError");
            
                } catch (Exception $e) {
            
                    header ("location: /error/genericSystemError");
            
                }
            
            } else if (($_SESSION['pagetitle']) == "Create Authorized User Payment Method") {

                // If users other than the account holder wants to add their own payment
                // method the logic for that is going to go here.

            } else if (($_SESSION['pagetitle']) == "Onboarding Complete") {

                \Stripe\Stripe::setApiKey($apikeysecret);

                try {

                    // Run the Charge to the card on file.

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
                    $riskScore = $retrievedPaymentIntent->charges->data[0]->outcome->risk_score;

                    
                    $actions = [
                        ['threshold' => 15, 'status' => 'Active', 'reason' => '', 'notes' => '', 'redirect' => '/onboarding/decision/approvedApp'],
                        ['threshold' => 25, 'status' => 'Under Review', 'reason' => 'The customers risk score flagged for review and needs to be approved by a Cali Web Design Team Member.', 'notes' => 'The customers risk score flagged for review and needs to be approved by a Cali Web Design Team Member.', 'redirect' => '/onboarding/decision/manualReview'],
                        ['threshold' => 35, 'status' => 'Under Review', 'reason' => 'This customer needs to speak to the Online Team, transfer them. FOR ONLINE TEAM USE ONLY. The account was flagged for unusual activity, verify customer.', 'notes' => 'This customer needs to speak to the Online Team, transfer them. FOR ONLINE TEAM USE ONLY. The account was flagged for unusual activity, verify customer.', 'redirect' => '/onboarding/decision/callOnlineTeam'],
                        ['threshold' => 45, 'status' => 'Under Review', 'reason' => 'DO NOT ASSIST OVER PHONE. Have customer email the internal risk team. FOR INTERNAL RISK TEAM. The customer flagged high on Stripe. Check with Stripe to see further actions.', 'notes' => 'DO NOT ASSIST OVER PHONE. Have customer email the internal risk team. FOR INTERNAL RISK TEAM. The customer flagged high on Stripe. Check with Stripe to see further actions.', 'redirect' => '/onboarding/decision/emailRiskTeam'],
                        ['threshold' => 60, 'status' => 'Under Review', 'reason' => 'Customer needs to verify identity at a branch, do not assist over the phone or email. Close after 60 days if they dont present to a branch.', 'notes' => 'Customer needs to verify identity at a branch, do not assist over the phone or email. Close after 60 days if they dont present to a branch.', 'redirect' => '/onboarding/decision/presentBranch'],
                        ['threshold' => 70, 'status' => 'Closed', 'reason' => 'The customer scored too high on the risk score and we cant serve this customer.', 'notes' => 'The customer scored too high on the risk score and we cant serve this customer.', 'redirect' => '/onboarding/decision/deniedApp'],
                    ];
            
                    $action = end($actions);
                    foreach ($actions as $a) {
                        if ($riskScore <= $a['threshold']) {
                            $action = $a;
                            break;
                        }
                    }
            
                    $userProfileUpdateQuery = "UPDATE `caliweb_users` SET `accountStatus` = '{$action['status']}', `statusReason`='{$action['reason']}', `accountNotes`='{$action['notes']}' WHERE email = '$caliemail'";
                    $userProfileUpdateResult = mysqli_query($con, $userProfileUpdateQuery);
            
                    if ($userProfileUpdateResult) {

                        echo '<script type="text/javascript">window.location = "' . $action['redirect'] . '"</script>';

                    } else {

                        header("Location: /error/genericSystemError");

                    }

                } catch (\Stripe\Exception\ApiErrorException $e) {

                    header("Location: /error/genericSystemError");
    
                } catch (Exception $e) {

                    header("Location: /error/genericSystemError");

                }

            } else {

                header ("location: /error/genericSystemError");

            }

        } else {

            header ("location: /error/genericSystemError");

        }

    } else {

        header ("location: /error/genericSystemError");

    }

?>