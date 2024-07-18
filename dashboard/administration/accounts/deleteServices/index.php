<?php

    $pagetitle = "Services";
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
    $serviceName = $_GET['service_name'];

    if ($accountNumber != "" || $accountNumber != NULL) {

        $serviceDeleteRequest = "DELETE FROM `caliweb_services` WHERE `accountNumber`= '$accountNumber' AND `serviceName` = '$serviceName'";
        $serviceDeleteResult = mysqli_query($con, $serviceDeleteRequest);

        if ($serviceDeleteResult) {

            header ("location: /dashboard/administration/accounts/manageAccount/?account_number=$accountNumber");

        } else {

            header ("location: /error/genericSystemError");
    
        }

    } else {

        header ("location: /error/genericSystemError");

    }

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>