<?php

    $pagetitle = "Accounts";
    $pagesubtitle = "Delete";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    if ($userrole == "Customer" || $userrole == "customer") {

        header("location:/dashboard/customers");

    } else if ($userrole == "Authorized User" || $userrole == "authorized user") {

        header("location:/dashboard/customers/authorizedUserView");

    } else if ($userrole == "Partner" || $userrole == "partner") {

        header("location:/dashboard/partnerships");

    }

    $accountNumber = $_GET['account_number'];

    if ($accountNumber != "" || $accountNumber != NULL) {

        $accountDeleteRequest = "DELETE FROM `caliweb_users` WHERE `accountNumber`= '$accountNumber'";
        $accountDeleteResult = mysqli_query($con, $accountDeleteRequest);

        if ($accountDeleteResult) {

            header ("location: /dashboard/administration/accounts");

        } else {

            header ("location: /error/genericSystemError");
    
        }

    } else {

        header ("location: /error/genericSystemError");

    }

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>