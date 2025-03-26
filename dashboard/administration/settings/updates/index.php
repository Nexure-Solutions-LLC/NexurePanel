<?php
    $pagetitle = "Settings";
    $pagesubtitle = "Updates";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';
?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
                <div class="caliweb-settings-sidebar">
                    <div class="caliweb-card dashboard-card sidebar-card">
                        <?php  include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Menus/Settings/index.php'); ?>
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

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>