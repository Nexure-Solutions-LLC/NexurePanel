<?php

    $pagetitle = "Accounts";
    $pagesubtitle = "Delete";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    $employeeID = $_GET['employee_id'] ?? '';

    if (!$employeeID) {
        header("location: /error/genericSystemError");
        exit;
    }
    

    $checkEmployeeQuery = "SELECT email FROM `nexure_users` WHERE `employeeID` = ?";
    $stmt = $con->prepare($checkEmployeeQuery);
    $stmt->bind_param('s', $employeeID);
    $stmt->execute();
    $stmt->bind_result($employeeEmail);
    $stmt->fetch();
    $stmt->close();


    if (!$employeeEmail) {
        header("location: /error/genericSystemError");
        exit;
    }


    // Delete queries for removing employee

    $deleteQueries = [
        "DELETE FROM `nexure_users` WHERE `email` = ?",
        "DELETE FROM `nexure_payroll` WHERE `employeeID` = ?"
    ];


    foreach ($deleteQueries as $query) {

        $stmt = $con->prepare($query);

        if (str_contains($query, 'email')) {

            $stmt->bind_param('s', $employeeEmail);

        } else {

            $stmt->bind_param('s', $employeeID);
        }

        if (!$stmt->execute()) {

            header("location: /error/genericSystemError");
            exit;

        }

        $stmt->close();

    }

    header("location: /dashboard/administration/accounts");

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>
