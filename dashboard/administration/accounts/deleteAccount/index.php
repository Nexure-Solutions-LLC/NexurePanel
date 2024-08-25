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

    if ($accountType === 'Customer') {

        // Query for the owner's account information

        $customerAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '".mysqli_real_escape_string($con, $accountNumber)."' AND userrole = 'Customer'");
   
    } else {

        // Query for the authorized user's account information

        $customerAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '".mysqli_real_escape_string($con, $accountNumber)."' AND userrole = '".mysqli_real_escape_string($con, $accountType)."'");
    
    }

    $customerAccountInfo = mysqli_fetch_array($customerAccountQuery);
    mysqli_free_result($customerAccountQuery);

    if (!$customerAccountInfo) {
        header("location: /error/genericSystemError");
        exit;
    }

    $customeremail = $customerAccountInfo['email'];

    if ($accountType === 'owner') {

        // Delete the owner and all related information

        $deleteQueries = [
            "DELETE FROM `caliweb_users` WHERE `accountNumber` = '$accountNumber'",
            "DELETE FROM `caliweb_businesses` WHERE `email` = '$customeremail'",
            "DELETE FROM `caliweb_ownershipinformation` WHERE `emailAddress` = '$customeremail'"
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