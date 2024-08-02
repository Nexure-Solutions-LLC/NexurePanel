<?php
    $pagetitle = "Suspended";
    $pagesubtitle = "Your account has been suspended";
    $pagetype = "Status Page";

    require($_SERVER["DOCUMENT_ROOT"].'/components/CaliStatus/Status.php');

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
                        <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Services LLC - All rights reserved. It is illegal to copy this website.</p>
                    </div>
                    <div class="list-links-footer">
                        <a href="'.$paneldomain.'/terms">Terms of Service</a>
                        <a href="'.$paneldomain.'/privacy">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>';

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Login.php');

?>