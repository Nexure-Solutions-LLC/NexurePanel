<?php

    $pagetitle = "Services";
    $pagesubtitle = "Delete";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    $accountNumber = $_GET['account_number'] ?? '';
    $serviceID = $_GET['service_id'] ?? '';

    if (!$accountNumber) {

        header("location: /error/genericSystemError");
        exit;

    }

    if (!$serviceID) {

        header("location: /error/genericSystemError");
        exit;

    }

    $checkServiceQuery = "SELECT COUNT(*) AS count FROM `caliweb_services` WHERE `accountNumber` = ? AND `serviceIdentifier` = ?";
    $stmt = $con->prepare($checkServiceQuery);
    $stmt->bind_param('ss', $accountNumber, $serviceID);
    $stmt->execute();
    $stmt->bind_result($serviceCount);
    $stmt->fetch();
    $stmt->close();

    if ($serviceCount == 0) {

        header("location: /error/genericSystemError");
        exit;

    }

    $deleteQueries = [
        "DELETE FROM `caliweb_services` WHERE `accountNumber`= '$accountNumber' AND `serviceIdentifier` = '$serviceID'"
    ];

    foreach ($deleteQueries as $query) {

        if (!mysqli_query($con, $query)) {

            header("location: /error/genericSystemError");
            exit;

        }

    }

    header("location: /dashboard/administration/accounts/manageAccount/?account_number=$accountNumber");

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>