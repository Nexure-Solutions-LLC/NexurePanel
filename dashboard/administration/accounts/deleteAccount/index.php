<?php

    $pagetitle = "Accounts";
    $pagesubtitle = "Delete";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    $lowerrole = strtolower($userrole);
    
    switch ($lowerrole) {
        case "authorized user":
            header("location:/dashboard/customers/authorizedUserView");
            break;
        case "partner":
            header("location:/dashboard/partnerships");
            break;
        case "customer":
            header("location:/dashboard/customers");
            break;
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