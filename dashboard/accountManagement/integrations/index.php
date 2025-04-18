<?php

    $pagetitle = "Account Management";
    $pagesubtitle = 'General';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/tables/settingsTables/index.php');

    $accountModulesLookupQuery = "SELECT * FROM nexure_modules WHERE moduleStatus = 'Active' AND modulePositionType = 'Authentication'";
    $accountModulesLookupResult = mysqli_query($con, $accountModulesLookupQuery);


    unset($_SESSION['pagetitle']);
    $_SESSION['pagetitle'] = $pagetitle;

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
                        <li class="sidebar-link"><a href="/dashboard/accountManagement/nexureaccounts" class="sidebar-link-a">Nexure Account Settings</a></li>
                        <li class="sidebar-link"><a href="/dashboard/accountManagement/advanced" class="sidebar-link-a">Advanced Settings</a></li>
                    </ul>
                </aside>
            </div>
            <div class="caliweb-one-grid special-caliweb-spacing">
                <?php include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Account/Management/Headers/index.php'); ?>
                <div class="caliweb-card dashboard-card" style="overflow-y:scroll; height:75vh;">
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

                                    if ($accountModulesName == "Nexure OAuth") {

                                        include($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Oauth/index.php");

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

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>