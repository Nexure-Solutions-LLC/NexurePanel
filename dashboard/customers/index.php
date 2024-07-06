<?php
    $pagetitle = "Client";
    $pagesubtitle = "Overview";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    if ($userrole == "Authorized User" || $userrole == "authorized user") {
        header("location:/dashboard/customers/authorizedUserView");
    } else if ($userrole == "Partner" || $userrole == "partner") {
        header("location:/dashboard/partnerships");
    } else if ($userrole == "Administrator" || $userrole == "administrator") {
        header("location:/dashboard/administration");
    }

    $businessAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_businesses WHERE email = '".$caliemail."'");
    $businessAccountInfo = mysqli_fetch_array($businessAccountQuery);
    mysqli_free_result($businessAccountQuery);

    $businessname = ($businessAccountInfo !== null) ? $businessAccountInfo['businessName'] : null;

    $accountnumber = $userinfo['accountNumber'];
    $customerStatus = $userinfo['accountStatus'];

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';
?>

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <h4 class="text-bold font-size-20 no-padding"><span id="greetingMessage"></span>, <?php echo $fullname; ?></h4>
                </div>
            </section>

            <section class="section caliweb-section customer-dashboard-section">
                <div class="container caliweb-container">
                    <div class="caliweb-two-grid" style="grid-template-columns: 1.2fr .7fr; grid-column-gap: 20px !important;">
                        <div class="accounts-overview">
                            <div class="caliweb-card dashboard-card" style="padding:0;">
                                <div class="card-header no-padding no-margin customer-card-header" style="padding:20px;">
                                    <h6 class="no-padding" style="font-size:16px; font-weight:600;">
                                        <?php echo $orgShortName.' '.$LANG_CUSTOMER_HOME_ACCOUNTS_TITLE; ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="no-padding customer-business-card-bar" style="font-size:14px; font-weight:600;">
                                        <?php
                                            if ($businessname !== null) {
                                                echo strtoupper($businessname);
                                            } else {
                                                echo strtoupper($fullname);
                                            }
                                        ?>
                                    </h6>
                                    <div class="display-flex align-center no-padding no-margin customer-account-title" style="padding:20px; justify-content:space-between;">
                                        <h6 class="no-padding no-margin" style="font-size:16px; font-weight:600;">
                                            <?php echo $orgShortName; ?> Standard (<?php echo $accountnumber; ?>)
                                        </h6>
                                        <span>
                                            <a href="/dashboard/customers/viewAccount/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary">View Account</a>
                                        </span>
                                    </div>
                                    <div class="caliweb-three-grid" style="padding:20px;">
                                        <div class="customer-balance">
                                            <h5 style="font-weight:600; font-size:40px;" class="no-padding no-margin">$0.00</h5>
                                            <p style="font-size:12px; padding-top:5px;" class="no-padding no-margin">Owed Balance</p>
                                        </div>
                                        <div class="customer-duedate" style="padding-top:7%">
                                            <h5 style="font-weight:700; font-size:18px;" class="no-padding no-margin">July 12, 2024</h5>
                                            <p style="font-size:12px; padding-top:5px;" class="no-padding no-margin">Due Date</p>
                                        </div>
                                        <div class="customer-duedate" style="padding-top:5.5%">
                                            <h5 style="font-weight:700; font-size:18px;" class="no-padding no-margin">
                                                <?php
                                                    if ($customerStatus == "Active" || $customerStatus == "active") {
                                                        echo "<span class='account-status-badge green' style='margin-left:0;'>Active</span>";
                                                    } else if ($customerStatus == "Suspended" || $customerStatus == "suspended") {
                                                       echo "<span class='account-status-badge red' style='margin-left:0;'>Suspended</span>";
                                                    } else if ($customerStatus == "Terminated" || $customerStatus == "terminated") {
                                                       echo "<span class='account-status-badge red-dark' style='margin-left:0;'>Terminated</span>";
                                                    } else if ($customerStatus == "Under Review" || $customerStatus == "under review") {
                                                       echo "<span class='account-status-badge yellow' style='margin-left:0;'>Under Review</span>";
                                                    } else if ($customerStatus == "Closed" || $customerStatus == "closed") {
                                                       echo "<span class='account-status-badge passive' style='margin-left:0;'>Closed</span>";
                                                    }
                                                ?>
                                            </h5>
                                            <p style="font-size:12px; padding-top:10px;" class="no-padding no-margin">Account Standing</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="quick-actions">
                            <div class="caliweb-card dashboard-card" style="padding-bottom:30px;">
                                <div class="card-body">
                                    <h4 class="text-bold no-padding" style="font-size:16px;"><?php echo $LANG_QUICKACTIONS_TITLE; ?></h4>
                                    <div class="caliweb-three-grid mt-5-per customer-grid-quick-actions">
                                        <?php 
                                            $moduleResult = mysqli_query($con, "SELECT * FROM caliweb_modules WHERE `moduleFreindlyName` = 'Websites'");
                                            $moduleInfo = mysqli_fetch_array($moduleResult);
                                            mysqli_free_result($moduleResult);

                                            $webDesignNameModuleCheck = $moduleInfo['moduleName'];
                                            $webDesignStatusModuleCheck = $moduleInfo['moduleStatus'];
                                            $webDesignPathModule= $moduleInfo['modulePath'];

                                            if ($webDesignNameModuleCheck == "Cali Websites" && $webDesignStatusModuleCheck == "Active") {

                                                echo ' 
                                                    <a href="'.$webDesignPathModule.'" style="text-decoration:none;">
                                                        <div>
                                                            <img class="customer-quick-actions-img" src="/assets/img/systemIcons/website-builder.png" />
                                                            <p class="text-bold no-padding no-margin font-14px">'.$LANG_EDIT_WEBSITES_TILE.'</p>
                                                            <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;">'.$LANG_EDIT_WEBSITES_SUBTEXT.'</p>
                                                        </div>
                                                    </a>
                                                ';

                                            } else {
                                                    echo '';
                                            }
                                        ?>
                                        <div>
                                            <img class="customer-quick-actions-img" src="/assets/img/systemIcons/page-speed.png" />
                                            <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_RUN_SPEEDTEST_TILE; ?></p>
                                            <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_RUN_SPEEDTEST_SUBTEXT; ?></p>
                                        </div>
                                        <div>
                                            <img class="customer-quick-actions-img" src="/assets/img/systemIcons/synchronize.png" />
                                            <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_BACKUPS_TILE; ?></p>
                                            <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_BACKUPS_SUBTEXT; ?></p>
                                        </div>
                                        <div>
                                            <img class="customer-quick-actions-img" src="/assets/img/systemIcons/log-file.png" />
                                            <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_LOG_FILES_TILE; ?></p>
                                            <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_LOG_FILES_SUBTEXT; ?></p>
                                        </div>
                                        <div>
                                            <img class="customer-quick-actions-img" src="/assets/img/systemIcons/integrity.png" />
                                            <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_CODE_INTEGRITY_TILE; ?></p>
                                            <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $orgShortName; ?> <?php echo $LANG_CODE_INTEGRITY_SUBTEXT; ?></p>
                                        </div>
                                        <div>
                                            <img class="customer-quick-actions-img" src="/assets/img/systemIcons/monitoring.png" />
                                            <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_MONITORING_TILE; ?></p>
                                            <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_MONITORING_SUBTEXT; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card" style="margin-top:2%;">
                                <div class="card-body">
                                    <h4 class="text-bold no-padding" style="font-size:16px;"><?php echo $LANG_PREAPPROVED_DASH_TITLE; ?></h4>
                                    <p class="no-margin no-padding" style="padding-top:4%; font-size:12px;"><?php echo $LANG_NO_PREAPPROVED_DASH_TEXT; ?></p>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card" style="margin-top:2%;">
                                <div class="card-body">
                                    <h4 class="text-bold no-padding" style="font-size:16px;"><?php echo $LANG_DOCUMENTATION_SECTION_TITLE; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>