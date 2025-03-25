<?php

    $pagetitle = "Blacklister";
    $pagesubtitle = "View Blacklist";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    
    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    $accountnumber = $_GET['account_number'] ?? '';

    if (!$accountnumber) {

        header("location: /modules/NexureSolutions/Blacklister/");

        exit;

    }

    $blacklistDeleteRequest = "DELETE FROM `nexure_blacklists` WHERE `accountNumber` = '$accountnumber'";

    $blacklistDeleteResult = mysqli_query($con, $blacklistDeleteRequest);

    if ($blacklistDeleteResult) {

        header ("location: /modules/NexureSolutions/Blacklister/");

    } else {

        header ("location: /error/genericSystemError");

    }

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>