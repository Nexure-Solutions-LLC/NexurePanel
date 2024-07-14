<?php
    $pagetitle = "Client";
    $pagesubtitle = "Customer Service";

    $accountnumber = $_GET['account_number'];

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');
    $lowerrole = strtolower($userrole);


    if ($lowerrole == "authorized user") {

        header("location:/dashboard/customers/authorizedUserView/supportCenter");

    } else if ($lowerrole == "partner") {

        header("location:/dashboard/partnerships/supportCenter/");

    } else if ($lowerrole == "administrator") {

        header("location:/dashboard/administration/cases");

    }

    $websiteresult = mysqli_query($con, "SELECT * FROM caliweb_websites WHERE email = '$caliemail'");
    $websiteinfo = mysqli_fetch_array($websiteresult);

    $customerStatus = $userinfo['accountStatus'];

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

?>

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <div class="" style="display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <p class="no-padding" style="font-size:16px;">Support Center / View and Manage Cases</p>
                        </div>
                        <div>
                            <a href="" class="caliweb-button primary">Create Case</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section caliweb-section customer-dashboard-section">
                <div class="container caliweb-container">
                    <div class="caliweb-one-grid">
                        <div class="accounts-overview">
                            <div class="caliweb-card dashboard-card" style="padding:20px; border:0 !important;">
                                <div class="card-header no-padding no-margin customer-card-header" style="padding-top:20px; padding-bottom:20px; border:0;">
                                    <h6 class="no-padding" style="font-size:16px; font-weight:600;">
                                        <?php echo $LANG_CUSTOMER_SUPPORT_CENTER_TITLE_TEXT; ?>
                                    </h6>
                                </div>
                                <div class="card-body" style="padding-top:20px; padding-bottom:20px;">
                                    <?php

                                        // Fetch data from MySQL table
                                        $sql = "SELECT * FROM caliweb_cases WHERE emailAddress = '".$caliemail."'";
                                        $result = mysqli_query($con, $sql);

                                        // Check if any rows were returned
                                        if (mysqli_num_rows($result) > 0) {
                                            // Output table header
                                            echo '<table style="width:100%; margin-top:0%; margin-bottom:0; background-color:transparent;>
                                                    <tr style="background-color:transparent;">
                                                        <th style="width:15%;">Case Number</th>
                                                        <th style="width:20%;">Title</th>
                                                        <th style="width:15%;">Created Date</th>
                                                        <th style="width:15%;">Closed Date</th>
                                                        <th style="width:10%;">Assigned Agent</th>
                                                        <th style="width:15%;">Assigned Department</th>
                                                        <th style="width:15%;">Status</th>
                                                        <th>Actions</th>
                                                    </tr>';

                                            // Output table rows
                                            while ($row = mysqli_fetch_assoc($result)) {

                                                // This formats the date to MM/DD/YYYY HH:MM AM/PM
                                                $caseCreateDateUnformatted = new DateTime($row['caseCreateDate']);
                                                $caseCreateDateFormatted = $caseCreateDateUnformatted->format('m/d/Y g:i A');
                                                $caseCloseDateUnformatted = new DateTime($row['caseCloseDate']);
                                                $caseCloseDateFormatted = $caseCloseDateUnformatted->format('m/d/Y g:i A');

                                                $caseStatusColorAssignment = $row['caseStatus'];

                                                echo '<tr>';
                                                echo '<td style="width:15%;">' . $row['caseNumber'] . '</td>';
                                                echo '<td style="width:20%;">' . $row['caseTitle'] . '</td>';
                                                echo '<td style="width:15%;">' . $caseCreateDateFormatted . '</td>';
                                                echo '<td style="width:15%;">' . $caseCloseDateFormatted . '</td>';
                                                echo '<td style="width:10%;">' . $row['assignedAgent'] . '</td>';
                                                echo '<td style="width:15%;">' . $row['assignedDepartment'] . '</td>';

                                                if ($caseStatusColorAssignment == "Open" || $caseStatusColorAssignment == "open") {
                                                    echo '<td style="width:15%; "><span class="account-status-badge green" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                                                } else if ($caseStatusColorAssignment == "Closed" || $caseStatusColorAssignment == "closed") {
                                                echo '<td style="width:15%; "><span class="account-status-badge passive" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                                                } else if ($caseStatusColorAssignment == "Pending" || $caseStatusColorAssignment == "pending") {
                                                echo '<td style="width:15%; "><span class="account-status-badge yellow" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                                                } else if ($caseStatusColorAssignment == "On Hold" || $caseStatusColorAssignment == "on hold") {
                                                echo '<td style="width:15%; "><span class="account-status-badge red" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                                                }
                                                
                                                echo '<td class=""><a href="/dashboard/administration/cases/viewCases/?case_id=' . $row['id'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">View</a><a href="/dashboard/administration/cases/deleteCase/?case_id=' . $row['id'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">Delete</a><a href="/dashboard/administration/cases/editCase/?case_id=' . $row['id'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a></td>';
                                                echo '</tr>';

                                            }

                                            echo '</table>'; // Close the table

                                        } else {

                                            echo '<p class="no-padding font-14px" style="margin-top:0% !important; margin-bottom:25px;">There are no cases for this account.<p>';
                                            
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>

<?php include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php'); ?>