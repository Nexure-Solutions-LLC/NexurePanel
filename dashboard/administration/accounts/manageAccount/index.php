<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    $pagetitle = "Customer Accounts";
    $pagesubtitle = "Details";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/tables/accountTables/index.php');

    if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

        if ($variableDefinitionX->paymentProcessorName == "Stripe") {

            include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

        } else {

            header ("location: /error/genericSystemError");

        }

    } else {

        echo 'There are no payment modules available to service this request.';

    }
    
    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    $accountnumber = $_GET['account_number'] ?? '';

    if (!$accountnumber) {

        header("location: /dashboard/administration/accounts");
        
        exit;

    }

    $manageAccountDefinitionR = new \NexureSolutions\Generic\VariableDefinitions();

    $manageAccountDefinitionR->manageAccount($con, $accountnumber);

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <?php include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Account/Headers/index.php'); ?>
                <div class="caliweb-two-grid special-caliweb-spacing account-grid-modified">
                    <div>
                        <div class="caliweb-card dashboard-card">
                            <?php include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Account/Menus/index.php'); ?>
                            <?php

                                $blacklistCheck = $manageAccountDefinitionR->checkForBlacklistAccount(
                                    $accountnumber,
                                    $con
                                );

                                if (!isset($blacklistCheck['status'])) {
                                    
                                    echo $blacklistCheck;

                                } else {

                                    echo $blacklistCheck['status'];

                                }

                            ?>
                            <?php

                                $duplicateCheck = $manageAccountDefinitionR->checkForDuplicateAccount(
                                    $accountnumber, 
                                    $manageAccountDefinitionR->legalname, 
                                    $manageAccountDefinitionR->customeremail, 
                                    $manageAccountDefinitionR->businessname, 
                                    $manageAccountDefinitionR->websitedomain, 
                                    $manageAccountDefinitionR->businessindustry, 
                                    $con
                                );

                                echo $duplicateCheck['status'];

                            ?>
                            <div class="caliweb-card dashboard-card">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <p class="no-padding">Authorized Users</p>
                                        <a href="/dashboard/administration/accounts/createAuthorizedUser/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Create User</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <?php

                                            accountsManageListingTable(
                                                $con,
                                                "SELECT * FROM nexure_users WHERE userrole = 'authorized user' AND accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'",
                                                ['Name', 'Phone', 'Type', 'Status', 'Actions'],
                                                ['legalName', 'mobileNumber', 'userrole', 'accountStatus'],
                                                ['25%', '20%', '20%', '20%'],
                                                [
                                                    'View' => "/dashboard/administration/accounts/manageAccount/?account_number={accountNumber}&account_type=".urlencode('Authorized User'),
                                                    'Edit' => "/dashboard/administration/accounts/editAccount/?account_number={accountNumber}&account_type=".urlencode('Authorized User'),
                                                    'Delete' => "/dashboard/administration/accounts/deleteAccount/?account_number={accountNumber}&account_type=".urlencode('Authorized User')
                                                ]
                                            );

                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card" style="margin-top:10px;">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <p class="no-padding">Current Services</p>
                                        <a href="/dashboard/administration/accounts/orderServices/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Order Service</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <?php

                                            accountsManageListingTable(
                                                $con,
                                                "SELECT * FROM nexure_services WHERE accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'",
                                                ['Service ID', 'Service Name', 'Type', 'Started', 'Renewal', 'Cost', 'Status', 'Actions'],
                                                ['serviceIdentifier', 'serviceName', 'serviceType', 'serviceStartDate', 'serviceEndDate', 'serviceCost', 'serviceStatus'],
                                                ['15%', '20%', '15%', '12%', '12%', '8%', '8%'],
                                                [
                                                    'View' => "{linkedServiceName}/?account_number={accountNumber}",
                                                    'Edit' => "/dashboard/administration/accounts/editServices/?account_number={accountNumber}&service_id={serviceIdentifier}",
                                                    'Delete' => "/dashboard/administration/accounts/deleteServices/?account_number={accountNumber}&service_id={serviceIdentifier}"
                                                ]
                                            );

                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card" style="margin-top:10px; margin-bottom:2%;">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <p class="no-padding">Files and Documents</p>
                                        <a href="/dashboard/administration/accounts/fileUpload/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Upload File</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <?php

                                            accountsManageListingTable(
                                                $con,
                                                "SELECT * FROM nexure_fileRecords WHERE accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'",
                                                ['File Name', 'Type', 'Upload Date', 'Actions'],
                                                ['fileDisplayName', 'fileType', 'fileUploadDate'],
                                                ['30%', '40%', '20%'],
                                                [
                                                    'Download' => "{filePath}",
                                                    'Delete' => "/dashboard/administration/accounts/fileDeletion/?file_path={filePath}"
                                                ]
                                            );

                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card" style="margin-top:10px; margin-bottom:2%;">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <p class="no-padding">Cases</p>
                                        <a href="/dashboard/administration/cases/createCase/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Create Case</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <?php

                                            accountsManageListingTable(
                                                $con,
                                                "SELECT * FROM nexure_cases WHERE accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'",
                                                ['Case Number', 'Case Title', 'Created Date', 'Closed Date', 'Assigned Agent', 'Status', 'Actions'],
                                                ['caseNumber', 'caseTitle', 'caseCreateDate', 'caseCloseDate', 'assignedAgent', 'caseStatus'],
                                                ['10%', '20%', '15%', '15%', '15%', '10%'],
                                                [
                                                    'View' => "/dashboard/administration/cases/viewCases/?account_number={accountNumber}&case_number={caseNumber}",
                                                    'Edit' => "/dashboard/administration/cases/editCase/?account_number={accountNumber}&case_number={caseNumber}",
                                                    'Delete' => "/dashboard/administration/cases/deleteCase/?account_number={accountNumber}&case_number={caseNumber}"
                                                ]
                                            );

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <?php if ($manageAccountDefinitionR->customerStatus == "Under Review" || $manageAccountDefinitionR->customerStatus == "under review") : ?>
                            <div class="caliweb-card dashboard-card" style="margin-bottom:2%;">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <p class="no-padding">Important Notice</p>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="font-14px display-flex align-center"><img src="/assets/img/systemIcons/infoicon.png" class="filter-white-on-dark" style="margin-right:20px; width:30px; height:30px;" /> <span>This account is currently pending and has not been approved automatically. Please take action or request the client to finish onboarding.</span></p>
                                </div>
                                <div class="card-footer">
                                    <div class="display-flex align-center">
                                        <a href="/dashboard/administration/accounts/approveAccount/index.php?account_number=<?php echo $accountnumber; ?>" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Approve</a>
                                        <a href="/dashboard/administration/accounts/rejectAccount/index.php?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Reject</a>
                                        <a href="/dashboard/administration/accounts/transferAccount/index.php?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin" style="padding:6px 24px;">Transfer</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="caliweb-card dashboard-card" style="margin-bottom:2%;">
                            <div class="card-header">
                                <p class="no-padding">Account Insights</p>
                            </div>
                            <div class="card-body">
                                <!-- Insights content here -->
                                <div class="caliweb-grid caliweb-three-grid" style="grid-row-gap:0!important; padding:0; margin:0; margin-bottom:20px;">
                                    <div>

                                        <p style="font-size:12px; color:grey;">Balance</p>

                                        <p style="font-size:16px; font-weight:800; font-family: Mona Sans, sans-serif;">

                                            <?php echo '$'.getCreditBalance($manageAccountDefinitionR->customerStripeID); ?>
                                        
                                        </p>

                                    </div>
                                    <div>

                                        <p style="font-size:12px; color:grey;">Due Date</p>

                                        <?php

                                            $dueDate =  $dueDate = getSubscriptionDueDate($manageAccountDefinitionR->customerStripeID);

                                            echo "<p style='font-size:16px; font-weight:800; font-family: Mona Sans, sans-serif;'>$dueDate</p>";

                                        ?>

                                    </div>
                                    <div></div>
                                </div>
                                <div class="caliweb-horizantal-spacer"></div>
                                <div class="caliweb-grid caliweb-three-grid" style="grid-row-gap:0!important; padding:0; margin:0; margin-top:20px;">
                                    <div>
                                        <?php

                                            // Gets the total amount spent with Nexure by totaling
                                            // the amount of payments a given customer has made.

                                            $totalAmount = 0;
                                            
                                            $totalPayments = getTotalPayments($manageAccountDefinitionR->customerStripeID);


                                        ?>
                                        <p style="font-size:12px; color:grey;">Total Spend</p>
                                        <p style="font-size:16px; font-weight:800; font-family: Mona Sans, sans-serif;">$<?php echo number_format($totalPayments, 2); ?></p>
                                    </div>
                                    <div>
                                        <p style="font-size:12px; color:grey;">Customer Since</p>

                                        <?php

                                            $registrationYear = date('Y', strtotime($manageAccountDefinitionR->regdateformattedfinal));

                                        ?>

                                        <p style="font-size:16px; font-weight:800; font-family: Mona Sans, sans-serif;"><?php echo $registrationYear; ?></p>
                                    </div>
                                    <div>
                                        <p style="font-size:12px; color:grey;">Tax Status</p>

                                        <?php

                                            $taxStatus = getTaxStatus($manageAccountDefinitionR->customerStripeID);

                                        ?>

                                        <p style="font-size:16px; font-weight:800; font-family: Mona Sans, sans-serif;"><?php echo $taxStatus; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="caliweb-card dashboard-card">
                            <div class="card-header">
                                <div class="display-flex align-center" style="justify-content:space-between;">
                                    <p class="no-padding">Notes and Activity</p>
                                    <a href="/dashboard/administration/accounts/addActivityNote/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin" style="padding:6px 24px;">Place Note</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if ($manageAccountDefinitionR->statusreason || mysqli_num_rows($manageAccountDefinitionR->notesResults) == 0 && ($manageAccountDefinitionR->customerStatus == 'Active')): ?>
                                    <p class="font-14px no-padding" style="margin-top:10px; margin-bottom:10px;">No notes have been made for this account.</p>
                                <?php endif; ?>
                                <?php 
                                    while ($row = mysqli_fetch_assoc($manageAccountDefinitionR->notesResults)): 
                                    $addedAtDateModify = DateTime::createFromFormat('d-m-Y h:i:sa', $row['added_at'])->format('M d, Y \a\\t h:i A');
                                ?>
                                    <div class="caliweb-card dashboard-card note-card" style="margin-bottom:10px;">
                                        <div class="card-header">
                                            <div class="display-flex align-center">
                                                <div class="no-padding margin-20px-right icon-size-formatted" style="height: 40px; width: 40px;">
                                                    <img src="/assets/img/systemIcons/notesicon.png" alt="Notes Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                                </div>
                                                <div>
                                                    <p class="no-padding font-12px"><strong>Note Added</strong></p>
                                                    <p class="no-padding font-12px">Author: <?= $row["added_by"] ?></p>
                                                    <p class="no-padding font-12px">Date: <?= $addedAtDateModify ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="no-padding font-12px"><?= $row['content'] ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>