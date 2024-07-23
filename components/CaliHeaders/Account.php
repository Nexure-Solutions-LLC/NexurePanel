                <div class="caliweb-card dashboard-card custom-padding-account-card">
                    <div class="card-header-account">
                        <div>
                            <div class="display-flex align-center">
                                <div class="no-padding margin-10px-right icon-size-formatted">
                                    <img src="/assets/img/customerBusinessLogos/defaultstore.png" alt="Client Logo and/or Business Logo" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px" style="padding-bottom:4px;">Account</p>
                                    <h4 class="text-bold font-size-16 no-padding display-flex align-center"><?php echo $businessname; ?> - <?php echo $accountnumber; ?> <?php

                                        if ($customerStatus == "Active" || $customerStatus == "active") {

                                            echo "<span class='account-status-badge green'>Active</span>";

                                        } else if ($customerStatus == "Suspended" || $customerStatus == "suspended") {

                                           echo "<span class='account-status-badge red'>Suspended</span>";

                                        } else if ($customerStatus == "Terminated" || $customerStatus == "terminated") {

                                           echo "<span class='account-status-badge red-dark'>Terminated</span>";

                                        } else if ($customerStatus == "Under Review" || $customerStatus == "under review") {

                                           echo "<span class='account-status-badge yellow'>Under Review</span>";

                                        } else if ($customerStatus == "Closed" || $customerStatus == "closed") {

                                           echo "<span class='account-status-badge passive'>Closed</span>";

                                        }

                                     ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="display-flex align-center">
                            <a href="" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a>
                            <a href="/dashboard/administration/accounts/viewAsOwner/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">View as Owner</a>
                        </div>
                    </div>
                    <div class="card-body width-75 macBook-styling-hotfix">
                        <div class="display-flex align-center width-100 padding-20px-no-top macBook-padding-top">
                            <div class="width-60 macBook-styling-hotfix-1">
                                <p class="no-padding font-14px">Type</p>
                                <p class="no-padding font-14px">
                                    <?php
                                    
                                        if ($userrole == "customer" || $userrole == "Customer") {

                                            echo 'Customer - Direct';

                                        } else if ($userrole == "partner" || $userrole == "Partner") {

                                            echo 'Partner - Affiliate';

                                        } else {

                                            echo 'Unknown';

                                        }

                                    ?>
                                </p>
                            </div>
                            <div class="width-60">
                                <p class="no-padding font-14px">Phone</p>
                                <p class="no-padding font-14px"><?php echo $mobilenumber; ?></p>
                            </div>
                            <div class="width-60">
                                <p class="no-padding font-14px">Website</p>
                                <p class="no-padding font-14px"><?php echo $websitedomain; ?></p>
                            </div>
                            <div class="width-75 macBook-width-60">
                                <p class="no-padding font-14px">Owner</p>
                                <p class="no-padding font-14px"><?php echo $legalname; ?></p>
                            </div>
                            <div class="width-100">
                                <p class="no-padding font-14px">Industry</p>
                                <p class="no-padding font-14px"><?php echo $businessindustry; ?></p>
                            </div>
                            <div class="width-75">
                                <p class="no-padding font-14px">Last Interaction</p>
                                <p class="no-padding font-14px">01/01/1970</p>
                            </div>
                            <div class="width-75">
                                <p class="no-padding font-14px">Next Interaction</p>
                                <p class="no-padding font-14px">01/19/2038</p>
                            </div>
                        </div>
                    </div>
                </div>