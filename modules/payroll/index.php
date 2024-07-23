<?php

    // Cali Web Design Payroll Module
    // Version: 1.2.5
    // (C) Copyright Cali Web Design Corporation - All rights reserved
    // DISMANTLING, REVERSE ENGINEERING, OR MODIFICATION OF THIS MODULE IS PROHIBITED.

    // This module is not included in the Cali Panel software by default
    // the Cali Web Design Payroll Module will allow you to run payroll on
    // certain time schedules, record and track employee work time and
    // run payments (with an integrated bank module), and records and keep track of
    // 1099s and W-2 Forms.

    // THIS MODULE WILL NOT SUBMIT THE FORMS TO THE IRS NOR SUBMIT ANY EARNINGS TO THE IRS.
    // YOU ARE FULLY RESPONSIBLE, THIS JUST ALLOWS AUTOMATING OF ACH PAYMENTS AND TIME KEEPING.

    $pagetitle = "Payroll";
    $pagesubtitle = "Initalizing";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

    $sql = "SELECT * FROM caliweb_cases";
    $result = mysqli_query($con, $sql);

    switch ($currentAccount->accessLevel->name) {
        case "Executive":
            header("location:/modules/payroll/ceoSuite");
            break;
        case "Manager":
            break;
        default:
            header("location:/modules/payroll/employeeView");
            break;
    }


?>