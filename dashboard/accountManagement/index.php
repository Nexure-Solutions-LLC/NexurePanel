<?php

    // ===================== DO NOT TOUCH BELOW THIS LINE IT CONTAINS CORE FUNCTIONS ===================

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    $caliemail = $_SESSION['caliid'];

    $userprofileresult = mysqli_query($con, "SELECT * FROM caliweb_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($userprofileresult);
    mysqli_free_result($userprofileresult);

    // User Profile Variable Definitions

    $userrole = strtolower($userinfo['userrole']);

    // Role-to-title mapping

    $roleTitles = [
        'customer' => 'Account Management - Customer',
        'authorized user' => 'Account Management - Authorized User',
        'administrator' => 'Account Management - Administrator',
        'partner' => 'Account Management - Partners'
    ];

    // Default title if role not found

    $pagetitle = isset($roleTitles[$userrole]) ? $roleTitles[$userrole] : 'Account Management';
    $pagesubtitle = 'General';

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

    // ===================== DO NOT TOUCH ABOVE THIS LINE IT CONTAINS CORE FUNCTIONS ===================

    // Content goes here

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>