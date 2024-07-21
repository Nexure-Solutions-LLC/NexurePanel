<?php

    $pagetitle = "Account Management";
    $pagesubtitle = 'General';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container caliweb-container">
            <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
                <div class="caliweb-settings-sidebar">
                    <div class="caliweb-card dashboard-card sidebar-card">
                        <aside class="caliweb-sidebar">
                            <ul class="sidebar-list-linked">
                                <a href="/dashboard/accountManagement/" class="sidebar-link-a"><li class="sidebar-link">Overview</li></a>
                                <a href="/dashboard/accountManagement/accountSettings/" class="sidebar-link-a"><li class="sidebar-link active">Account Settings</li></a>
                                <a href="/dashboard/accountManagement/personalDetails/" class="sidebar-link-a"><li class="sidebar-link">Your Personal Details</a></li></a>
                                <a href="/dashboard/accountManagement/privacyAndSecurity/" class="sidebar-link-a"><li class="sidebar-link">Sign-in & Security</li></a>
                            </ul>
                        </aside>
                    </div>
                </div>
                <div class="caliweb-card dashboard-card">

                    <!-- DO NOT PUT USER PERSONAL INFO IN THIS FILE -->

                </div>
            </div>
        </div>
    </section>


<?php


?>