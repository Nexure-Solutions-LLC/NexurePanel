<?php

    $pagetitle = "Onboarding Billing";
    $pagesubtitle = "";
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/Onboarding/MiddleLogic/index.php");

    if ($pagetitle == "Onboarding Billing") {

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