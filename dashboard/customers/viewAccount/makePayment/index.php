<?php

    $pagetitle = "Client";
    $pagesubtitle = "Make Payment";
    $pagetype = "Client";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    $accountnumber = $_GET['account_number'];

    if ($accountnumber == "") {

        header("location: /dashboard/customers");

    }

    $manageAccountDefinitionR = new \CaliWebDesign\Generic\VariableDefinitions();
    $manageAccountDefinitionR->manageAccount($con, $accountnumber);

    $customerAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '".$accountnumber."'");

    $customerAccountInfo = mysqli_fetch_array($customerAccountQuery);

    mysqli_free_result($customerAccountQuery);

    if (!$customerAccountInfo) {

        header("location: /dashboard/administration/accounts");
        exit();

    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

            if ($variableDefinitionX->paymentProcessorName == "Stripe") {

                include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

            } else {

                header ("location: /error/genericSystemError");

            }

        } else {

            echo 'There are no payment modules available to service this request.';

        }

        $amount = filter_input(INPUT_POST, 'balanceNumber', FILTER_VALIDATE_FLOAT);

        $message = chargeCustomer($manageAccountDefinitionR->customerStripeID, $amount);

        if ($message == "Success") {

            header("location:/dashboard/customers/viewAccount/?account_number=" . $accountnumber);

        } else {

            header("Location: /error/genericSystemError");

        }

    } else {

        header("location: /dashboard/customers");

    }

?>