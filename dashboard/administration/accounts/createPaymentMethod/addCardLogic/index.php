<?php
    $pagetitle = "Administration Add Card to File";
    $pagesubtitle = "";
    $pagetype = "";

    require($_SERVER["DOCUMENT_ROOT"] . "/modules/CaliWebDesign/Utility/Backend/System/Dashboard.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = json_decode(file_get_contents('php://input'), true);
        
        $_SESSION['stripe_token'] = $data['token'];

        // Checks type of payment processor.

        $pagetitle = "Administration Add Card to File";
        $pagesubtitle = "";
        $pagetype = "";

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

?>