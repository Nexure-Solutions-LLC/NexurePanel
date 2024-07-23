<?php

    $pagetitle = "Account Management";
    $pagesubtitle = 'General';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container caliweb-container">
            <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
                <div class="caliweb-settings-sidebar">
                    <div class="caliweb-card dashboard-card sidebar-card">
                        <aside class="caliweb-sidebar">
                            <ul class="sidebar-list-linked">
                                <li class="sidebar-link">
                                    <a href="/dashboard/accountManagement/" class="sidebar-link-a">Overview</a>
                                </li>
                                <li>
                                    <a id="account-settings-toggle" href="/dashboard/accountManagement/accountSettings/" class="sidebar-link-a drop-menu-item-sidebar" style="margin-bottom: 4px; font-size: 15px; display:flex; justify-content: space-between;">
                                        Account Settings
                                        <i class="lnr lnr-chevron-down arrow" style="padding-top:2px;"></i>
                                    </a>
                                    <ul id="account-settings-menu" class="sub-menu" style="padding:0; list-style:none; display: none;">
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Paperless</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Travel</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Set Nickname</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Integrations</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Show Or Hide Account</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Set Primary Account</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Nickname Account</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Manage Custom Groups</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Manage Linked Accounts</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a id="personal-details-toggle" href="/dashboard/accountManagement/personalDetails/" class="sidebar-link-a drop-menu-item-sidebar" style="margin-bottom: 4px; font-size: 15px; display:flex; justify-content: space-between;">
                                        Your Personal Details
                                        <i class="lnr lnr-chevron-down arrow" style="padding-top:2px;"></i>
                                    </a>
                                </li>
                                <li>
                                    <a id="privacy-security-toggle" href="/dashboard/accountManagement/privacyAndSecurity/" class="sidebar-link-a drop-menu-item-sidebar" style="margin-bottom: 4px; font-size: 15px; display:flex; justify-content: space-between;">
                                        Sign-In Security
                                        <i class="lnr lnr-chevron-down arrow" style="padding-top:2px;"></i>
                                    </a>
                                </li>
                                <li>
                                    <a id="account-settings-toggle" href="/dashboard/accountManagement/accountSettings/" class="sidebar-link-a drop-menu-item-sidebar" style="margin-bottom: 4px; font-size: 15px; display:flex; justify-content: space-between;">
                                        Account Settings
                                        <i class="lnr lnr-chevron-down arrow" style="padding-top:2px;"></i>
                                    </a>
                                    <ul id="account-settings-menu" class="sub-menu" style="padding:0; list-style:none; display: none;">
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Paperless</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Travel</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Set Nickname</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Integrations</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Show Or Hide Account</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Set Primary Account</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Nickname Account</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Manage Custom Groups</a>
                                        </li>
                                        <li class="sidebar-link">
                                            <a href="#" class="sidebar-link-a">Manage Linked Accounts</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a id="personal-details-toggle" href="/dashboard/accountManagement/personalDetails/" class="sidebar-link-a drop-menu-item-sidebar" style="margin-bottom: 4px; font-size: 15px; display:flex; justify-content: space-between;">
                                        Your Personal Details
                                        <i class="lnr lnr-chevron-down arrow" style="padding-top:2px;"></i>
                                    </a>
                                </li>
                                <li>
                                    <a id="privacy-security-toggle" href="/dashboard/accountManagement/privacyAndSecurity/" class="sidebar-link-a drop-menu-item-sidebar" style="margin-bottom: 4px; font-size: 15px; display:flex; justify-content: space-between;">
                                        Sign-In Security
                                        <i class="lnr lnr-chevron-down arrow" style="padding-top:2px;"></i>
                                    </a>
                                </li>
                            </ul>
                        </aside>
                    </div>
                </div>
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <div class="caliweb-card dashboard-card">
                        <div class="caliweb-two-grid special-caliweb-spacing" style="height:0vh;">
                            <div class="caliweb-card dashboard-card account-center-cards">
                                <div class="card-body">
                                    <h4 class="text-bold font-size-20 no-padding"><?php echo $LANG_ACCOUNTCENTER_CARD_ACCOUNTSETTINGS_TITLE; ?></h4>
                                    <p class="font-12px" style="padding-top:20px;"><?php echo $LANG_ACCOUNTCENTER_CARD_ACCOUNTSETTINGS_TEXT; ?></p</div>
                                </div>
                                <div style="margin-top:20px;">
                                    <a href="/dashboard/accountManagement/accountSettings/" class="careers-link" style="text-decoration:none;"><span class="display-flex align-center"><?php echo $LANG_ACCOUNTCENTER_CARD_ACCOUNTSETTINGS_LINKTEXT; ?> <span class="lnr lnr-chevron-right" style="margin-left:10px;"></span></span></a>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card account-center-cards">
                                <div class="card-body">
                                    <h4 class="text-bold font-size-20 no-padding"><?php echo $LANG_ACCOUNTCENTER_CARD_PERSONALDETAILS_TITLE; ?></h4>
                                    <p class="font-12px" style="padding-top:20px;"><?php echo $LANG_ACCOUNTCENTER_CARD_PERSONALDETAILS_TEXT; ?></p</div>
                                </div>
                                <div style="margin-top:20px;">
                                    <a href="/dashboard/accountManagement/personalDetails/" class="careers-link" style="text-decoration:none;"><span class="display-flex align-center"><?php echo $LANG_ACCOUNTCENTER_CARD_PERSONALDETAILS_LINKTEXT; ?> <span class="lnr lnr-chevron-right" style="margin-left:10px;"></span></span></a>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card account-center-cards">
                                <div class="card-body">
                                    <h4 class="text-bold font-size-20 no-padding"><?php echo $LANG_ACCOUNTCENTER_CARD_PRIVACYANDSECURITY_TITLE; ?></h4>
                                    <p class="font-12px" style="padding-top:20px;"><?php echo $LANG_ACCOUNTCENTER_CARD_PRIVACYANDSECURITY_TEXT; ?></p</div>
                                </div>
                                <div style="margin-top:20px;">
                                    <a href="/dashboard/accountManagement/privacyAndSecurity/" class="careers-link" style="text-decoration:none;"><span class="display-flex align-center"><?php echo $LANG_ACCOUNTCENTER_CARD_PRIVACYANDSECURITY_LINKTEXT; ?> <span class="lnr lnr-chevron-right" style="margin-left:10px;"></span></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>