<?php

    $pagetitle = "Account Management";
    $pagesubtitle = 'Privacy and Security';
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

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>