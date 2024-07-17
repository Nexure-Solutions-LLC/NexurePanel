<?php
    $pagetitle = "Customer Accounts";
    $pagesubtitle = "Linked Relationships";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    $lowerrole = strtolower($userrole);
    
    switch ($lowerrole) {
        case "authorized user":
            header("location:/dashboard/customers/authorizedUserView");
            break;
        case "partner":
            header("location:/dashboard/partnerships");
            break;
        case "customer":
            header("location:/dashboard/customers");
            break;
    }

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

    ob_start();

    $accountnumber = $_GET['account_number'];

    // if (!isset($_SESSION['verification_code'])) {

        // header("location: /dashboard/administration/verification/customerVerification/?account_number=$accountnumber");
        
    // }

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
            $accountnotes = $customerAccountInfo['accountNotes'];

            if ($accountnumber != $dbaccountnumber) {

                header("location: /dashboard/administration/accounts");

            } else {

                $businessAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_businesses WHERE email = '".$customeremail."'");
                $businessAccountInfo = mysqli_fetch_array($businessAccountQuery);
                mysqli_free_result($businessAccountQuery);

                if ($businessAccountInfo != NULL) {

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


?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <?php include($_SERVER["DOCUMENT_ROOT"].'/assets/php/accountHeader.php'); ?>
                <div class="caliweb-two-grid special-caliweb-spacing account-grid-modified">
                    <div>
                        <div class="caliweb-card dashboard-card">
                            <?php include($_SERVER["DOCUMENT_ROOT"].'/assets/php/accountMenuSelector.php'); ?>
                            <div class="caliweb-card dashboard-card">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <div>
                                            <p class="no-padding">Linked Relationships</p>
                                        </div>
                                        <div class="display-flex align-center">
                                            <a href="/dashboard/administration/accounts/linkRelationship/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Manually Link Relationship</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <table style="width:100%;">
                                            <?php

                                                // Fetch data from MySQL table
                                                $sql = "SELECT * FROM caliweb_users WHERE userrole = 'authorized user'";
                                                $result = mysqli_query($con, $sql);

                                                // Check if any rows were returned
                                                if (mysqli_num_rows($result) > 0) {

                                                    // Output table header
                                                    echo '<table style="width:100%;">
                                                            <tr>
                                                                <th style="width:18%;">Company</th>
                                                                <th style="width:18%;">Owner</th>
                                                                <th style="width:15%;">Phone</th>
                                                                <th style="width:15%;">Type</th>
                                                                <th style="width:15%;">Status</th>
                                                                <th>Actions</th>
                                                            </tr>';

                                                    // Output table rows
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo '<tr>';
                                                        echo '<td style="width:18%;">' . $row['legalName'] . '</td>';
                                                        echo '<td style="width:18%;">' . $row['legalName'] . '</td>';
                                                        echo '<td style="width:15%;">' . $row['mobileNumber'] . '</td>';
                                                        if ($row["userrole"] == "customer" || $row["userrole"] == "Customer") {
                                                            echo '<td style="width:20%;">Customer - Direct</td>';
                                                        } else if ($row["userrole"] == "partner" || $row["userrole"] == "Partner") {
                                                            echo '<td style="width:20%;">Partner - Affiliate</td>';
                                                        } else {
                                                            echo '<td style="width:20%;">Unknown</td>';
                                                        }
                                                        echo '<td style="width:10%;">' . $row['accountStatus'] . '</td>';
                                                        echo '<td style="display-flex align-center">
                                                                <a href="/dashboard/administration/accounts/manageAccount/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">View</a>
                                                                <a href="/dashboard/administration/accounts/deleteAccount/?account_number='.$row['accountNumber'].'" class="careers-link" style="margin-right:10px;">Delete</a>
                                                                <a href="/dashboard/administration/accounts/editAccount/?account_number='.$row['accountNumber'].'" class="careers-link">Edit</a>
                                                            </td>';
                                                        echo '</tr>';
                                                    }

                                                    echo '</table>'; // Close the table

                                                } else {

                                                    echo '<p class="no-padding font-14px" style="margin-top:-2% !important; margin-bottom:25px;">There are no additional relationships for this account.<p>';
                                                
                                                }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
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
                                        if ($accountnotes == NULL || $accountnotes == "") {

                                            echo "There are no notes for this account.";

                                        } else {

                                            echo $accountnotes; 

                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

                } else {

                    header("location: /dashboard/administration/accounts");

                }

            }

        } else {

            header("location: /dashboard/administration/accounts");

        }
        
    }

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>