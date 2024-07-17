<?php
    $pagetitle = "Settings";
    $pagesubtitle = "About";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    $lowerrole = strtolower($userrole);
    
    switch ($lowerrole) {
        case "authorized user":
            header("location:/dashboard/customers/authorizedUserView");
            break;
        case "partner":
            header("location:/dashboard/partnerships");
            break;
        case "customer":
            header("location:/dashboard/customers");
            break;
    }

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';
?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
                <div class="caliweb-settings-sidebar">
                    <div class="caliweb-card dashboard-card">
                        <aside class="caliweb-sidebar">
                            <ul class="sidebar-list-linked">
                                <li class="sidebar-link"><a href="/dashboard/administration/settings/" class="sidebar-link-a">General</a></li>
                                <li class="sidebar-link"><a href="/license" class="sidebar-link-a">Licencing</a></li>
                                <li class="sidebar-link"><a href="/dashboard/administration/settings/updates" class="sidebar-link-a">Updates</a></li>
                                <li class="sidebar-link active"><a href="/dashboard/administration/settings/about" class="sidebar-link-a">About Cali Panel</a></li>
                            </ul>
                        </aside>
                    </div>
                </div>
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <div class="caliweb-card dashboard-card">
                        <div>
                            <img src="https://caliwebdesignservices.com/assets/img/logos/CaliWebDesign-Logo.svg" width="150px" loading="lazy" alt="Cali Web Design Logo" class="caliweb-navbar-logo-img light-mode" style="width:150px;">
                            <img src="https://caliwebdesignservices.com/assets/img/logos/CaliWebDesign-WhiteLogo.svg" width="150px" loading="lazy" alt="Cali Web Design Dark Logo" class="caliweb-navbar-logo-img dark-mode" style="width:150px;">
                        </div>
                        <div style="padding-left:5px; padding-right:5px; width:70%;">
                            <div>
                                <h3 style="font-size:20px; margin-top:20px;">Cali Web Design Panel 20.0.1 Web Version</h3>
                                <p style="margin-top:20px; font-size:14px;">This software was created by Cali Web Design Corporation. This software allows the ability to run your business from one place. The software is equipt with OAuth, CRM, Payroll, Time Keeping, Merchant Proccessing, Web Site Editing, and tons of other features your business needs to succeed. The panel is completely modular so you can remove and add features or develop your own features as needed for your type of business.</p>
                                <p style="margin-top:20px; font-size:14px; margin-bottom:20px;">THE BASE CODE OF THIS SOFTWARE IS OPEN SOURCE AND FREE TO USE UNDER THE COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL). THE AUTHOR OF DERIVATIVE WORKS OF THIS SOFTWRE MUST NOTIFY CALI WEB DESIGN OF CHANGES. CERTAIN MODULES AND PAID VERSIONS OF THIS SOFTWARE WE DEVELOP ARE NOT OPEN SOURCE.</p>
                            </div>
                            <div>
                                <br>
                                    <div class="horizantal-line"></div>
                                <br>
                            </div>
                            <div>
                                <p style="margin-top:10px; font-size:14px;">Software Name: Cali Web Design Panel (Cali Panel)</p>
                                <p style="margin-top:10px; font-size:14px;">Version: 20.0.1 Developer Beta 1</p>
                                <p style="margin-top:10px; font-size:14px;">Release Date: 06/21/2024 8:42:05 PM (Eastern Time)</p>
                                <p style="margin-top:10px; font-size:14px;">Edition: Cali Panel Free and Open Source</p>
                                <?php

                                    echo "<p style='margin-top:10px; font-size:14px;'>Current PHP Version: " . phpversion() . "</p>";
                                    echo "<p style='margin-top:10px; font-size:14px;'>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
                                    echo "<p style='margin-top:10px; font-size:14px;'>Operating System: " . php_uname('s') . " " . php_uname('r') . "</p>";

                                ?>
                                <p style="margin-top:10px; font-size:14px; margin-bottom:20px;">Languages: HTML, CSS, JS, PHP and MySQL</p>
                            </div>
                            <div>
                                <br>
                                    <div class="horizantal-line"></div>
                                <br>
                            </div>
                            <div class="custom-phpinfo">
                                <?php
                                    
                                  //  include($_SERVER["DOCUMENT_ROOT"].'/dashboard/administration/settings/about/phpInfo/index.php');
                                    
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>