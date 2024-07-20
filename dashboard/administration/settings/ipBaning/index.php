<?php
    $pagetitle = "Settings";
    $pagesubtitle = "System Setup";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';
?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
                <div class="caliweb-settings-sidebar">
                    <div class="caliweb-card dashboard-card sidebar-card">
                        <aside class="caliweb-sidebar">
                            <ul class="sidebar-list-linked">
                                <li class="sidebar-link active"><a href="/dashboard/administration/settings/" class="sidebar-link-a">General</a></li>
                                <li class="sidebar-link"><a href="/dashboard/administration/settings/ipBaning" class="sidebar-link-a">IP Baning</a></li>
                                <li class="sidebar-link"><a href="/license" class="sidebar-link-a">Licencing</a></li>
                                <li class="sidebar-link"><a href="/dashboard/administration/settings/updates" class="sidebar-link-a">Updates</a></li>
                                <li class="sidebar-link"><a href="/dashboard/administration/settings/about" class="sidebar-link-a">About Cali Panel</a></li>
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

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>