<?php

    include($_SERVER["DOCUMENT_ROOT"]."/components/CaliHeaders/Login.php");

    echo '<title>'.$orgshortname.' - Generic Error</title>';

    echo '<section class="section" style="padding-top:5%; padding-left:5%;">
            <div class="container caliweb-container">
                <h3 class="caliweb-login-heading license-text-dark">'.$LANG_GENERIC_ERROR_TITLE_PAR_1.' <span style="font-weight:700;">'.$LANG_GENERIC_ERROR_TITLE_PAR_2.'</span></h3>
                <p class="caliweb-login-sublink license-text-dark" style="font-weight:700; padding-top:0; margin-top:0;">'.$LANG_GENERIC_ERROR_TITLE.'</p>
                <p class="caliweb-login-sublink license-text-dark width-50">'.$LANG_GENERIC_ERROR_TEXT.'</p>
    ';

    if(isset($_SESSION['error_log_file'])) {

        $errorLogFilePath = $_SESSION['error_log_file'];
        $errorLogContent = file_get_contents($errorLogFilePath);

        echo "<pre>$errorLogContent</pre>";
        unset($_SESSION['error_log_file']);

    } else {

        echo "";
        
    }

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