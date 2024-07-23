<?php
    $pagetitle = "Settings";
    $pagesubtitle = "System Setup";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';
?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
                <div class="caliweb-settings-sidebar">
                    <div class="caliweb-card dashboard-card sidebar-card">
                        <aside class="caliweb-sidebar">
                            <ul class="sidebar-list-linked">
                                <a href="/dashboard/administration/settings/" class="sidebar-link-a"><li class="sidebar-link active">General</li></a>
                                <a href="/dashboard/administration/settings/ipBaning" class="sidebar-link-a"><li class="sidebar-link">IP Baning</li></a>
                                <a href="/licensing/" class="sidebar-link-a"><li class="sidebar-link">Licencing</li></a>
                                <a href="/dashboard/administration/settings/update" class="sidebar-link-a"><li class="sidebar-link">Updates</li></a>
                                <a href="/dashboard/administration/settings/about" class="sidebar-link-a"><li class="sidebar-link">About Cali Panel</li></a>
                            </ul>
                        </aside>
                    </div>
                </div>
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <div class="caliweb-card dashboard-card">
                        Content
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>