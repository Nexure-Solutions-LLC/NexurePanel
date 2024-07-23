<?php

    $pagetitle = "Services";
    $pagesubtitle = "Delete";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

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

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>