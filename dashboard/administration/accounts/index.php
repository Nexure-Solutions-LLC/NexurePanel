<?php

    $pagetitle = "Customer Accounts";
    $pagesubtitle = "List";
    $pagetype = "Administration";

    unset($_SESSION['verification_code']);

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div class="display-flex align-center" style="justify-content: space-between;">
                            <div class="display-flex align-center">
                                <div class="no-padding margin-10px-right icon-size-formatted">
                                    <img src="/assets/img/systemIcons/accountsicon.png" alt="Client Logo and/or Business Logo" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">Accounts</p>
                                    <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">List Accounts</h4>
                                </div>
                            </div>
                            <div>
                                <a href="/dashboard/administration/accounts/createAccount/" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            <?php

                                $sql = "SELECT * FROM caliweb_users WHERE userrole <> 'administrator'";
                                $result = mysqli_query($con, $sql);

                                if (mysqli_num_rows($result) > 0) {

                                    echo '<table style="width:100%;">
                                            <tr>
                                                <th style="width:30%;">Company/Account Number</th>
                                                <th style="width:20%;">Owner</th>
                                                <th style="width:20%;">Phone</th>
                                                <th style="width:20%;">Type</th>
                                                <th style="width:10%;">Status</th>
                                                <th>Actions</th>
                                            </tr>';

                                    while ($row = mysqli_fetch_assoc($result)) {

                                        $accountStatusColorAssignment = strtolower($row['accountStatus']);
                                        $businessAccountQuery = mysqli_query($con, "SELECT businessName FROM caliweb_businesses WHERE email = '" . $row['email'] . "'");
                                        $businessAccountInfo = mysqli_fetch_array($businessAccountQuery);
                                        mysqli_free_result($businessAccountQuery);

                                        $businessname = $businessAccountInfo['businessName'] ?? $row['legalName'];
                                        $accountNumber = '•••• ' . substr($row['accountNumber'], -4);
                                        $userrole = strtolower($row["userrole"]);
                                        $accountType = $userrole === "customer" ? "Customer - Direct" : ($userrole === "partner" ? "Partner - Affiliate" : "Unknown");

                                        $statusClasses = [
                                            "active" => "green",
                                            "suspended" => "red",
                                            "under review" => "yellow",
                                            "terminated" => "red-dark",
                                            "closed" => "passive"
                                        ];

                                        $statusClass = $statusClasses[$accountStatusColorAssignment] ?? "unknown";

                                        echo '<tr>
                                                <td style="width:30%;">' . $businessname . ' (' . $accountNumber . ')</td>
                                                <td style="width:20%;">' . $row['legalName'] . '</td>
                                                <td style="width:20%;">' . $row['mobileNumber'] . '</td>
                                                <td style="width:20%;">' . $accountType . '</td>
                                                <td style="width:20%;"><span class="account-status-badge ' . $statusClass . '" style="margin-left:0;">' . $row['accountStatus'] . '</span></td>
                                                <td><a href="/dashboard/administration/accounts/manageAccount/?account_number=' . $row['accountNumber'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">View</a><a href="/dashboard/administration/accounts/deleteAccount/?account_number=' . $row['accountNumber'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">Delete</a><a href="/dashboard/administration/accounts/editAccount/?account_number=' . $row['accountNumber'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a>
                                                </td>
                                            </tr>';

                                    }

                                    echo '</table>';

                                } else {

                                    echo '<table style="width:100%;">
                                            <tr>
                                                <th style="width:30%;">Company/Account Number</th>
                                                <th style="width:20%;">Owner</th>
                                                <th style="width:20%;">Phone</th>
                                                <th style="width:20%;">Type</th>
                                                <th style="width:10%;">Status</th>
                                                <th>Actions</th>
                                            </tr>
                                            <tr>
                                                <td style="width:20%;">There are no Accounts</td>
                                                <td style="width:20%;"></td>
                                                <td style="width:20%;"></td>
                                                <td style="width:20%;"></td>
                                                <td style="width:20%;"></td>
                                                <td style="width:10%;"></td>
                                            </tr>
                                        </table>';

                                }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php 

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php'); 

?>