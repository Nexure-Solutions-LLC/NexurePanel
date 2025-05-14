<?php

    $PageTitle = "Customer Dashboard";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");

    
    if ($account) {
        $accountName = strtoupper($account['headerName']);
        $lastFour = substr($account['accountNumber'], -4);
    } else {
        $accountName = "NO ACCOUNT";
        $lastFour = "----";
    }

?>

    <title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | <?php echo $PageTitle; ?></title>


    <section class="section dashboard">
        <div class="container nexure-container">
            <div class="nexure-grid nexure-one-grid no-row-gap">
                <div>
                    <h4 class="text-bold font-24px no-padding margin-top-10px margin-bottom-50px"><span id="greetingMessage"></span>, <?php echo $VariableDefinitionHandler->displayName; ?></h4>
                </div>
            </div>
            <div class="nexure-grid nexure-two-grid no-row-gap offset-grid">
                <?php if ($account): ?>
                    <?php
                        $mostRecentAccount = $VariableDefinitionHandler->userAccounts[0] ?? null;
                        $headerName = $mostRecentAccount['headerName'] ?? $VariableDefinitionHandler->organizationShortName.' ACCOUNT';
                    ?>
                    <div class="nexure-card">
                        <p class="margin-bottom-20px"><strong><?php echo $VariableDefinitionHandler->organizationShortName; ?> accounts</strong></p>

                        <div class="background-grey-100 margin-bottom-10px">
                            <p class="font-12px text-uppercase text-bold"><?= htmlspecialchars($headerName) ?></p>
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
                                    <?php foreach ($VariableDefinitionHandler->userAccounts as $account): ?>
                                        <tr>
                                            <td class="width-50"><?= htmlspecialchars($account['accountDisplayName']) ?> (...<?= substr($account['accountNumber'], -4) ?>)</td>
                                            <td class="width-20">$<?= number_format((float)$account['balance'], 2) ?></td>
                                            <td class="width-20"><?= $account['dueDate'] ? date('F j, Y', strtotime($account['dueDate'])) : '—' ?></td>
                                            <td class="width-10">
                                                <span class="account-status-badge <?= strtolower($account['accountStatus']) === 'open' ? 'green' : 'red' ?>">
                                                    <?= htmlspecialchars($account['accountStatus']) ?>
                                                </span>
                                            </td>
                                            <td class="width-20">
                                                <a href="/Dashboard/Customer/ViewAccount?account_number=<?= urlencode($account['accountNumber']) ?>" class="nexure-button primary" style="padding:4px 24px;">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
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
                <?php endif; ?>
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