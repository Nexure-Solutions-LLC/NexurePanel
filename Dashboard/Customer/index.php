<?php

    $PageTitle = "Customer Dashboard";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");

?>

    <title>Nexure Unified Panel | <?php echo $PageTitle; ?></title>


    <section class="section dashboard">
        <div class="container nexure-container">
            <div class="nexure-grid nexure-one-grid no-row-gap">
                <div>
                    <h4 class="text-bold font-24px no-padding margin-top-10px margin-bottom-50px"><span id="greetingMessage"></span>, Undefined</h4>
                </div>
            </div>
            <!-- <div class="nexure-grid nexure-one-grid no-row-gap">
                <div class="nexure-card">
                    <div class="display-flex align-center">
                        <div>
                            <img src="/Assets/img/SystemImages/Icons/warning.webp" alt="InformationIcon" class="informationIcon" />
                        </div>
                        <div class="margin-left-20px">
                            <p><strong>You currently have no accounts</strong></p>
                            <p>This page is a bit empty. Use the "Open an account" link to get started with an account opening.</p>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="nexure-grid nexure-two-grid no-row-gap offset-grid">
                <div class="nexure-card">
                    <p class="margin-bottom-20px"><strong>Nexure accounts</strong></p>

                    <div class="background-grey-100">
                        <p class="font-12px" style="text-transform:uppercase;"><strong>Testing Organization II LTD.</strong></p>
                    </div>

                    <div class="nexure-table-container">
                        <table class="nexure-table-plugin nexure-table-domains">
                            <thead>
                            <tr>
                                <th>Account</th>
                                <th>Balance</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="width-50">Nexure Enterprise Unlimited (... 1234)</td>
                                    <td class="width-20">$14.99</td>
                                    <td class="width-20">May 30 2025</td>
                                    <td class="width-10"><span class="account-status-badge green">Open</span></td>
                                    <td class="width-20"><a href="/Dashboard/Customer/ViewAccount" class="nexure-button primary" style="padding:4px 24px;">View</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <div class="nexure-card">
                        <p class="no-padding"><strong><?php echo $LANG_QUICKACTIONS_TITLE; ?></strong></p>
                        <div class="nexure-grid nexure-three-grid margin-top-30px gap-row-spacing-30 margin-bottom-10px">
                            <div>
                                <img class="customer-quick-actions-img" src="/Assets/img/SystemImages/Icons/page-speed.png" />
                                <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_RUN_SPEEDTEST_TILE; ?></p>
                                <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_RUN_SPEEDTEST_SUBTEXT; ?></p>
                            </div>
                            <div>
                                <img class="customer-quick-actions-img" src="/Assets/img/SystemImages/Icons/synchronize.png" />
                                <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_BACKUPS_TILE; ?></p>
                                <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_BACKUPS_SUBTEXT; ?></p>
                            </div>
                            <div>
                                <img class="customer-quick-actions-img" src="/Assets/img/SystemImages/Icons/log-file.png" />
                                <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_LOG_FILES_TILE; ?></p>
                                <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_LOG_FILES_SUBTEXT; ?></p>
                            </div>
                            <div>
                                <img class="customer-quick-actions-img" src="/Assets/img/SystemImages/Icons/integrity.png" />
                                <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_CODE_INTEGRITY_TILE; ?></p>
                                <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;">Nexure <?php echo $LANG_CODE_INTEGRITY_SUBTEXT; ?></p>
                            </div>
                            <div>
                            <img class="customer-quick-actions-img" src="/Assets/img/SystemImages/Icons/monitoring.png" />
                                <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_MONITORING_TILE; ?></p>
                                <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_MONITORING_SUBTEXT; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="nexure-card margin-top-20px">
                        <p><strong>Plan for your next business</strong></p>
                        <p class="margin-top-10px">You have no pre-approved account offers.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>