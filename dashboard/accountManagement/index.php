<?php

    $pagetitle = "Account Management";
    $pagesubtitle = 'General';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

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
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <div class="caliweb-card dashboard-card">
                        <h4 class="text-bold font-size-20 no-padding" style="margin-top:1%;">Overview</h4>
                        <div class="caliweb-two-grid special-caliweb-spacing" style="height:0vh; margin-top:2%;">
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

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>