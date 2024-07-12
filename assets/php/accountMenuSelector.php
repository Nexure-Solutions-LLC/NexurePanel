            <?php

                if ($pagesubtitle == "Details") {

                    echo '<div class="tab-switcher">
                        <ul class="display-flex align-center tab-switch-ul">
                            <li class="tab-switch-tab active"><a href="/dashboard/administration/accounts/manageAccount/?account_number='.$accountnumber.'">Details</a></li>
                            <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/paymentMethods/?account_number='.$accountnumber.'">Payment Methods</a></li>
                            <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/relationships/?account_number='.$accountnumber.'">Relationships</a></li>
                            <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/goals/?account_number='.$accountnumber.'">Goals</a></li>
                            <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/related/?account_number='.$accountnumber.'">Related</a></li>
                            <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/profile/?account_number='.$accountnumber.'">Manage Profile</a></li>
                        </ul>
                    </div>';

                } else if ($pagesubtitle == "Payment Methods") {

                    echo '<div class="tab-switcher">
                        <ul class="display-flex align-center tab-switch-ul">
                            <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/?account_number='.$accountnumber.'">Details</a></li>
                            <li class="tab-switch-tab active"><a href="/dashboard/administration/accounts/manageAccount/paymentMethods/?account_number='.$accountnumber.'">Payment Methods</a></li>
                            <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/relationships/?account_number='.$accountnumber.'">Relationships</a></li>
                            <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/goals/?account_number='.$accountnumber.'">Goals</a></li>
                            <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/related/?account_number='.$accountnumber.'">Related</a></li>
                            <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/profile/?account_number='.$accountnumber.'">Manage Profile</a></li>
                        </ul>
                    </div>';

                } else if ($pagesubtitle == "Linked Relationships") {

                   echo '<div class="tab-switcher">
                       <ul class="display-flex align-center tab-switch-ul">
                           <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/?account_number='.$accountnumber.'">Details</a></li>
                           <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/paymentMethods/?account_number='.$accountnumber.'">Payment Methods</a></li>
                           <li class="tab-switch-tab active"><a href="/dashboard/administration/accounts/manageAccount/relationships/?account_number='.$accountnumber.'">Relationships</a></li>
                           <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/goals/?account_number='.$accountnumber.'">Goals</a></li>
                           <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/related/?account_number='.$accountnumber.'">Related</a></li>
                           <li class="tab-switch-tab"><a href="/dashboard/administration/accounts/manageAccount/profile/?account_number='.$accountnumber.'">Manage Profile</a></li>
                       </ul>
                   </div>';

               } else if ($pagesubtitle == "Manage Profile") {

                    echo '<div class="tab-switcher">
                        <ul class="display-flex align-center tab-switch-ul">
                            <li class="tab-switch-tab macBook-Menu-Links-Small"><a href="/dashboard/administration/accounts/manageAccount/?account_number='.$accountnumber.'">Details</a></li>
                            <li class="tab-switch-tab macBook-Menu-Links-Small"><a href="/dashboard/administration/accounts/manageAccount/paymentMethods/?account_number='.$accountnumber.'">Payment Methods</a></li>
                            <li class="tab-switch-tab macBook-Menu-Links-Small"><a href="/dashboard/administration/accounts/manageAccount/relationships/?account_number='.$accountnumber.'">Relationships</a></li>
                            <li class="tab-switch-tab macBook-Menu-Links-Small"><a href="/dashboard/administration/accounts/manageAccount/goals/?account_number='.$accountnumber.'">Goals</a></li>
                            <li class="tab-switch-tab macBook-Menu-Links-Small"><a href="/dashboard/administration/accounts/manageAccount/related/?account_number='.$accountnumber.'">Related</a></li>
                            <li class="tab-switch-tab macBook-Menu-Links-Small active"><a href="/dashboard/administration/accounts/manageAccount/profile/?account_number='.$accountnumber.'">Manage Profile</a></li>
                        </ul>
                    </div>';

               }

            ?>