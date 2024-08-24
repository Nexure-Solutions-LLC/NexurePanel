<?php
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/components/CaliUtilities/VariableDefinitions.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/components/CaliAccounts/Account.php");

    $caliemail = $_SESSION['caliid'];

    $currentAccount = new \CaliAccounts\Account($con);
    $success = $currentAccount->fetchByEmail($caliemail);

    $variableDefinitionX = new \CaliUtilities\VariableDefinitions();
    $variableDefinitionX->variablesHeader($con);

    $stripeID = $currentAccount->stripe_id;

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