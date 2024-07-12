<?php
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    $caliemail = $_SESSION['caliid'];

    $userprofileresult = mysqli_query($con, "SELECT * FROM caliweb_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($userprofileresult);
    mysqli_free_result($userprofileresult);

    $stripeID = $userinfo['stripeID'];

    if (($_SESSION['pagetitle']) == "Onboarding Billing") {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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

                    include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

                } else {

                    header ("location: /error/genericSystemError");

                }

            } else {

                header ("location: /error/genericSystemError");

            }

        }

    } else {

        header ("location: /error/genericSystemError");

    }

?>