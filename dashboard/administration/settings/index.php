<?php
    $pagetitle = "Settings";
    $pagesubtitle = "System Setup";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/tables/settingsTables/index.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';
?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
                <div class="caliweb-settings-sidebar">
                    <div class="caliweb-card dashboard-card sidebar-card">
                        <aside class="caliweb-sidebar">
                            <ul class="sidebar-list-linked">
                                <a href="/dashboard/administration/settings/" class="sidebar-link-a"><li class="sidebar-link active">General</li></a>
                                <a href="/dashboard/administration/settings/ipBaning" class="sidebar-link-a"><li class="sidebar-link">IP Banning</li></a>
                                <a href="/licensing/" class="sidebar-link-a"><li class="sidebar-link">Licencing</li></a>
                                <a href="/dashboard/administration/settings/update" class="sidebar-link-a"><li class="sidebar-link">Updates</li></a>
                                <a href="/dashboard/administration/settings/about" class="sidebar-link-a"><li class="sidebar-link">About Cali Panel</li></a>
                            </ul>
                        </aside>
                    </div>
                </div>
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <div class="caliweb-card dashboard-card">
                        <div>
                            <h3 style="font-size:18px; margin-top:10px; margin-bottom:4%;">Company Information</h3>
                             <div class="dashboard-table">
                                <?php

                                    settingsManageListingTable(
                                        $con,
                                        "SELECT * FROM caliweb_panelconfig WHERE id = 1",
                                        ['DBA Name', 'Company Legal Name', 'Address', 'City', 'State', 'Postal Code', 'Country', 'Payment Descriptor'],
                                        ['organizationShortName', 'organization', 'organizationAddress', 'organizationCity', 'organizationState', 'organizationZipcode', 'organizationCountry', 'paymentDescriptor'],
                                        ['10%', '17%', '20%', '10%', '10%', '8%', '12%', '15%']
                                    );

                                ?>
                            </div>
                            <br>
                                <div class="horizantal-line"></div>
                            <br>
                            <h3 style="font-size:18px; margin-top:10px; margin-top:1%; margin-bottom:4%;">Primary Contact Information</h3>
                             <div class="dashboard-table">
                                <?php

                                    settingsManageListingTable(
                                        $con,
                                        "SELECT * FROM caliweb_users WHERE id = 1",
                                        ['Legal Name', 'Phone Number', 'Email', 'Role', 'Access Level', 'Account Status', 'Email Verification', 'Setup Date'],
                                        ['legalName', 'mobileNumber', 'email', 'userrole', 'employeeAccessLevel', 'accountStatus', 'emailVerfied', 'registrationDate'],
                                        ['10%', '12%', '23%', '10%', '10%', '10%', '12%', '15%']
                                    );

                                ?>
                            </div>
                        </div>
                        <div>
                            <h3 style="font-size:18px; margin-top:4%; margin-bottom:4%;">System Options</h3>
                            <form method="POST">
                                <div class="caliweb-grid caliweb-three-grid" style="grid-row-gap:0; margin:0; padding:0;">
                                    <div>
                                        <div class="form-control" style="margin-top:-2%;">
                                            <label for="panelTheme">Panel Theme</label>
                                            <br>
                                            <select class="form-input" style="width:100%;" name="panelTheme" id="panelTheme">
                                                <option>Cali Default</option>
                                                <option>Cali Classic</option>
                                                <option>Cali Purple</option>
                                                <option>Cali Green</option>
                                                <option>Cali Orange</option>
                                                <option>Cali Red</option>
                                            </select>
                                        </div>
                                        <div class="form-control" style="margin-top:6%;">
                                            <label for="panelMaintenanceMode">Maintenance Mode</label>
                                            <br>
                                            <select class="form-input" style="width:100%;" name="panelMaintenanceMode" id="panelMaintenanceMode">
                                                <option>False (Default)</option>
                                                <option>True</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-control">
                                            <div class="form-control" style="margin-top:-2%;">
                                                <label for="paymentDescriptor">Payment Descriptor</label>
                                                <br>
                                                <input type="text" class="form-input" style="width:100%;" name="paymentDescriptor" id="paymentDescriptor" placeholder="LITTLEINTERNETWIDGETS" />
                                            </div>
                                            <div class="form-control" style="margin-top:6%;">
                                                <label for="registrationMode">Registration Enabled</label>
                                                <br>
                                                <select class="form-input" style="width:100%;" name="registrationMode" id="registrationMode">
                                                    <option>True (Default)</option>
                                                    <option>False</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                     <div>
                                        <div class="form-control">
                                            <div class="form-control" style="margin-top:-2%;">
                                                <label for="logoWide">Upload Wide Logo (For Dashboard Pages)</label>
                                                <br>
                                                <input type="file" class="form-input" style="width:100%;" name="logoWide" id="logoWide" />
                                            </div>
                                            <div class="form-control" style="margin-top:6%;">
                                                <label for="logoSquare">Upload Square Logo (For Login Pages)</label>
                                                <br>
                                                <input type="file" class="form-input" style="width:100%;" name="logoSquare" id="logoSquare" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>