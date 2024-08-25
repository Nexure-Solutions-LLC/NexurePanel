<?php

    session_start();
    unset($_SESSION['pagetitle']);

    $pagetitle = "Account Management";
    $pagesubtitle = 'Account Settings';
    $_SESSION['pagetitle'] = "Account Management";
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    $accountModulesLookupQuery = "SELECT * FROM caliweb_modules WHERE moduleStatus = 'Active' AND modulePositionType = 'Authentication'";
    $accountModulesLookupResult = mysqli_query($con, $accountModulesLookupQuery);

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
                <h4 class="text-bold font-size-20 no-padding" style="margin-top:1%;">Integrations</h4>
                <div class="caliweb-grid caliweb-one-grid">
                    <p style="margin-top:2%; font-size:14px;">List of OAuth Authorization Providers</p>
                    <div style="width:40%; margin-top:-4%;">
                        <?php

                            if (mysqli_num_rows($accountModulesLookupResult) > 0) {

                                while ($accountModulesLookupRow = mysqli_fetch_assoc($accountModulesLookupResult)) {

                                    $accountModulesName = $accountModulesLookupRow['moduleName'];

                                    if ($accountModulesName == "Cali OAuth") {

                                        include($_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Oauth//index.php");

                                    }

                                }

                            }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>
