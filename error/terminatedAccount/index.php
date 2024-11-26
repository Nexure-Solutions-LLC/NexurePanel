<?php

    $pagetitle = "Terminated";
    $pagesubtitle = "Your account has been terminated";
    $pagetype = "Status Page";

    require($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Status/Headers/index.php');

    echo '<title>'.$variableDefinitionX->orgShortName.' - Terminated</title>';

    echo '<section class="section" style="padding-top:10%; padding-left:15%;">
            <div class="container caliweb-container">
                <div style="display:flex; align-items:center;" class="mobile-experiance">
                    <div style="margin-right:2%;">
                        <img src="/assets/img/systemIcons/terminatedicon.png" style="height:30px; width:30px;" />
                    </div>
                    <div>
                        <h3 class="caliweb-login-heading license-text-dark">'.$LANG_TERMINATED_TITLE.'</h3>
                        <p class="caliweb-login-sublink license-text-dark width-75">'.$LANG_TERMINATED_TEXT.'</p>
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
                        <p class="caliweb-login-footer-text">&copy; 2024 - Nexure Solutions LLC - All rights reserved. It is illegal to copy this website.</p>
                    </div>
                    <div class="list-links-footer">
                        <a href="'.$variableDefinitionX->paneldomain.'/terms">Terms of Service</a>
                        <a href="'.$variableDefinitionX->paneldomain.'/privacy">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>';

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Login/Footers/index.php');

?>