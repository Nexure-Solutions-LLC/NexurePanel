<?php

    $pagetitle = "Customer Accounts";
    $pagesubtitle = "Details";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

    ob_start();

    $accountnumber = $_GET['account_number'];

 //   if (!isset($_SESSION['verification_code'])) {

       // header("location: /dashboard/administration/verification/customerVerification/?account_number=$accountnumber");

 //   }

    if ($accountnumber == "") {

        header("location: /dashboard/administration/accounts");

    } else {

        $customerAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '".$accountnumber."'");
        $customerAccountInfo = mysqli_fetch_array($customerAccountQuery);
        mysqli_free_result($customerAccountQuery);

        if ($customerAccountInfo != NULL) {

            $legalname = $customerAccountInfo['legalName'];
            $customeremail = $customerAccountInfo['email'];
            $customerSystemID = $customerAccountInfo['id'];
            $mobilenumber = $customerAccountInfo['mobileNumber'];
            $customerStatus = $customerAccountInfo['accountStatus'];
            $userrole = $customerAccountInfo['userrole'];
            $dbaccountnumber = $customerAccountInfo['accountNumber'];
            $statusreason = $customerAccountInfo['statusReason'];
            $firstinteractiondate = $customerAccountInfo['firstInteractionDate'];

            $newInteractionDate = date('Y-m-d H:i:s');

            $updateInteractionDateQuery = mysqli_query($con, "UPDATE `caliweb_users` SET `lastInteractionDate`='$newInteractionDate' WHERE accountnumber = '" . $accountnumber . "'");

            // Update the last interaction date
            
            $updateInteractionDateQuery = mysqli_query($con, "UPDATE `caliweb_users` SET `lastInteractionDate`='$newInteractionDate' WHERE accountnumber='$accountnumber'");

            if ($updateInteractionDateQuery) {

                // Fetch the updated last interaction date

                $fetchInteractionDateQuery = mysqli_query($con, "SELECT `lastInteractionDate` FROM `caliweb_users` WHERE accountnumber='$accountnumber'");
                
                if ($fetchInteractionDateQuery) {

                    $customerAccountInfo = mysqli_fetch_array($fetchInteractionDateQuery);
                    mysqli_free_result($fetchInteractionDateQuery);
                    $lastinteractiondate = $customerAccountInfo['lastInteractionDate'] ?? null;

                }

            }


            $notesQuery = "SELECT * FROM caliweb_accountnotes WHERE accountnumber = '$accountnumber' ORDER BY id DESC";
            $notesResults = mysqli_query($con, $notesQuery);


            if ($accountnumber != $dbaccountnumber) {

                header("location: /dashboard/administration/accounts");

            } else {

                $businessAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_businesses WHERE email = '".$customeremail."'");
                $businessAccountInfo = mysqli_fetch_array($businessAccountQuery);
                mysqli_free_result($businessAccountQuery);

                if ($businessAccountInfo) {

                    $businessname = $businessAccountInfo['businessName'];
                    $businessindustry = $businessAccountInfo['businessIndustry'];

                    $websiteAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_websites WHERE email = '".$customeremail."'");
                    $websiteAccountInfo = mysqli_fetch_array($websiteAccountQuery);
                    mysqli_free_result($websiteAccountQuery);


                    if ($websiteAccountInfo) {
                        $websitedomain = $websiteAccountInfo['domainName'];
                    } else {
                        $websitedomain = "Not Assigned";
                    }

                } else {
                    $businessname = $legalname;
                    $businessindustry = "Not Assigned";
                    $websitedomain = "Not Assigned";
                }
                    

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <?php include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Account.php'); ?>
                <div class="caliweb-two-grid special-caliweb-spacing account-grid-modified">
                    <div>
                        <div class="caliweb-card dashboard-card">
                            <?php include($_SERVER["DOCUMENT_ROOT"].'/components/CaliMenus/Account.php'); ?>
                            <div class="caliweb-card dashboard-card">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <div>
                                            <p class="no-padding">Authorized Users</p>
                                        </div>
                                        <div class="display-flex align-center">
                                            <a href="/dashboard/administration/accounts/createAuthorizedUser/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Create User</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <table style="width:100%;">
                                            <?php
                                                // Fetch data from MySQL table
                                                $sql = "SELECT * FROM caliweb_users WHERE userrole = 'authorized user' AND accountNumber = '".$accountnumber."'";
                                                $result = mysqli_query($con, $sql);

                                                // Check if any rows were returned
                                                if (mysqli_num_rows($result) > 0) {

                                                    // Output table header
                                                    echo '<table style="width:100%;">
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

                                                    echo '<p class="no-padding font-14px" style="margin-top:-2% !important; margin-bottom:25px;">There are no authorized users for this account.<p>';

                                                }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card" style="margin-top:10px;">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <div>
                                            <p class="no-padding">Current Services</p>
                                        </div>
                                        <div class="display-flex align-center">
                                            <a href="/dashboard/administration/accounts/orderServices/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Order Service</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <table style="width:100%;">
                                            <?php

                                                // Fetch data from MySQL table
                                                $sql = "SELECT * FROM caliweb_services WHERE accountNumber = '".$accountnumber."'";
                                                $result = mysqli_query($con, $sql);

                                                // Check if any rows were returned
                                                if (mysqli_num_rows($result) > 0) {

                                                    // Output table header
                                                    echo '<table style="width:100%;">
                                                            <tr>
                                                                <th style="width:20%;">Service Name</th>
                                                                <th style="width:15%;">Type</th>
                                                                <th style="width:15%;">Started</th>
                                                                <th style="width:15%;">Renewal</th>
                                                                <th style="width:10%;">Cost</th>
                                                                <th style="width:10%;">Status</th>
                                                                <th>Actions</th>
                                                            </tr>';

                                                    // Output table rows
                                                    while ($row = mysqli_fetch_assoc($result)) {

                                                        // This formats the date to MM/DD/YYYY HH:MM AM/PM
                                                        $serviceStartDateUnformattedData = $row['serviceStartDate'];
                                                        $serviceEndDateUnformattedData = $row['serviceEndDate'];
                                                        $serviceStartDateUnformatted = new DateTime($serviceStartDateUnformattedData);
                                                        $serviceEndDateUnformatted = new DateTime($serviceEndDateUnformattedData);
                                                        $serviceStartDateFormatted = $serviceStartDateUnformatted->format('m/d/Y g:i A');
                                                        $serviceEndDateFormatted = $serviceEndDateUnformatted->format('m/d/Y g:i A');

                                                        //Construct URLs

                                                        $urlAccountNumber = $row['accountNumber'];
                                                        $urlServiceName = urlencode($row['serviceName']);

                                                        echo '<tr>';
                                                        echo '<td style="width:20%;">' . $row['serviceName'] . '</td>';
                                                        echo '<td style="width:15%;">' . $row['serviceType'] . '</td>';
                                                        echo '<td style="width:15%;">' . $serviceStartDateFormatted . '</td>';
                                                        echo '<td style="width:15%;">' . $serviceEndDateFormatted . '</td>';
                                                        echo '<td style="width:10%;">$ ' . $row['serviceCost'] . '</td>';
                                                        echo '<td style="width:10%;">' . $row['serviceStatus'] . '</td>';
                                                        echo '<td style="display-flex align-center">
                                                                <a href="/modules/'.$row['linkedServiceName'].'/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">View</a>
                                                                <a href="/dashboard/administration/accounts/deleteServices/?account_number='.$urlAccountNumber.'&service_name='.$urlServiceName.'" class="careers-link" style="margin-right:10px;">Delete</a>
                                                                <a href="/dashboard/administration/accounts/editServices/?account_number='.$urlAccountNumber.'&service_name='.$urlServiceName.'" class="careers-link">Edit</a>
                                                            </td>';
                                                        echo '</tr>';
                                                    }

                                                    echo '</table>'; // Close the table

                                                } else {

                                                    echo '<p class="no-padding font-14px" style="margin-top:-2% !important; margin-bottom:25px;">There are no services for this account.<p>';

                                                }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card" style="margin-top:10px; margin-bottom:2%;">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <div>
                                            <p class="no-padding">Cases</p>
                                        </div>
                                        <div class="display-flex align-center">
                                            <a href="/dashboard/administration/cases/createCase/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Create Case</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <table style="width:100%;">
                                            <?php

                                                // Fetch data from MySQL table
                                                $sql = "SELECT * FROM caliweb_cases WHERE accountNumber = '".$accountnumber."'";
                                                $result = mysqli_query($con, $sql);

                                                // Check if any rows were returned
                                                if (mysqli_num_rows($result) > 0) {

                                                    // Output table header
                                                    echo '<table style="width:100%;">
                                                            <tr>
                                                                <th style="width:10%;">Case Number</th>
                                                                <th style="width:20%;">Case Title</th>
                                                                <th style="width:15%;">Created Date</th>
                                                                <th style="width:15%;">Closed Date</th>
                                                                <th style="width:15%;">Assigned Agent</th>
                                                                <th style="width:10%;">Status</th>
                                                                <th>Actions</th>
                                                            </tr>';

                                                    // Output table rows
                                                    while ($row = mysqli_fetch_assoc($result)) {

                                                        // This formats the date to MM/DD/YYYY HH:MM AM/PM
                                                        $caseCreateDateUnformattedData = $row['caseCreateDate'];
                                                        $caseCloseDateUnformattedData = $row['caseCloseDate'];
                                                        $caseCreateDateUnformatted = new DateTime($caseCreateDateUnformattedData);
                                                        $caseCloseDateUnformatted = new DateTime($caseCloseDateUnformattedData);
                                                        $caseCreateDateFormatted = $caseCreateDateUnformatted->format('m/d/Y g:i A');
                                                        $caseCloseDateFormatted = $caseCloseDateUnformatted->format('m/d/Y g:i A');

                                                        echo '<tr>';
                                                        echo '<td style="width:10%;">' . $row['caseNumber'] . '</td>';
                                                        echo '<td style="width:20%;">' . $row['caseTitle'] . '</td>';
                                                        echo '<td style="width:15%;">' . $caseCreateDateFormatted . '</td>';
                                                        echo '<td style="width:15%;">' . $caseCloseDateFormatted . '</td>';
                                                        echo '<td style="width:15%;">' . $row['assignedAgent'] . '</td>';
                                                        echo '<td style="width:10%;">' . $row['caseStatus'] . '</td>';
                                                        echo '<td style="display-flex align-center">
                                                                <a href="/dashboard/administration/cases/viewCases/?account_number='.$row['accountNumber'].'&case_number='.$row['caseNumber'].'" class="careers-link" style="margin-right:10px;">View</a>
                                                                <a href="/dashboard/administration/cases/deleteCase/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">Delete</a>
                                                                <a href="/dashboard/administration/cases/editCase/?account_number='.$row['accountNumber'].'&case_number='.$row['caseNumber'].'" class="careers-link">Edit</a>
                                                            </td>';
                                                        echo '</tr>';
                                                    }

                                                    echo '</table>'; // Close the table

                                                } else {

                                                    echo '<p class="no-padding font-14px" style="margin-top:-2% !important; margin-bottom:25px;">There are no cases for this account.<p>';

                                                }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="caliweb-card dashboard-card" style="margin-bottom:2%;">
                            <div class="card-header">
                                <div class="display-flex align-center" style="justify-content:space-between;">
                                    <div>
                                        <p class="no-padding">Account Insights</p>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="caliweb-card dashboard-card">
                            <div class="card-header">
                                <div class="display-flex align-center" style="justify-content:space-between;">
                                    <div>
                                        <p class="no-padding">Notes and Activity</p>
                                    </div>
                                    <div class="display-flex align-center">
                                        <a href="/dashboard/administration/accounts/addActivityNote/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Place Note</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="font-14px no-padding" style="margin-top:10px; margin-bottom:10px;">
                                    <?php
                                        if (mysqli_num_rows($notesResults) == 0) {
                                            echo "<p class='no-padding font-12px'>No notes have been made for this account.</p>";
                                        }
                                    ?>
                                </p>
                                <?php
                                    if ($statusreason) {
                                        echo '
                                            <div class="caliweb-card dashboard-card note-card">
                                                <div class="card-header">
                                                    <div class="display-flex align-center">
                                                        <div class="no-padding margin-20px-right icon-size-formatted" style="height: 40px; width: 40px;">
                                                            <img src="/assets/img/systemIcons/notesicon.png" alt="Notes Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                                        </div>
                                                        <div>
                                                            <p class="no-padding font-12px"><strong>'. 'Account Status' .'</strong></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="no-padding font-12px">'. $statusreason .'</p>
                                                </div>
                                            </div>';
                                    }
                                
                                    while ($row = mysqli_fetch_assoc($notesResults)) {

                                            // Formats the date to MM/DD/YYYY HH:MM AM/PM format as preferred by
                                            // United States users. Additional formats will come soon.

                                            $addedAtDateUnFormated = $row["added_at"];
                                            $addedAtDateModify = DateTime::createFromFormat('d-m-Y h:i:sa', $addedAtDateUnFormated);
                                            $addedAtDateFormated = $addedAtDateModify->format('m/d/Y h:i A');

                                            echo '
                                            <div class="caliweb-card dashboard-card note-card">
                                                <div class="card-header">
                                                    <div class="display-flex align-center">
                                                        <div class="no-padding margin-20px-right icon-size-formatted" style="height: 40px; width: 40px;">
                                                            <img src="/assets/img/systemIcons/notesicon.png" alt="Notes Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                                        </div>
                                                        <div>
                                                            <p class="no-padding font-12px"><strong>'. $row["notetype"].'</strong></p>
                                                            <p class="no-padding font-12px">'. $addedAtDateFormated .'</p>
                                                            <p class="no-padding font-12px">'. $row["added_by"].'</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="no-padding font-12px">'. $row["content"].'</p>
                                                </div>
                                            </div>
                                            ';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
            }
            
        } else {

            header("location: /dashboard/administration/accounts");
            
        }

    }

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>