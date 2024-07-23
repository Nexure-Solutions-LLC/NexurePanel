<?php

    $pagetitle = "Account Management";
    $pagesubtitle = 'General';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

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
                                <a href="/dashboard/accountManagement/accountSettings/" class="sidebar-link-a"><li class="sidebar-link">Account Settings</li></a>
                                <a href="/dashboard/accountManagement/personalDetails/" class="sidebar-link-a"><li class="sidebar-link">Your Personal Details</a></li></a>
                                <a href="/dashboard/accountManagement/privacyAndSecurity/" class="sidebar-link-a"><li class="sidebar-link active">Sign-in & Security</li></a>
                            </ul>
                        </aside>
                    </div>
                </div>
                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div>
                            <h3 class="font-size-20 no-padding">Security and Privacy</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php


?>