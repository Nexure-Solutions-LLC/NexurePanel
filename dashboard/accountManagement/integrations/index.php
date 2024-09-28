<?php

    $pagetitle = "Account Management";
    $pagesubtitle = 'General';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/tables/settingsTables/index.php');

    $accountModulesLookupQuery = "SELECT * FROM caliweb_modules WHERE moduleStatus = 'Active' AND modulePositionType = 'Authentication'";
    $accountModulesLookupResult = mysqli_query($con, $accountModulesLookupQuery);

    echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';

?>

<section class="section first-dashboard-area-cards" style="padding-top:1%;">
    <div class="container width-98">
        <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
            <div class="caliweb-card dashboard-card sidebar-card">
                <aside class="caliweb-sidebar">
                    <ul class="sidebar-list-linked">
                        <li class="sidebar-link"><a href="/dashboard/accountManagement" class="sidebar-link-a">General</a></li>
                        <li class="sidebar-link active"><a href="/dashboard/accountManagement/integrations" class="sidebar-link-a">Integrations</a></li>
                        <li class="sidebar-link"><a href="/dashboard/accountManagement/security" class="sidebar-link-a">Security Settings</a></li>
                        <li class="sidebar-link"><a href="/dashboard/accountManagement/caliaccounts" class="sidebar-link-a">Cali Account Settings</a></li>
                        <li class="sidebar-link"><a href="/dashboard/accountManagement/advanced" class="sidebar-link-a">Advanced Settings</a></li>
                    </ul>
                </aside>
            </div>
            <div>
                <?php include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Account/Management/Headers/index.php'); ?>
                <div class="caliweb-card dashboard-card" style="margin-top:1%;">
                    <div class="display-flex align-center" style="justify-content:space-between;">
                        <div>
                            <h3 style="font-size:18px; padding-top:0%; margin-bottom:4%;">Integrations</h3>
                        </div>
                    </div>
                    <div class="dashboard-table" style="margin-top:2%; width:40%;">
                        <?php

                            if (mysqli_num_rows($accountModulesLookupResult) > 0) {

                                while ($accountModulesLookupRow = mysqli_fetch_assoc($accountModulesLookupResult)) {

                                    $accountModulesName = $accountModulesLookupRow['moduleName'];

                                    if ($accountModulesName == "Cali OAuth") {

                                        include($_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Oauth/index.php");

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

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>