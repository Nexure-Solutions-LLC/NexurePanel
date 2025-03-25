<div class="caliweb-card dashboard-card custom-padding-account-card">
        <div class="card-header-account">
            <div class="display-flex align-center">
                <div class="no-padding margin-10px-right icon-size-formatted">
                <img src="/assets/img/systemIcons/blacklisterservices.png" alt="Blacklister Logo" style="background-color:#fbe7e3;" class="client-business-andor-profile-logo" />
                </div>
                <div>
                    <p class="no-padding font-14px" style="padding-bottom:4px;">Blacklist information on account</p>
                    <h4 class="text-bold font-size-16 no-padding display-flex align-center">
                        <?php echo $manageAccountDefinitionR->businessname; ?> - <?php echo $accountnumber; ?>
                        <?php

                            $statusClasses = [
                                "Active" => "green",
                                "Revoked" => "red",
                                "Under Review" => "yellow",
                                "Closed" => "passive"
                            ];
                            
                            $statusClass = $statusClasses[ucwords(strtolower($blacklisterDefinitionG->blacklistStatus))] ?? 'default';
                            echo "<span class='account-status-badge $statusClass'>{$blacklisterDefinitionG->blacklistStatus}</span>";

                        ?>
                    </h4>
                </div>
            </div>
            <div class="display-flex align-center">
                <?php if ($blacklisterDefinitionG->blacklistStatus == "Active") { ?>
                    <a href="/modules/NexureSolutions/Blacklister/EditBlacklist/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a>
                    <a href="/modules/NexureSolutions/Blacklister/RevokeBlacklist/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Revoke</a>
                <?php } else { ?>
                    <a href="/modules/NexureSolutions/Blacklister/DeleteBlacklist/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Delete</a>
                <?php } ?>
            </div>
        </div>
        <div class="card-body width-100 macBook-styling-hotfix">
            <div class="display-flex align-center width-100 padding-20px-no-top macBook-padding-top">
                <?php
                    
                    $details = [
                        'Nexure Case ID' => $blacklisterDefinitionG->blacklistIdentifier,
                        'Blacklist Title' => $blacklisterDefinitionG->blacklistTitle,
                        'Submission Date' => $blacklisterDefinitionG->blacklistTimestampformattedfinal,
                        "Author" => $blacklisterDefinitionG->submittedBy,
                        "Compelling Evidence" => '<a class="careers-link" href="{$blacklisterDefinitionG->compellingEvidence}">View Documents</a>'
                    ];
                    
                    foreach ($details as $label => $value) {

                        echo "<div style='width:75%;'><p class='no-padding font-14px'>{$label}</p><p class='no-padding font-14px'>{$value}</p></div>";
                    
                    }

                ?>
            </div>
        </div>
    </div>