<?php

    // This page should not render any HTML content other than a preloader, this
    // script will be used to determine account approval. If the account needs
    // more verification throw an error to do so.

    // Inital Checks

    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php');

    $pagetitle = "Onboarding Complete";
    $_SESSION['pagetitle'] = $pagetitle;

    $caliemail = $_SESSION['caliid'];

    $userprofileresult = mysqli_query($con, "SELECT * FROM caliweb_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($userprofileresult);
    mysqli_free_result($userprofileresult);

    $accountStatus = $userinfo['accountStatus'];

    if ($accountStatus == "Active") {

        header ("Location: /dashboard/customers/");

    } else if ($accountStatus == "Suspended") {

        header ("Location: /error/suspendedAccount");

    } else if ($accountStatus == "Terminated") {

        header ("Location: /error/terminatedAccount");
        
    }

    // Check if Payment Processing Module is loaded in and if its Stripe

    $result = mysqli_query($con, "SELECT * FROM caliweb_paymentconfig WHERE id = '1'");
    $paymentgateway = mysqli_fetch_array($result);

    // Free payment processor check result set

    mysqli_free_result($result);

    $variableDefinitionX->apiKeysecret = $paymentgateway['secretKey'];
    $variableDefinitionX->apiKeypublic = $paymentgateway['publicKey'];
    $paymentgatewaystatus = $paymentgateway['status'];
    $variableDefinitionX->paymentProcessorName = $paymentgateway['processorName'];

    // Checks type of payment processor.

    if ($variableDefinitionX->apiKeysecret != "" && $paymentgatewaystatus == "active") {

        if ($variableDefinitionX->paymentProcessorName == "Stripe") {

            include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

        } else {

            echo '<script>window.location.href = "/error/genericSystemError";</script>';
    
        }

    } else {

        echo '<script>window.location.href = "/error/genericSystemError";</script>';

    }

?>