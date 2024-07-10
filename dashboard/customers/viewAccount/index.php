<?php
    $pagetitle = "Client";
    $pagesubtitle = "Account Overview";

    $accountnumber = $_GET['account_number'];

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    if ($userrole == "Authorized User" || $userrole == "authorized user") {

        header("location:/dashboard/customers/authorizedUserView");

    } else if ($userrole == "Partner" || $userrole == "partner") {

        header("location:/dashboard/partnerships");

    } else if ($userrole == "Administrator" || $userrole == "administrator") {

        header("location:/dashboard/administration");

    }

    $websiteresult = mysqli_query($con, "SELECT * FROM caliweb_websites WHERE email = '$caliemail'");
    $websiteinfo = mysqli_fetch_array($websiteresult);

    $customerStatus = $userinfo['accountStatus'];

    $businessAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_businesses WHERE email = '".$caliemail."'");
    $businessAccountInfo = mysqli_fetch_array($businessAccountQuery);
    mysqli_free_result($businessAccountQuery);

    $businessname = ($businessAccountInfo !== null) ? $businessAccountInfo['businessName'] : null;

    $accountnumber = $userinfo['accountNumber'];
    $truncatedAccountNumber = substr($accountnumber, -4);
    $customerStatus = $userinfo['accountStatus'];

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

?>

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <p class="no-padding" style="font-size:16px;">Overview / Cali Web Design View Account / Details</h4>
                </div>
            </section>

            <section class="section caliweb-section customer-dashboard-section">
                <div class="container caliweb-container">
                    <div class="caliweb-one-grid" style="grid-row-gap: 32px;">
                        <div class="accounts-overview">
                            <div class="caliweb-card dashboard-card" style="padding:0;">
                                <div class="card-header no-padding no-margin customer-card-header" style="padding:20px; border:0;">
                                    <div class="display-flex align-top">
                                        <div>
                                            <p style="font-size:14px; font-weight:600;">
                                                <?php echo $orgShortName; ?> Standard (...<?php echo $truncatedAccountNumber; ?>)
                                            </p>
                                            <p style="font-size:12px; margin-top:5px;">
                                                <?php
                                                    if ($businessname !== null) {

                                                        echo strtoupper($businessname);

                                                    } else {

                                                        echo strtoupper($fullname);

                                                    }
                                                ?>
                                            </p>
                                        </div>
                                        <span style="padding-left:15px; padding-right:15px;">|</span>
                                        <div>
                                            <a class="display-flex align-center careers-link" style="text-decoration:none;" href="javascript:void(0);" onclick="openModal()"><span style="padding-right:5px;">See full account number</span> <span class="lnr lnr-chevron-right"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="caliweb-three-grid" style="padding:20px;">
                                        <div class="customer-balance">
                                            <h5 style="font-weight:300; font-size:40px;" class="no-padding no-margin">$0.00</h5>
                                            <p style="font-size:12px; padding-top:5px;" class="no-padding no-margin">Owed Balance</p>
                                        </div>
                                        <div class="customer-duedate" style="margin-top:4%;">
                                            <h5 style="font-weight:300; font-size:18px;" class="no-padding no-margin">July 12, 2024</h5>
                                            <p style="font-size:12px; padding-top:5px;" class="no-padding no-margin">Due Date</p>
                                        </div>
                                        <div class="customer-duedate" style="margin-top:3.5%;">
                                            <h5 style="font-weight:300; font-size:18px;" class="no-padding no-margin">
                                                <?php
                                                    if ($customerStatus == "Active" || $customerStatus == "active") {

                                                        echo "<span class='account-status-badge green' style='margin-left:0;'>Active</span>";

                                                    } else if ($customerStatus == "Suspended" || $customerStatus == "suspended") {

                                                       echo "<span class='account-status-badge red' style='margin-left:0;'>Suspended</span>";

                                                    } else if ($customerStatus == "Terminated" || $customerStatus == "terminated") {

                                                       echo "<span class='account-status-badge red-dark' style='margin-left:0;'>Terminated</span>";

                                                    } else if ($customerStatus == "Under Review" || $customerStatus == "under review") {

                                                       echo "<span class='account-status-badge yellow' style='margin-left:0;'>Under Review</span>";

                                                    } else if ($customerStatus == "Closed" || $customerStatus == "closed") {

                                                       echo "<span class='account-status-badge passive' style='margin-left:0;'>Closed</span>";

                                                    }
                                                ?>
                                            </h5>
                                            <p style="font-size:12px; padding-top:10px;" class="no-padding no-margin">Account Standing</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        <?php echo $LANG_CUSTOMER_SERVICES_TITLE_TEXT; ?>
                                    </h6>
                                </div>
                                <div class="card-body" style="padding-top:20px; padding-bottom:20px;">
                                    <?php

                                        // Fetch data from MySQL table
                                        $sql = "SELECT * FROM caliweb_services WHERE accountNumber = '".$accountnumber."'";
                                        $result = mysqli_query($con, $sql);

                                        // Check if any rows were returned
                                        if (mysqli_num_rows($result) > 0) {
                                            // Output table header
                                            echo '<table style="width:100%; margin-top:0%; margin-bottom:0; background-color:transparent;>
                                                    <tr style="background-color:transparent;">
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

                                                echo '<tr>';
                                                echo '<td style="width:20%;">' . $row['serviceName'] . '</td>';
                                                echo '<td style="width:15%;">' . $row['serviceType'] . '</td>';
                                                echo '<td style="width:15%;">' . $serviceStartDateFormatted . '</td>';
                                                echo '<td style="width:15%;">' . $serviceEndDateFormatted . '</td>';
                                                echo '<td style="width:10%;">$ ' . $row['serviceCost'] . '</td>';
                                                echo '<td style="width:10%;">' . $row['serviceStatus'] . '</td>';
                                                echo '<td style="">
                                                        <a href="/dashboard/administration/accounts/manageAccount/servicesManagement/'.$row['linkedServiceName'].'/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">View</a>
                                                        <a href="/dashboard/administration/accounts/manageAccount/servicesManagement/'.$row['linkedServiceName'].'/deleteService/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">Delete</a>
                                                        <a href="/dashboard/administration/accounts/manageAccount/servicesManagement/'.$row['linkedServiceName'].'/editService/?account_number='.$row['accountNumber'].'" class="careers-link">Edit</a>
                                                    </td>';
                                                echo '</tr>';
                                            }

                                            echo '</table>'; // Close the table

                                        } else {

                                            echo '<p class="no-padding font-14px" style="margin-top:0% !important; margin-bottom:25px;">There are no services for this account.<p>';
                                            
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <div id="accountModal" class="modal">
            <div class="modal-content">
                <h6 style="font-size:14px; font-weight:600; padding:0; margin:0;">Full Account Number</h6>
                <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">Full Account Number: <?php echo $accountnumber; ?></p>
                <p style="font-size:14px; padding-bottom:30px;">This account number will be used to identify your account. Keep this number safe.</p>
                <div style="display:flex; align-items:right; justify-content:right;">
                    <button class="caliweb-button primary" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>

        <script>
            var modal = document.getElementById("accountModal");

            function openModal() {
                modal.style.display = "block";
            }

            function closeModal() {
                modal.style.display = "none";
            }
        </script>

<?php include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php'); ?>