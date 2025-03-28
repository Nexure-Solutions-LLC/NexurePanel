<?php
    $pagetitle = "Settings";
    $pagesubtitle = "System Setup";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/tables/settingsTables/index.php');

    echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';
?>

<section class="section first-dashboard-area-cards">
    <div class="container width-98">
        <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
            <div class="caliweb-settings-sidebar">
                <div class="caliweb-card dashboard-card sidebar-card">
                    <?php  include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Sidebars/Settings/index.php'); ?>
                </div>
            </div>
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <div class="settings-header settings-header-compact">
                        <div class="display-flex align-center mobile-header-compact">
                            <div class="no-padding margin-10px-right icon-size-formatted">
                                <img src="/assets/img/systemIcons/settingsicon.png" alt="Settings Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                            </div>
                            <div>
                                <p class="no-padding font-14px" style="padding-bottom:4px;">Settings</p>
                                <h4 class="text-bold font-size-16 no-padding display-flex align-center">
                                    IP Banning
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="caliweb-card dashboard-card" style="overflow-y:scroll; height:75vh;">
                        <div>
                            <div>
                                <div class="display-flex align-center" style="justify-content:space-between;">
                                    <div>
                                        <h3 style="font-size:18px; margin-top:10px; margin-bottom:4%;">Whitelisted IP Addresses</h3>
                                    </div>
                                    <div>
                                        <a href="/dashboard/administration/settings/ipBaning/addIPAddress/" class="caliweb-button primary">List IPs</a>
                                        <a href="/dashboard/administration/settings/ipBaning/removeIPAddress/" class="caliweb-button secondary">Remove Listed IP</a>
                                    </div>
                                </div>
                                <br>
                                <div class="dashboard-table" style="margin-top:4%;">
                                    <?php

                                    settingsManageListingTable(
                                        $con,
                                        "SELECT * FROM nexure_networks WHERE listType = '" . strtolower('whitelist') . "'",
                                        ['IP Address', 'Type'],
                                        ['ipAddress', 'listType'],
                                        ['50%', '50%']
                                    );

                                    ?>
                                </div>
                            </div>
                            <br>
                            <div class="horizantal-line"></div>
                            <br>
                            <div>
                                <div class="display-flex align-center" style="justify-content:space-between;">
                                    <div>
                                        <h3 style="font-size:18px; padding-top:4%; margin-bottom:4%;">Blacklisted IP Addresses</h3>
                                    </div>
                                </div>
                                <br>
                                <div class="dashboard-table" style="margin-top:4%;">
                                    <?php

                                    settingsManageListingTable(
                                        $con,
                                        "SELECT * FROM nexure_networks WHERE listType = '" . strtolower('blacklist') . "'",
                                        ['IP Address', 'Type'],
                                        ['ipAddress', 'listType'],
                                        ['50%', '50%']
                                    );

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<?php

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>