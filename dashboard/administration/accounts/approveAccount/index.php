<?php

    $pagetitle = "Accounts";
    $pagesubtitle = "Approve";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    $accountNumber = $_GET['account_number'] ?? '';
    $accountType = $_GET['account_type'] ?? '';

    if (!$accountNumber) {
        header("location: /error/genericSystemError");
        exit;
    }

    if (!$accountType) {
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

    $updateAccountQuery = "UPDATE `nexure_users` SET `accountStatus` = 'Active', `statusReason` = '' WHERE `accountNumber` = ?";
    $stmt = $con->prepare($updateAccountQuery);
    $stmt->bind_param('s', $accountNumber);
    
    if (!$stmt->execute()) {
        header("location: /error/genericSystemError");
        exit;
    }

    $stmt->close();

    header("location: /dashboard/administration/accounts");

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>
