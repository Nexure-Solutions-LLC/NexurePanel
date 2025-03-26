<?php

    $pagetitle = "Account Management";
    $pagesubtitle = 'General';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/tables/settingsTables/index.php');

    $accountModulesLookupQuery = "SELECT * FROM nexure_modules WHERE moduleStatus = 'Active' AND modulePositionType = 'Authentication'";
    $accountModulesLookupResult = mysqli_query($con, $accountModulesLookupQuery);

    echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';

?>

<section class="section first-dashboard-area-cards" style="padding-top:1%;">
    <div class="container width-98">
        <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
            <div class="caliweb-card dashboard-card sidebar-card">
                <aside class="caliweb-sidebar">
                    <ul class="sidebar-list-linked">
                        <li class="sidebar-link active"><a href="/dashboard/accountManagement" class="sidebar-link-a">General</a></li>
                        <li class="sidebar-link"><a href="/dashboard/accountManagement/integrations" class="sidebar-link-a">Integrations</a></li>
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
                            <h3 style="font-size:18px; padding-top:0%; margin-bottom:4%;">Personal Information</h3>
                        </div>
                    </div>
                    <div class="dashboard-table" style="margin-top:4%;">
                        <?php

                            settingsManageListingTable(
                                $con,
                                "SELECT * FROM nexure_users WHERE email = '" . $currentAccount->email . "'",
                                ['Legal Name', 'Email', 'Mobile Number', 'Account Number', 'Role'],
                                ['legalName', 'email', 'mobileNumber', 'accountNumber', 'userrole'],
                                ['20%', '20%', '20%', '20%', '20%']
                            );

                        ?>
                    </div>
                    <br>
                    <div class="display-flex align-center" style="justify-content:space-between;">
                        <div>
                            <h3 style="font-size:18px; padding-top:4%; margin-bottom:4%;">Address Information</h3>
                        </div>
                    </div>
                    <div class="dashboard-table" style="margin-top:4%;">
                        <?php

                            settingsManageListingTable(
                                $con,
                                "SELECT * FROM nexure_ownershipinformation WHERE emailAddress = '" . $currentAccount->email . "'",
                                ['Address Line 1', 'Address Line 2', 'City', 'State', 'Country', 'Postal Code'],
                                ['addressline1', 'addressline2', 'city', 'state', 'country', 'postalcode'],
                                ['40%', '20%', '10%', '10%', '10%']
                            );

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