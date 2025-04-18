<?php

    $pagetitle = "Client";
    $pagesubtitle = "Overview";
    $pagetype = "Client";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

        if ($variableDefinitionX->paymentProcessorName == "Stripe") {

            include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

        } else {

            header ("location: /error/genericSystemError");

        }

    } else {

        echo 'There are no payment modules available to service this request.';

    }

    function getServiceType($con, $accountNumber) {
        
        try {

            $query = "SELECT serviceName FROM nexure_services WHERE accountNumber = '$accountNumber'";

            $result = $con->query($query);
            
            if (!$result) {

                return '';

            }
    
            $row = $result->fetch_assoc();

            if (!$row) {

                return '';

            }
    
            $service = $row['serviceName'];
    
            $words = explode(' ', trim($service));
    
            if (count($words) > 1) {

                array_shift($words);

            }
    
            $serviceType = implode(' ', $words);
    
            return htmlspecialchars($serviceType);
    
        } catch (\Exception $e) {

            return '';

        }

    }

    try {

        $accountNumbers = $currentAccount->accountNumber;

        $accountNumberArray = explode(',', $accountNumbers);

        $customerStatus = $currentAccount->accountStatus;

        $manageAccountDefinitionR = new \NexureSolutions\Generic\VariableDefinitions();

        $manageAccountDefinitionR->manageAccount($con, $accountNumbers);

        $businessname = ($manageAccountDefinitionR->businessname !== null) ? $manageAccountDefinitionR->businessname : null;

        $serviceType = getServiceType($con, $accountNumbers);

        echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';
    
?>

        <section class="first-dashboard-area-cards">
            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <h4 class="text-bold font-size-20 no-padding"><span id="greetingMessage"></span>, <?php echo $currentAccount->legalName; ?></h4>
                </div>
            </section>

            <section class="section caliweb-section customer-dashboard-section">
                <div class="container caliweb-container">
                    <div class="caliweb-two-grid" style="grid-template-columns: 1.2fr .7fr; grid-column-gap: 20px !important;">
                        <div class="accounts-overview">
                            <div class="caliweb-card dashboard-card" style="padding:0;">
                                <div class="card-header no-padding no-margin customer-card-header" style="padding:20px;">
                                    <h6 class="no-padding" style="font-size:16px; font-weight:600; font-family: 'IBM Plex Sans', sans-serif;">
                                        <?php echo $variableDefinitionX->orgShortName.' '.$LANG_CUSTOMER_HOME_ACCOUNTS_TITLE; ?>
                                    </h6>
                                </div>
                                <div class="card-body">

                                    <h6 class="no-padding customer-business-card-bar" style="font-size:14px; font-weight:600; font-family: 'IBM Plex Sans', sans-serif;">
                                        <?php
                                            if ($businessname !== null) {

                                                echo strtoupper($businessname);

                                            } else {

                                                echo strtoupper($currentAccount->legalName);
                                                
                                            }
                                        ?>
                                    </h6>

                                    <?php

                                        foreach ($accountNumberArray as $accountNumber) {

                                            $accountNumber = trim($accountNumber);

                                            $truncatedAccountNumber = substr($accountNumber, -4);

                                            $displayedBusinessName = $businessname !== null ? strtoupper($businessname) : strtoupper($currentAccount->legalName);

                                            if (strtolower($currentAccount->accountStatus->name) == "restricted") {

                                                $balance = '——';

                                                $dueDate = '——';

                                                $statusMessage = '

                                                    <div class="account-status-banner" id="accountRestricted">
                                                        <p style="font-size:14px;">We have restricted this account and reopened it to protect your services. If you have any questions, please contact us.</p>
                                                    </div>

                                                ';

                                            } else {

                                                $stripeID = $currentAccount->stripe_id;

                                                $balance = '$'.getCreditBalance($stripeID);

                                                $dueDate = getSubscriptionDueDate($stripeID);

                                                $statusMessage = '';

                                            }

                                            echo '
                            
                                                <div class="display-flex align-center no-padding no-margin customer-account-title" style="padding:20px; justify-content:space-between;">
                                                    <h6 class="no-padding no-margin" style="font-size:16px; font-weight:600; font-family: \'IBM Plex Sans\', sans-serif;">' . $variableDefinitionX->orgShortName . ' ' . $serviceType . ' (...' . $truncatedAccountNumber . ')</h6>
                                                    <div class="caliweb-button-section">   
                                                        <a href="/dashboard/customers/viewAccount/?account_number=' . urlencode($accountNumber) . '" class="caliweb-button secondary">View Account</a>
                                                    </div>
                                                </div>
                                                ' . $statusMessage . '
                                                <div class="caliweb-three-grid" style="padding:20px;">
                                                    <div class="customer-balance">
                                                        <h5 style="font-weight:600; font-size:40px;" class="no-padding no-margin">' . $balance . '</h5>
                                                        <p style="font-size:12px; padding-top:5px;" class="no-padding no-margin">Owed Balance</p>
                                                    </div>
                                                    <div class="customer-duedate" style="padding-top:7%">
                                                        <h5 style="font-weight:700; font-size:18px;" class="no-padding no-margin">' . $dueDate . '</h5>
                                                        <p style="font-size:12px; padding-top:5px;" class="no-padding no-margin">Due Date</p>
                                                    </div>
                                                    <div class="customer-duedate" style="padding-top:5.5%">
                                                        <h5 style="font-weight:700; font-size:18px;" class="no-padding no-margin">
                                                            <span class="account-status-badge ' . $currentAccount->transformStringToStatusColor($currentAccount->fromAccountStatus($customerStatus))->value . '" style="margin-left:0;">' . $currentAccount->fromAccountStatus($customerStatus) . '</span>
                                                        </h5>
                                                        <p style="font-size:12px; padding-top:10px;" class="no-padding no-margin">Account Standing</p>
                                                    </div>
                                                </div>

                                            ';

                                        }

                                    ?>

                                </div>
                            </div>
                        </div>
                        <div class="quick-actions">
                            <div class="caliweb-card dashboard-card" style="padding-bottom:30px;">
                                <div class="card-body">
                                    <h4 class="text-bold no-padding" style="font-size:16px;"><?php echo $LANG_QUICKACTIONS_TITLE; ?></h4>
                                    <div class="caliweb-three-grid mt-5-per customer-grid-quick-actions">
                                        <?php 

                                            $moduleResult = mysqli_query($con, "SELECT * FROM nexure_modules WHERE `moduleFriendlyName` = 'Websites'");
                                            $moduleInfo = mysqli_fetch_array($moduleResult);
                                            mysqli_free_result($moduleResult);

                                            $webDesignNameModuleCheck = $moduleInfo['moduleName'];
                                            $webDesignStatusModuleCheck = $moduleInfo['moduleStatus'];
                                            $webDesignPathModule= $moduleInfo['modulePath'];

                                            if ($webDesignNameModuleCheck == "Nexure Websites" && $webDesignStatusModuleCheck == "Active") {

                                                echo ' 
                                                    <a href="'.$webDesignPathModule.'" class="dark-mode-white" style="text-decoration:none;">
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
                                        <?php

                                            $codemoduleResult = mysqli_query($con, "SELECT * FROM nexure_modules WHERE `moduleFriendlyName` = 'Security'");

                                            $codemoduleInfo = mysqli_fetch_array($codemoduleResult);
                                            
                                            mysqli_free_result($codemoduleResult);

                                            $codeIntNameModuleCheck = $codemoduleInfo['moduleName'];

                                            $codeIntStatusModuleCheck = $codemoduleInfo['moduleStatus'];

                                            $codeIntPathModule= $codemoduleInfo['modulePath'];

                                            if ($codeIntNameModuleCheck == "Nexure Code Integrity" && $codeIntStatusModuleCheck == "Active") {

                                                echo '
                                                    <a href="'.$codeIntPathModule.'" class="dark-mode-white" style="text-decoration:none;">
                                                        <div>
                                                            <img class="customer-quick-actions-img" src="/assets/img/systemIcons/integrity.png" />
                                                            <p class="text-bold no-padding no-margin font-14px">'.$LANG_CODE_INTEGRITY_TILE.'</p>
                                                            <p class="no-padding no-margin" style="padding-top:6%; font-size:12px;">'.$variableDefinitionX->orgShortName.' '.$LANG_CODE_INTEGRITY_SUBTEXT.'</p>
                                                        </div>
                                                    </a>
                                                ';

                                            } else {

                                                echo '';

                                            }

                                        ?>
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

        include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');
    
    } catch (\Throwable $exception) {
            
        \Sentry\captureException($exception);
        
    }

?>