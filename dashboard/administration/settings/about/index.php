<?php
$pagetitle = "Settings";
$pagesubtitle = "About";
$pagetype = "Administration";

include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';
?>

<section class="section first-dashboard-area-cards">
    <div class="container width-98">
        <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
            <div class="caliweb-settings-sidebar">
                <div class="caliweb-card dashboard-card sidebar-card">
                    <aside class="caliweb-sidebar">
                        <ul class="sidebar-list-linked">
                            <li class="sidebar-link"><a href="/dashboard/administration/settings/" class="sidebar-link-a">General</a></li>
                            <li class="sidebar-link"><a href="/dashboard/administration/settings/ipBaning" class="sidebar-link-a">IP Banning</a></li>
                            <li class="sidebar-link"><a href="/license" class="sidebar-link-a">Licencing</a></li>
                            <li class="sidebar-link"><a href="/dashboard/administration/settings/updates" class="sidebar-link-a">Updates</a></li>
                            <li class="sidebar-link active"><a href="/dashboard/administration/settings/about" class="sidebar-link-a">About Cali Panel</a></li>
                        </ul>
                    </aside>
                </div>
            </div>
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card custom-padding-account-card" style="padding-bottom:0; background-color: #f1f1f1">
                    <div class="card-header-account" style="margin-bottom:0; border-bottom:0;">
                        <div class="display-flex align-center">
                            <div class="no-padding margin-10px-right icon-size-formatted">
                                <img src="/assets/img/systemIcons/settingsicon.png" alt="Settings Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                            </div>
                            <div>
                                <p class="no-padding font-14px" style="padding-bottom:4px;">Settings</p>
                                <h4 class="text-bold font-size-16 no-padding display-flex align-center">
                                    About
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="caliweb-card dashboard-card" style="overflow-y:scroll; height:73.5vh;">
                    <div>
                        <img src="https://nexuresolutions.com/assets/img/logos/NexureWideLogoBlack.svg" width="150px" loading="lazy" alt="Cali Web Design Logo" class="caliweb-navbar-logo-img light-mode" style="width:150px;">
                        <img src="https://nexuresolutions.com/assets/img/logos/NexureWideLogoWhite.svg" width="150px" loading="lazy" alt="Cali Web Design Dark Logo" class="caliweb-navbar-logo-img dark-mode" style="width:150px;">
                    </div>
                    <div style="padding-left:5px; padding-right:5px; width:70%;">
                        <div>
                            <h3 style="font-size:20px; margin-top:30px; margin-bottom:4%;"><?php echo $PANEL_ABOUT_TITLE_PRODUCT_NAME ?></h3>
                            <p style="margin-top:20px; font-size:14px;"><?php echo $PANEL_ABOUT_INFO ?></p>
                            <p style="margin-top:20px; font-size:14px; margin-bottom:20px;"><?php echo $PANEL_ABOUT_LICENSE_DISCLAIMER ?></p>
                        </div>
                        <div>
                            <br>
                            <div class="horizantal-line"></div>
                            <br>
                        </div>
                        <div>
                            <p style="margin-top:10px; font-size:14px;">Software Name: Nexure Solutions Panel (Nexure Panel)</p>
                            <p style="margin-top:10px; font-size:14px;">Version: <?php echo $variableDefinitionX->panelVersionName; ?> Developer Beta 3</p>
                            <p style="margin-top:10px; font-size:14px;">Release Date: 08/03/2024 10:18:22 PM (Eastern Time)</p>
                            <p style="margin-top:10px; font-size:14px;">Edition: Nexure Panel Developer Edition</p>
                            <?php

                            echo "<p style='margin-top:10px; font-size:14px;'>Current PHP Version: " . phpversion() . "</p>";
                            echo "<p style='margin-top:10px; font-size:14px;'>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
                            echo "<p style='margin-top:10px; font-size:14px;'>Operating System: " . php_uname('s') . " " . php_uname('r') . "</p>";

                            ?>
                            <p style="margin-top:10px; font-size:14px;">Languages: HTML, CSS, JS, PHP and MySQL</p>
                            <p style="margin-top:1%; font-size:14px; margin-bottom:6%;">Authors: Nexure Solutions LLC, Nick Derry, Mikey W, Mikey Brinkley, Nathan Schwartz, Aiden Webb.</p>
                        </div>
                        <!-- <div id="phpinfo" style="margin-top:6%;">
                                <?php
                                // ob_start();
                                // phpinfo();
                                // $pinfo = ob_get_contents();
                                // ob_end_clean();
                                // echo (str_replace("module_Zend Optimizer", "module_Zend_Optimizer", preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo)));
                                ?>
                            </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>