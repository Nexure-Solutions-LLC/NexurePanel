<?php

    $pagetitle = "Accounts";
    $pagesubtitle = "Delete";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    $accountNumber = $_GET['account_number'] ?? '';

    $customerAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '".mysqli_real_escape_string($con, $accountNumber)."'");
    $customerAccountInfo = mysqli_fetch_array($customerAccountQuery);
    mysqli_free_result($customerAccountQuery);

    $customeremail = $customerAccountInfo['email'];

    if ($accountNumber) {

        $deleteQueries = [
            "DELETE FROM `caliweb_users` WHERE `accountNumber` = '$accountNumber'",
            "DELETE FROM `caliweb_businesses` WHERE `email` = '$customeremail'",
            "DELETE FROM `caliweb_ownershipinformation` WHERE `emailAddress` = '$customeremail'"
        ];

        foreach ($deleteQueries as $query) {

            if (!mysqli_query($con, $query)) {

                header("location: /error/genericSystemError");
                exit;
                
            }

        }

        header("location: /dashboard/administration/accounts");

    } else {

        header("location: /error/genericSystemError");

    }

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>
