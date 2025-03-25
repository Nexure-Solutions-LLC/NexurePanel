<?php

    require($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/Login/Headers/index.php");

    echo '<title>'.$variableDefinitionX->orgShortName.' - Generic Error</title>';

    echo '<section class="section" style="padding-top:5%; padding-left:5%;">
            <div class="container caliweb-container">
                <h3 class="caliweb-login-heading license-text-dark">'.$LANG_LINKEDROLES_SUCCESS_PAR_1.' <span style="font-weight:700;">'.$LANG_LINKEDROLES_SUCCESS_PAR_2.'</span></h3>
                <p class="caliweb-login-sublink license-text-dark" style="font-weight:700; padding-top:0; margin-top:10px; margin-bottom:10px;">'.$LANG_LINKEDROLES_SUCCESS_TITLE.'</p>
                <p class="caliweb-login-sublink license-text-dark width-50">'.$LANG_LINKEDROLES_SUCCESS_TEXT.'</p>
            </div>
        </section>';

    echo '<div class="caliweb-login-footer license-footer">
            <div class="container caliweb-container">
                <div class="caliweb-grid-2">
                    <div class="">
                        <p class="caliweb-login-footer-text">&copy; <span id="nexure-year"></span> - Nexure Solutions LLC - All rights reserved. It is illegal to copy this website.</p>
                    </div>
                    <div class="list-links-footer">
                        <a href="'.$variableDefinitionX->paneldomain.'/terms">Terms of Service</a>
                        <a href="'.$variableDefinitionX->paneldomain.'/privacy">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>';

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Login/Footers/index.php');

?>