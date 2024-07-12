<?php
    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    use GuzzleHttp\Client;
    use IPLib\Factory;

    function getClientIp() {

       $ipaddress = '';
       if (getenv('HTTP_CLIENT_IP'))
           $ipaddress = getenv('HTTP_CLIENT_IP');
       else if(getenv('HTTP_X_FORWARDED_FOR'))
           $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
       else if(getenv('HTTP_X_FORWARDED'))
           $ipaddress = getenv('HTTP_X_FORWARDED');
       else if(getenv('HTTP_FORWARDED_FOR'))
           $ipaddress = getenv('HTTP_FORWARDED_FOR');
       else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
       else if(getenv('REMOTE_ADDR'))
           $ipaddress = getenv('REMOTE_ADDR');
       else
           $ipaddress = 'UNKNOWN';
       return $ipaddress;

    }

    $clientIp = getClientIp();

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

    $allowedIpList = file($_SERVER['DOCUMENT_ROOT'].'/dashboard/company/defaultValues/ip_allowlist.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (isIpAllowed($clientIp, $allowedIpList)) {

        header("location:/login");
        
    }

    include($_SERVER["DOCUMENT_ROOT"]."/assets/php/loginHeader.php");
    include($_SERVER["DOCUMENT_ROOT"]."/lang/en_US.php");

    echo '<title>'.$orgshortname.' - Generic Error</title>';

    echo '<section class="section" style="padding-top:10%; padding-left:15%;">
            <div class="container caliweb-container">
                <div style="display:flex; align-items:center;" class="mobile-experiance">
                    <div style="margin-right:2%;">
                        <img src="/assets/img/systemIcons/banned.webp" style="height:30px; width:30px;" />
                    </div>
                    <div>
                        <h3 class="caliweb-login-heading license-text-dark">'.$LANG_BANNED_TITLE.'</h3>
                        <p class="caliweb-login-sublink license-text-dark width-75">'.$LANG_BANNED_TEXT.'</p>
                    </div>
                </div>

    ';

    echo '
            </div>
        </section>';

    echo '<div class="caliweb-login-footer license-footer">
            <div class="container caliweb-container">
                <div class="caliweb-grid-2">
                    <div class="">
                        <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Corporation - All rights reserved. It is illegal to copy this website.</p>
                    </div>
                    <div class="list-links-footer">
                        <a href="'.$paneldomain.'/terms">Terms of Service</a>
                        <a href="'.$paneldomain.'/privacy">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>';

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/loginFooter.php');

?>