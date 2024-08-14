<?php
    $pagetitle = "Account Management";
    $pagesubtitle = 'Account Settings';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';
?>
<section class="section first-dashboard-area-cards">
    <div class="container caliweb-container">
        <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
            <div class="caliweb-settings-sidebar">
                <div class="caliweb-card dashboard-card sidebar-card" style="overflow-y: scroll;">
                    <?php

                        include($_SERVER["DOCUMENT_ROOT"].'/components/CaliSidebars/AccountManagement.php');

                    ?>
                </div>
            </div>
            <div class="caliweb-card dashboard-card">
                <h4 class="text-bold font-size-20 no-padding">d</h4>
                <!-- DO NOT PUT USER PERSONAL INFO IN THIS FILE -->
            </div>
        </div>
    </div>
</section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>
