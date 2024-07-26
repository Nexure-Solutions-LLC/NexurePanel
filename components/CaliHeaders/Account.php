    <div class="caliweb-card dashboard-card custom-padding-account-card">
        <div class="card-header-account">
            <div class="display-flex align-center">
                <div class="no-padding margin-10px-right icon-size-formatted">
                    <img src="/assets/img/customerBusinessLogos/defaultstore.png" alt="Client Logo and/or Business Logo" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                </div>
                <div>
                    <p class="no-padding font-14px" style="padding-bottom:4px;">Account</p>
                    <h4 class="text-bold font-size-16 no-padding display-flex align-center">
                        <?php echo $businessname; ?> - <?php echo $accountnumber; ?>
                        <?php

                            $statusClasses = [
                                "Active" => "green",
                                "Suspended" => "red",
                                "Terminated" => "red-dark",
                                "Under Review" => "yellow",
                                "Closed" => "passive"
                            ];
                            
                            $statusClass = $statusClasses[ucwords(strtolower($customerStatus))] ?? 'default';
                            echo "<span class='account-status-badge $statusClass'>{$customerStatus}</span>";

                        ?>
                    </h4>
                </div>
            </div>
            <div class="display-flex align-center">
                <a href="" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a>
                <a href="/dashboard/administration/accounts/viewAsOwner/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">View as Owner</a>
            </div>
        </div>
        <div class="card-body width-75 macBook-styling-hotfix">
            <div class="display-flex align-center width-100 padding-20px-no-top macBook-padding-top">
                <?php

                    $details = [
                        'Type' => ($userrole === 'customer' || $userrole === 'Customer') ? 'Customer - Direct' : (($userrole === 'partner' || $userrole === 'Partner') ? 'Partner - Affiliate' : 'Unknown'),
                        'Phone' => $mobilenumber,
                        'Website' => $websitedomain,
                        'Owner' => $legalname,
                        'Last Interaction' => '01/01/1970',
                        'Next Interaction' => '01/19/2038'
                    ];
                    
                    foreach ($details as $label => $value) {

                        echo "<div class='width-60'><p class='no-padding font-14px'>{$label}</p><p class='no-padding font-14px'>{$value}</p></div>";
                    
                    }

                ?>
                <div class="width-100">
                    <p class="no-padding font-14px">Industry</p>
                    <p class="no-padding font-14px"><?php echo $businessindustry; ?></p>
                </div>
            </div>
        </div>
    </div>