<?php
    ob_clean();
    ob_start();

    // Cali Panel Required and System Imports

    require($_SERVER["DOCUMENT_ROOT"].'/lang/en_US.php');
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    use GuzzleHttp\Client;
    use IPLib\Factory;
    use Detection\MobileDetect;

    session_start();        
    
    // Check if the user is accessing from a mobile device

    $detect = new MobileDetect();

    if ($detect->isMobile() || $detect->isTablet()) {

        header("Location: /error/mobileExperiance/");
        exit();

    }

    // Custom Error Handler

    function errorHandler($errno, $errstr, $errfile, $errline) {

        $log_timestamp = date("d-m-Y H:i:sa");
        $errorMessage = "Error: [$errno] $errstr in $errfile on line $errline";
        $errorLogFile = $_SERVER["DOCUMENT_ROOT"] . "/error/errorLogs/$log_timestamp.log";

        error_log($errorMessage, 3, $errorLogFile);
        session_start();
        $_SESSION['error_log_file'] = $errorLogFile;

        while (ob_get_level()) {

            ob_end_clean();

        }
        if (headers_sent()) {

            echo '<meta http-equiv="refresh" content="0;url=/error/genericSystemError/">';

        } else {

            header("Location: /error/genericSystemError/");

        }

        exit;

    }

    set_error_handler("errorHandler");

    $error = error_get_last();

    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING])) {

        customErrorHandler($error['type'], $error['message'], $error['file'], $error['line']);

    }

    // Initialize DotEnv for PHP

    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
    $dotenv->load();
    $licenseKeyfromConfig = $_ENV['LICENCE_KEY'];

    if (mysqli_connect_errno()) {

        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();

    }

    // Retreive Users Email Address

    $caliemail = $_SESSION['caliid'];

    // MySQL Queries

    $panelresult = mysqli_query($con, "SELECT * FROM caliweb_panelconfig WHERE id = 1");
    $panelinfo = mysqli_fetch_array($panelresult);
    mysqli_free_result($panelresult);

    $userprofileresult = mysqli_query($con, "SELECT * FROM caliweb_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($userprofileresult);
    mysqli_free_result($userprofileresult);

    // User Profile Variable Definitions

    $userrole = $userinfo['userrole'];
    $fullname = $userinfo['legalName'];
    $accountStatus = $userinfo['accountStatus'];
    $accountStatusReason = $userinfo['statusReason'];
    $employeeAccessLevel = $userinfo['employeeAccessLevel'];

    // Panel Configuration Definitions

    $panelName = $panelinfo['panelName'];
    $orgShortName = $panelinfo['organizationShortName'];
    $orglogolight = $panelinfo['organizationLogoLight'];
    $orglogodark = $panelinfo['organizationLogoDark'];

    // Generic Variable Definitions

    $dataTimestamp = date("M d, Y \a\\t h:i A");
    $datedataOutput = "As of $dataTimestamp";
    $userId = $_ENV['IPCHECKAPIUSER'];
    $apiKey = $_ENV['IPCHECKAPIKEY'];

    // IP Address Checking and Banning

    function getClientIp() {

        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;

    }

    $clientIp = getClientIp();

    function isIpBlocked($ip, $blockedIpList) {

        $ip = Factory::addressFromString($ip);
        if ($ip === null) {

            return false;

        }

        foreach ($blockedIpList as $blockedIp) {

            $range = Factory::rangeFromString($blockedIp);

            if ($range !== null && $range->contains($ip)) {

                return true;

            } elseif ($blockedIp === (string)$ip) {

                return true;

            }

        }

        return false;
    }

    function isIpAllowed($ip, $allowedIpList) {

        $ip = Factory::addressFromString($ip);

        if ($ip === null) {

            return false;
        }

        foreach ($allowedIpList as $allowedIp) {

            $range = Factory::rangeFromString($allowedIp);

            if ($range !== null && $range->contains($ip)) {

                return true;

            } elseif ($allowedIp === (string)$ip) {

                return true;

            }

        }

        return false;

    }

    $blockedIpList = file($_SERVER['DOCUMENT_ROOT'].'/dashboard/company/defaultValues/ip_blocklist.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $allowedIpList = file($_SERVER['DOCUMENT_ROOT'].'/dashboard/company/defaultValues/ip_allowlist.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    /* 

    TODO: RE ADD THIS WHEN WE GO IN PRODUCTION THIS IS TO FIX RATE LIMIT,
    THIS WILL ALSO FIX AND WEIRD JS ISSUED WITH AD BLOCK BUT DONT RELY ON THIS
    THE PANEL WILL ONLY ALLOW MANUAL BLOCKING AND UNBLOCKING AUTO DETECTON
    IS DISABLED FOR DEVELOPMENT PURPOSES, BEFORE PRODUCTION FIX THIS. 
    TEMP USE ONLY. THIS IS NOT RELAIBLE AND SHOULD BE FIXED.
    
    function isIpBlacklistedOrProxyVpn($ip, $userId, $apiKey) {
        $url = "https://neutrinoapi.net/ip-probe";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['ip' => $ip]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-ID: $userId", "API-Key: $apiKey"]);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        if (isset($data['is-hosting']) && $data['is-hosting']) {
            return true;
        }
        if (isset($data['is-proxy']) && $data['is-proxy']) {
            return true;
        }
        if (isset($data['is-vpn']) && $data['is-vpn']) {
            return true;
        }

        return false;
    }

    // AdBlock Checker and Browser Reject

    function hasAdBlocker() {
        if (!isset($_SESSION['ad_blocker_checked'])) {
            echo "<script>
                var adBlockEnabled = false;
                var testAd = document.createElement('div');
                testAd.innerHTML = '&nbsp;';
                testAd.className = 'adsbox';
                document.body.appendChild(testAd);
                window.setTimeout(function() {
                    if (testAd.offsetHeight === 0) {
                        adBlockEnabled = true;
                    }
                    testAd.remove();
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'check_ad_blocker.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('adBlockEnabled=' + adBlockEnabled);
                }, 100);
            </script>";
            $_SESSION['ad_blocker_checked'] = true;
        }

        if (isset($_SESSION['adBlockEnabled']) && $_SESSION['adBlockEnabled']) {
            return true;
        }

        return false;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adBlockEnabled'])) {
        $_SESSION['adBlockEnabled'] = $_POST['adBlockEnabled'] == 'true' ? true : false;
        exit;
    }

    function isIPSpamListed($ip, $userId, $apiKey) {
        $url = "https://neutrinoapi.net/host-reputation";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'host' => $ip,
            'list-rating' => '3',
            'zones' => ''
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-ID: $userId", "API-Key: $apiKey"]);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        if (isset($data['is-listed']) && $data['is-listed']) {
            return true;
        }

        return false;
    }

    */

    function banIp($ip) {

        header("Location: /error/bannedUser");
        exit;

    }

    if (!isIpAllowed($clientIp, $allowedIpList)) {
        /*
        if (isIpBlacklistedOrProxyVpn($clientIp, $userId, $apiKey)) {
            banIp($clientIp);
        }

        if (isIPSpamListed($clientIp, $userId, $apiKey)) {
            banIp($clientIp);
        }

        if (hasAdBlocker()) {
            banIp($clientIp);
        }
        */

        if (isIpBlocked($clientIp, $blockedIpList)) {

            banIp($clientIp);

        }
    }

    // Checks the users account staus and send them to the right page.
    // If the user is active load the dashboard like normal.

    if ($accountStatus == "Under Review") {

        header ("Location: /error/underReviewAccount");

    } else if ($accountStatus == "Suspended") {

        header ("Location: /error/suspendedAccount");

    } else if ($accountStatus == "Terminated") {

        header ("Location: /error/terminatedAccount");

    } else if ($accountStatus == "Closed" && $accountStatusReason == "The customer is runing a prohibited business and their application was denied.") {

        header("Location: /onboarding/decision/deniedApp");

    }

?>
<!DOCTYPE html>
<!-- 
       ______      ___    _       __     __       ____            _           
      / ____/___ _/ (_)  | |     / /__  / /_     / __ \___  _____(_)___ _____ 
     / /   / __ `/ / /   | | /| / / _ \/ __ \   / / / / _ \/ ___/ / __ `/ __ \
    / /___/ /_/ / / /    | |/ |/ /  __/ /_/ /  / /_/ /  __(__  ) / /_/ / / / /
    \____/\__,_/_/_/     |__/|__/\___/_.___/  /_____/\___/____/_/\__, /_/ /_/ 
                                                                /____/        

    This site was created by Cali Web Design Corporation. http://www.caliwebdesignservices.com
    Last Published: Fri Feb 2 2024 at 08:38:52 PM (Pacific Daylight Time US and Canada) 

    Creator/Developer: Cali Web Design Development Team, Michael Brinkley, Nick Derry, All logos other 
    than ours go to their respectful owners. Images are provided by undraw.co as well as pexels.com 
    and are opensource svgs, or images to use by Cali Web Design Corporation.

    Website Registration Code: 49503994-20344
    Registration Date: July 09 2022
    Initial Development On: Jun 30 2023
    Last Update: Feb 2th 2024
    Website Version: 20.0.0
    Expiration Date: 02/19/2088 (LTSB Long-Term Servicing Branch)
    Contact Information:
        Phone: +1-877-597-7325
        Email: support@caliwebdesignservices.com

    Copyright Statement: Do not copy this website, if the code is found to be duplicated, reproduced,
    or copied we will fine you a minimum of $250,000 and criminal charges may be pressed.

    CopyOurCodeWeWillSendYouToJesus(C)2024ThisIsOurHardWork.

    Dear rule breakers, questioners, straight-A students who skipped class: We want you.
    https://caliwebdesignservices.com/careers.
    

-->
<html lang="en-us">
    <head>
        <script src="https://caliwebdesignservices.com/assets/js/darkmode.js" type="text/javascript"></script>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="author" content="Cali Web Design Development Team, Nick Derry, Michael Brinkley">
        <link href="https://caliwebdesignservices.com/assets/css/2024-01-29-styling.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/dashboard-css-2024.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="apple-touch-icon" sizes="180x180" href="https://caliwebdesignservices.com/assets/img/favico/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://caliwebdesignservices.com/assets/img/favico/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="https://caliwebdesignservices.com/assets/img/favico/favicon-16x16.png">
        <link rel="manifest" href="https://caliwebdesignservices.com/assets/img/favico/site.webmanifest">
        <?php include($_SERVER["DOCUMENT_ROOT"]."/dashboard/company/themes/index.php"); ?>
        <?php
            if ($pagetitle == "Client") {

                echo '<link href="/assets/css/client-dashboard-css-2024.css" rel="stylesheet" type="text/css" />';

            } else {

                echo '';
                
            }
        ?>
        <script type="text/javascript">   
            window.antiFlicker = {
                active: true,
                timeout: 3000
            }           
        </script>
        <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
        <script src="https://js.stripe.com/v3/"></script>
        <style id="no-bg-img">*{background-image:none!important /* Remove Background Images until the DOM Loads */}</style>
    </head>
    <body>

        <div class="caliweb-navbar" id="caliweb-header">
            <div class="container caliweb-navbar-container">
                <div class="caliweb-navbar-logo">
                    <a href="https://caliwebdesignservices.com/">
                        <img src="<?php echo $orglogolight; ?>" width="100px" loading="lazy" alt="Light Logo" class="caliweb-navbar-logo-img light-mode">
                        <img src="<?php echo $orglogodark; ?>" width="100px" loading="lazy" alt=" Dark Logo" class="caliweb-navbar-logo-img dark-mode">
                    </a>
                </div>
                <div class="caliweb-header-search">
                    <input class="form-input caliweb-search-input" placeholder="Search all of <?php echo $orgShortName ?>" />
                </div>
                <div class="caliweb-nav-buttons">
                    <a href="/dashboard/accountManagement" class="caliweb-nav-button secondary"><?php echo $fullname; ?></a>
                    <span class="toggle-container">
                        <span class="lnr lnr-sun" class="toggle-input" id="lightModeIcon"></span>
                        <span class="lnr lnr-moon"  class="toggle-input" id="darkModeIcon"></span>
                    </span>
                </div>
                <button style="background-color:transparent; border:none; outline:0;" href="javascript:void(0);" class="caliweb-menu-icon" aria-label="Mobile Menu" onclick="responsiveMenu()">
                    <img src="https://caliwebdesignservices.com/assets/img/systemicons/menu.svg" loading="lazy" width="24" alt="" class="menu-icon">
                </button>
            </div>
            <div class="container display-flex align-center">
                <nav class="caliweb-navbar-menu" id="caliweb-navigation">
                    <?php include($_SERVER["DOCUMENT_ROOT"]."/assets/php/headerMenuConfiguration.php"); ?>
                </nav>
                <div class="systemLoads">
                    <span>
                        <?php

                            $loads = sys_getloadavg();

                            $rounded_loads = array_map(function($load) {

                                return number_format($load, 2);

                            }, $loads);

                            echo "System Loads: " . implode(", ", $rounded_loads);

                        ?>
                    </span>
                </div>
            </div>
        </div>

