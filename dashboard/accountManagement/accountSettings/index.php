<?php
    $pagetitle = "Account Management";
    $pagesubtitle = 'Account Settings';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';
?>
<section class="section first-dashboard-area-cards">
    <div class="container caliweb-container">
        <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
            <div class="caliweb-settings-sidebar">
                <div class="caliweb-card dashboard-card sidebar-card" style="overflow-y: scroll;">
                    <?php

                        include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Account/Sidebars/index.php');

                    ?>
                </div>
            </div>
            <div class="caliweb-card dashboard-card">
                <h4 class="text-bold font-size-20 no-padding">Account Settings</h4>
                <p style="margin-top:10px; font-size:14px;">Update your account information like profile photo, integrations, account nicknames, and more.</p>
                <div class="settings-area">
                    <form action="" method="POST">

                        <input type="file" class="form-input"  />

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>
