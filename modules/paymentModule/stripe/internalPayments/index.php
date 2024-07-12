<?php
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
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