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

    $checkAccountQuery = "SELECT COUNT(*) AS count FROM `caliweb_users` WHERE `accountNumber` = ?";
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

    if ($accountType === 'Customer') {

        // Delete the owner and all related information

        $deleteQueries = [
            "DELETE FROM `caliweb_users` WHERE `accountNumber` = '$accountNumber'",
            "DELETE FROM `caliweb_businesses` WHERE `email` = '$manageAccountDefinitionR->customeremail'",
            "DELETE FROM `caliweb_ownershipinformation` WHERE `emailAddress` = '$manageAccountDefinitionR->customeremail'"
        ];

    } else {

        // Delete only the authorized user

        $deleteQueries = [
            "DELETE FROM `caliweb_users` WHERE `accountNumber` = '$accountNumber' AND `userrole` = '".mysqli_real_escape_string($con, $accountType)."'"
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