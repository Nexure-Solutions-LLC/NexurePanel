<?php

    // This page should not render any HTML content other than a preloader, this
    // script will be used to determine account approval. If the account needs
    // more verification throw an error to do so.

    // Inital Checks

    unset($_SESSION['pagetitle']);
    $pagetitle = "Onboarding Complete";
    $_SESSION['pagetitle'] = $pagetitle;
    $pagesubtitle = "";
    $pagetype = "";

    require($_SERVER["DOCUMENT_ROOT"] . "/modules/CaliWebDesign/Utility/Backend/Onboarding/Complete/index.php");

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