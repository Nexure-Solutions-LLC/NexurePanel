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

    $riskScore = $CurrentOnlineAccessAccount->NexureRiskScore10;

    $category = RiskScoreCategory::fromScore($riskScore);

?>

    <title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | <?php echo $PageTitle; ?></title>


    <section class="section dashboard">
        <div class="container nexure-container">
            <div class="nexure-grid nexure-one-grid no-row-gap">
                <div>
                    <h4 class="text-bold font-24px no-padding margin-top-10px margin-bottom-50px greeting-mobile"><span id="greetingMessage"></span>, <?php echo $CurrentOnlineAccessAccount->displayName; ?></h4>
                </div>
            </div>
            <div class="nexure-grid nexure-two-grid no-row-gap offset-grid">
                <?php if ($account): ?>
                    <?php
                        $mostRecentAccount = $CurrentOnlineAccessAccount->userAccounts[0] ?? null;
                        $headerName = $mostRecentAccount['headerName'] ?? $VariableDefinitionHandler->organizationShortName.' ACCOUNT';
                    ?>
                    <div class="nexure-card">
                        <p class="margin-bottom-20px primary-font"><strong><?php echo $VariableDefinitionHandler->organizationShortName; ?> accounts</strong></p>

                        <div class="background-grey-100 margin-bottom-10px">
                            <p class="font-12px text-uppercase text-bold"><?= htmlspecialchars($headerName) ?></p>
                        </div>

                         <?php if (strtolower($account['accountStatus']) === 'restricted'): ?>
                            <div class="restricted-notice margin-bottom-10px">
                                <p class="font-12px">We have restricted this account and reopened it to protect your service. If you have any questions, please contact us.</p>
                            </div>
                        <?php endif; ?>

                        <div class="nexure-table-container">
                            <table class="nexure-table-plugin nexure-table-domains">
                                <thead>
                                    <tr>
                                        <th>Account</th>
                                        <th>Type</th>
                                        <th>Balance</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($CurrentOnlineAccessAccount->userAccounts as $account): ?>
                                        <?php if (strtolower($account['accountStatus']) === 'closed') continue; ?>
                                        <?php if (strtolower($account['accountStatus']) === 'rejected') continue; ?>
                                        <tr>
                                            <td class="width-30"><?= htmlspecialchars($account['accountDisplayName']) ?> (...<?= substr($account['accountNumber'], -4) ?>)</td>
                                            <td class="width-20">
                                                <?= htmlspecialchars($account['accountType']) ?>
                                            </td>
                                            <td class="width-20">
                                                <?= strtolower($account['accountStatus']) === 'restricted' ? '— —' : '$' . $account['balance'] ?>
                                            </td>
                                            <td class="width-20">
                                                <?= strtolower($account['accountStatus']) === 'restricted' ? '— —' : ($account['dueDate'] ? date('F j, Y', strtotime($account['dueDate'])) : '—') ?>
                                            </td>
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
                    <?php if ($account): ?>
                        <div class="nexure-card">
                            <p class="no-padding primary-font"><strong><?php echo $LANG_QUICKACTIONS_TITLE; ?></strong></p>
                            <div class="nexure-grid nexure-three-grid margin-top-30px gap-row-spacing-30 margin-bottom-10px">
                                <?php if ($NexureModuleHandler->isModuleEnabled(82)): ?>
                                    <div>
                                        <a href="/Dashboard/Customer/QuickActions/SpeedTest/" class="quick-actions-link">
                                            <img class="customer-quick-actions-img" src="/Assets/img/SystemImages/Icons/page-speed.png" />
                                            <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_RUN_SPEEDTEST_TILE; ?></p>
                                            <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_RUN_SPEEDTEST_SUBTEXT; ?></p>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <?php if ($NexureModuleHandler->isModuleEnabled(82)): ?>
                                    <div>
                                        <a href="/Modules/NexureSolutions/Hosting/ManageHosting/Backups" class="quick-actions-link">
                                            <img class="customer-quick-actions-img" src="/Assets/img/SystemImages/Icons/synchronize.png" />
                                            <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_BACKUPS_TILE; ?></p>
                                            <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_BACKUPS_SUBTEXT; ?></p>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <a href="/Dashboard/Customer/QuickActions/Logs/" class="quick-actions-link">
                                        <img class="customer-quick-actions-img" src="/Assets/img/SystemImages/Icons/log-file.png" />
                                        <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_LOG_FILES_TILE; ?></p>
                                        <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_LOG_FILES_SUBTEXT; ?></p>
                                    </a>
                                </div>
                                <?php if ($NexureModuleHandler->isModuleEnabled(82)): ?>
                                    <div>
                                        <a href="/Modules/NexureSolutions/Development/CodeIntegrity" class="quick-actions-link">
                                            <img class="customer-quick-actions-img" src="/Assets/img/SystemImages/Icons/integrity.png" />
                                            <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_CODE_INTEGRITY_TILE; ?></p>
                                            <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;">Nexure <?php echo $LANG_CODE_INTEGRITY_SUBTEXT; ?></p>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <a href="/Dashboard/Customer/QuickActions/Monitoring/" class="quick-actions-link">
                                        <img class="customer-quick-actions-img" src="/Assets/img/SystemImages/Icons/monitoring.png" />
                                        <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_MONITORING_TILE; ?></p>
                                        <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_MONITORING_SUBTEXT; ?></p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="nexure-card">
                            <p class="no-padding primary-font"><strong><?php echo $LANG_QUICKACTIONS_TITLE; ?></strong></p>
                            <div class="nexure-grid nexure-three-grid margin-top-30px gap-row-spacing-30 margin-bottom-10px">
                                <div>
                                    <a href="/Onboarding/" class="quick-actions-link">
                                        <img class="customer-quick-actions-img" src="/Assets/img/SystemImages/Icons/open-account.png" />
                                        <p class="text-bold no-padding no-margin font-14px"><?php echo $LANG_OPENNEWACCOUNT_TILE; ?></p>
                                        <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;"><?php echo $LANG_OPENNEWACCOUNT_SUBTEXT; ?></p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($NexureModuleHandler->isModuleEnabled(75) && $CurrentOnlineAccessAccount->riskScoreMonitoring == 'True'): ?>
                        <div class="nexure-card margin-top-20px">
                            <p class="primary-font"><strong><?php echo $VariableDefinitionHandler->organizationShortName; ?> Risk Score 1.0®</strong></p>
                            <div class="score-container">
                                <div class="score-value display-flex align-center padding-bottom-20px"><?php echo isset($riskScore) ? (string)$riskScore : '——'; ?> <?php echo '<div class="score-label ' . $category->colorClass() . '">' . $category->label() . '</div>'; ?></div>
                                <div class="score-bar">
                                    <div class="score-indicator" id="score-indicator"></div>
                                </div>
                                <div class="score-range">
                                    <span>0</span>
                                    <span>299</span>
                                    <span>499</span>
                                    <span>699</span>
                                    <span>849</span>
                                    <span>999</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="nexure-card margin-top-20px">
                        <p class="primary-font"><strong>Plan for your next business</strong></p>
                        <p class="margin-top-20px">You have no pre-approved account offers.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        window.nexureRiskScore = <?php echo isset($riskScore) ? (int)$riskScore : 0; ?>;
    </script>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>