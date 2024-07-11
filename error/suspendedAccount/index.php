<?php
    ob_clean();
    ob_start();

    include($_SERVER["DOCUMENT_ROOT"]."/assets/php/loginHeader.php");
    include($_SERVER["DOCUMENT_ROOT"]."/lang/en_US.php");
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');

    // Retreive Users Email Address

    $caliemail = $_SESSION['caliid'];

    // MySQL Queries

    $userprofileresult = mysqli_query($con, "SELECT * FROM caliweb_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($userprofileresult);
    mysqli_free_result($userprofileresult);

    // User Profile Variable Definitions
    
    $accountStatus = $userinfo['accountStatus'];

    // Checks the users account staus and send them to the right page.
    // If the user is active load the dashboard like normal.

    if ($accountStatus == "Under Review") {

        header ("Location: /error/underReviewAccount");

    } else if ($accountStatus == "Active") {

        header ("Location: /dashboard/customers/");

    } else if ($accountStatus == "Terminated") {

        header ("Location: /error/terminatedAccount");

    }

    echo '<title>'.$orgshortname.' - Suspended</title>';

    echo '<section class="section" style="padding-top:10%; padding-left:15%;">
            <div class="container caliweb-container">
                <div style="display:flex; align-items:center;" class="mobile-experiance">
                    <div style="margin-right:2%;">
                        <img src="/assets/img/systemIcons/suspendedicon.png" style="height:30px; width:30px;" />
                    </div>
                    <div>
                        <h3 class="caliweb-login-heading license-text-dark">'.$LANG_SUSPENDED_TITLE.'</h3>
                        <p class="caliweb-login-sublink license-text-dark width-75">'.$LANG_SUSPENDED_TEXT.'</p>
                        <p class="caliweb-login-sublink license-text-dark width-75">'.$LANG_SUSPENDED_CONTACT_INFO.'</p>
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