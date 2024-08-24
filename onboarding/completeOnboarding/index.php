<?php

    // This page should not render any HTML content other than a preloader, this
    // script will be used to determine account approval. If the account needs
    // more verification throw an error to do so.

    // Inital Checks

    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php');
    require($_SERVER["DOCUMENT_ROOT"] . "/components/CaliUtilities/VariableDefinitions.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/components/CaliAccounts/Account.php");

    unset($_SESSION['pagetitle']);
    $pagetitle = "Onboarding Complete";
    $_SESSION['pagetitle'] = $pagetitle;

    $caliemail = $_SESSION['caliid'];

    $currentAccount = new \CaliAccounts\Account($con);
    $success = $currentAccount->fetchByEmail($caliemail);

    $variableDefinitionX = new \CaliUtilities\VariableDefinitions();
    $variableDefinitionX->variablesHeader($con);

    if ($currentAccount->accountStatus->name == "Active") {

        header ("Location: /dashboard/customers/");

    } else if ($currentAccount->accountStatus->name == "Suspended") {

        header ("Location: /error/suspendedAccount");

    } else if ($currentAccount->accountStatus->name == "Terminated") {

        header ("Location: /error/terminatedAccount");
        
    }

    // Checks type of payment processor.

    if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

        if ($variableDefinitionX->paymentProcessorName == "Stripe") {

            require($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

        } else {

            echo '<script>window.location.href = "/error/genericSystemError";</script>';
    
        }

    } else {

        echo '<script>window.location.href = "/error/genericSystemError";</script>';

    }

?>