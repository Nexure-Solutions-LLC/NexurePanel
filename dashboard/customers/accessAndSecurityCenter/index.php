<?php
    $pagetitle = "Client";
    $pagesubtitle = "Access and Security";
    $pagetype = "Client";

    $accountnumber = $_GET['account_number'];

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

?>

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <div class="display-flex align-center" style="justify-content:space-between">
                        <div>
                            <p class="no-padding" style="font-size:16px;">Access and Security</p>
                        </div>
                        <div>
                            <a href="" class="caliweb-button primary">Add Authorized User</a>
                        </div>
                    </div>
                </div>
            </section>


            <section class="section caliweb-section customer-dashboard-section">
                <div class="container caliweb-container">
                    <div class="caliweb-one-grid">
                        <div class="accounts-overview">
                            <div class="caliweb-card dashboard-card" style="padding:0; background-color:transparent !important; border:0 !important;">
                                <div class="card-header no-padding no-margin customer-card-header" style="padding-top:20px; padding-bottom:20px; border:0;">
                                    <h6 class="no-padding" style="font-size:16px; font-weight:600;">
                                        System Administrators
                                    </h6>
                                </div>
                                <div class="card-body" style="padding-top:20px; padding-bottom:20px;">
                                    <?php

                                        // Fetch data from MySQL table
                                        $sql = "SELECT * FROM caliweb_users WHERE email = '".$caliemail."'";
                                        $result = mysqli_query($con, $sql);

                                        // Check if any rows were returned
                                        if (mysqli_num_rows($result) > 0) {

                                            // Output table header
                                            echo '<table style="width:100%; margin-top:0%;">
                                                    <tr>
                                                        <th style="width:25%;">Name</th>
                                                        <th style="width:20%;">Phone</th>
                                                        <th style="width:20%;">Type</th>
                                                        <th style="width:20%;">Status</th>
                                                        <th>Actions</th>
                                                    </tr>';

                                            // Output table rows
                                            while ($row = mysqli_fetch_assoc($result)) {

                                                echo '<tr>';
                                                echo '<td style="width:25%;">' . $row['legalName'] . '</td>';
                                                echo '<td style="width:20%;">' . $row['mobileNumber'] . '</td>';

                                                if ($row["userrole"] == "customer" || $row["userrole"] == "Customer") {

                                                    echo '<td style="width:20%;">Customer - Direct</td>';

                                                } else if ($row["userrole"] == "partner" || $row["userrole"] == "Partner") {

                                                    echo '<td style="width:20%;">Partner - Affiliate</td>';

                                                } else {

                                                    echo '<td style="width:20%;">Unknown</td>';

                                                }

                                                echo '<td style="width:20%;">' . $row['accountStatus'] . '</td>';
                                                echo '<td style="display-flex align-center">
                                                        <a href="/dashboard/administration/accounts/manageAccount/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">View</a>
                                                        <a href="/dashboard/administration/accounts/deleteAccount/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">Delete</a>
                                                        <a href="/dashboard/administration/accounts/editAccount/?account_number='.$row['accountNumber'].'" class="careers-link">Edit</a>
                                                    </td>';
                                                echo '</tr>';

                                            }

                                            echo '</table>'; // Close the table

                                        } else {

                                            echo '<p class="no-padding font-14px" style="margin-top:25px !important; margin-bottom:25px;">There are no authorized users for this account.<p>';

                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card" style="padding:0; background-color:transparent !important; border:0 !important;">
                                <div class="card-header no-padding no-margin customer-card-header" style="padding-top:20px; padding-bottom:20px; border:0;">
                                    <h6 class="no-padding" style="font-size:16px; font-weight:600;">
                                        Authorized Users
                                    </h6>
                                </div>
                                <div class="card-body" style="padding-top:20px; padding-bottom:20px;">
                                    <?php

                                        // Fetch data from MySQL table
                                        $sql = "SELECT * FROM caliweb_users WHERE userrole = 'authorized user' AND ownerAuthorizedEmail = '".$caliemail."'";
                                        $result = mysqli_query($con, $sql);

                                        // Check if any rows were returned
                                        if (mysqli_num_rows($result) > 0) {

                                            // Output table header
                                            echo '<table style="width:100%; margin-top:0%;">
                                                    <tr>
                                                        <th style="width:25%;">Name</th>
                                                        <th style="width:20%;">Phone</th>
                                                        <th style="width:20%;">Type</th>
                                                        <th style="width:20%;">Status</th>
                                                        <th>Actions</th>
                                                    </tr>';

                                            // Output table rows
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<td style="width:25%;">' . $row['legalName'] . '</td>';
                                                echo '<td style="width:20%;">' . $row['mobileNumber'] . '</td>';

                                                if ($row["userrole"] == "customer" || $row["userrole"] == "Customer") {

                                                    echo '<td style="width:20%;">Customer - Direct</td>';

                                                } else if ($row["userrole"] == "partner" || $row["userrole"] == "Partner") {

                                                    echo '<td style="width:20%;">Partner - Affiliate</td>';

                                                } else {

                                                    echo '<td style="width:20%;">Unknown</td>';
                                                    
                                                }

                                                echo '<td style="width:20%;">' . $row['accountStatus'] . '</td>';
                                                echo '<td style="display-flex align-center">
                                                        <a href="/dashboard/administration/accounts/manageAccount/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">View</a>
                                                        <a href="/dashboard/administration/accounts/deleteAccount/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">Delete</a>
                                                        <a href="/dashboard/administration/accounts/editAccount/?account_number='.$row['accountNumber'].'" class="careers-link">Edit</a>
                                                    </td>';
                                                echo '</tr>';
                                            }

                                            echo '</table>'; // Close the table

                                        } else {

                                            echo '<p class="no-padding font-14px" style="margin-top:25px !important; margin-bottom:25px;">There are no authorized users for this account.<p>';

                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>

<?php include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php'); ?>