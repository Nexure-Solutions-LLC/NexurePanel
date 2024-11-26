<?php

    $pagetitle = "Accounts";
    $pagesubtitle = "Delete";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    $accountNumber = $_GET['account_number'] ?? '';
    $accountType = $_GET['account_type'] ?? '';

    if (!$accountNumber) {

        header("location: /error/genericSystemError");
        exit;

    }

    if (!$accountType) {

        // Default to owner if account type is not specified

        $accountType = 'Customer';

    }

    $checkAccountQuery = "SELECT COUNT(*) AS count FROM `nexure_users` WHERE `accountNumber` = ?";
    $stmt = $con->prepare($checkAccountQuery);
    $stmt->bind_param('s', $accountNumber);
    $stmt->execute();
    $stmt->bind_result($accountCount);
    $stmt->fetch();
    $stmt->close();

    if ($accountCount == 0) {
        header("location: /error/genericSystemError");
        exit;
    }

    $manageAccountDefinitionR = new \CaliWebDesign\Generic\VariableDefinitions();
    $manageAccountDefinitionR->manageAccount($con, $accountNumber);

    if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

        if ($variableDefinitionX->paymentProcessorName == "Stripe") {

            include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

        } else {

            header ("location: /error/genericSystemError");

        }

    } else {

        echo 'There are no payment modules available to service this request.';

    }

    delete_customer($manageAccountDefinitionR->customerStripeID);

    if ($accountType === 'Customer') {

        // Delete the owner and all related information

        $deleteQueries = [
            "DELETE FROM `nexure_users` WHERE `accountNumber` = '$accountNumber'",
            "DELETE FROM `nexure_businesses` WHERE `email` = '$manageAccountDefinitionR->customeremail'",
            "DELETE FROM `nexure_ownershipinformation` WHERE `emailAddress` = '$manageAccountDefinitionR->customeremail'"
        ];

    } else {

        // Delete only the authorized user

        $deleteQueries = [
            "DELETE FROM `nexure_users` WHERE `accountNumber` = '$accountNumber' AND `userrole` = '".mysqli_real_escape_string($con, $accountType)."'"
        ];

    }


    foreach ($deleteQueries as $query) {

        if (!mysqli_query($con, $query)) {

            header("location: /error/genericSystemError");
            exit;

        }

    }

    header("location: /dashboard/administration/accounts");

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>