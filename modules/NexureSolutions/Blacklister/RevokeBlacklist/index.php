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

    $blacklistRevokeRequest = "UPDATE `nexure_blacklists` SET `status`= 'Revoked' WHERE `accountNumber` = '$accountnumber'";

    $blacklistDeleteResult = mysqli_query($con, $blacklistRevokeRequest);

    $blacklistAccountUpdateRequest = "UPDATE `nexure_users` SET `accountStatus`= 'Under Review', `statusReason` = 'This account previously had a blacklist and it was revoked.' WHERE `accountNumber` = '$accountnumber'";
    
    $blacklistAccountUpdateResult = mysqli_query($con, $blacklistAccountUpdateRequest);

    if ($blacklistDeleteResult && $blacklistAccountUpdateResult) {

        header ("location: /modules/NexureSolutions/Blacklister/");

    } else {

        header ("location: /error/genericSystemError");

    }

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>