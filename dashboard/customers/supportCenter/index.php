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

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

?>

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <div class="" style="display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <p class="no-padding" style="font-size:16px;">Support Center / View and Manage Cases</h4>
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
                                        <?php echo $LANG_CUSTOMER_SUPORTCENTER_TITLE_TEXT; ?>
                                    </h6>
                                </div>
                                <div class="card-body" style="padding-top:20px; padding-bottom:20px;">
                                    <?php
                                        // Fetch data from MySQL table
                                        $sql = "SELECT * FROM caliweb_cases WHERE accountNumber = '".$accountnumber."'";
                                        $result = mysqli_query($con, $sql);

                                        // Check if any rows were returned
                                        if (mysqli_num_rows($result) > 0) {
                                            // Output table header
                                            echo '<table style="width:100%; margin-top:0%; margin-bottom:0; background-color:transparent;>
                                                    <tr style="background-color:transparent;">
                                                        <th style="width:20%; background-color:transparent !important;">Service Name</th>
                                                        <th style="width:15%; background-color:transparent !important;">Type</th>
                                                        <th style="width:15%; background-color:transparent !important;">Started</th>
                                                        <th style="width:15%; background-color:transparent !important;">Renewal</th>
                                                        <th style="width:10%; background-color:transparent !important;">Cost</th>
                                                        <th style="width:10%; background-color:transparent !important;">Status</th>
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
                                                echo '<td style="width:20%; background-color:transparent !important;">' . $row['serviceName'] . '</td>';
                                                echo '<td style="width:15%; background-color:transparent !important;">' . $row['serviceType'] . '</td>';
                                                echo '<td style="width:15%; background-color:transparent !important;">' . $serviceStartDateFormatted . '</td>';
                                                echo '<td style="width:15%; background-color:transparent !important;">' . $serviceEndDateFormatted . '</td>';
                                                echo '<td style="width:10%; background-color:transparent !important;">$ ' . $row['serviceCost'] . '</td>';
                                                echo '<td style="width:10%; background-color:transparent !important;">' . $row['serviceStatus'] . '</td>';
                                                echo '<td style="background-color:transparent !important;">
                                                        <a href="/dashboard/administration/accounts/manageAccount/servicesManagement/'.$row['linkedServiceName'].'/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">View</a>
                                                        <a href="/dashboard/administration/accounts/manageAccount/servicesManagement/'.$row['linkedServiceName'].'/deleteService/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">Delete</a>
                                                        <a href="/dashboard/administration/accounts/manageAccount/servicesManagement/'.$row['linkedServiceName'].'/editService/?account_number='.$row['accountNumber'].'" class="careers-link">Edit</a>
                                                    </td>';
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