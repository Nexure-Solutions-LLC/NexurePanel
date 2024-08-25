<?php

    $pagetitle = "Onboarding Billing";
    $_SESSION['pagetitle'] = $pagetitle;
    $pagesubtitle = "";
    $pagetype = "";

    require($_SERVER["DOCUMENT_ROOT"] . "/modules/CaliWebDesign/Utility/Backend/Onboarding/MiddleLogic/index.php");

    if (($_SESSION['pagetitle']) == "Onboarding Billing") {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Checks type of payment processor.

            if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

                if ($variableDefinitionX->paymentProcessorName == "Stripe") {

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