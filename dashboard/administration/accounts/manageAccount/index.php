<?php

    $pagetitle = "Customer Accounts";
    $pagesubtitle = "Details";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');
    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

    $accountnumber = $_GET['account_number'] ?? '';

    if (!$accountnumber) {

        header("location: /dashboard/administration/accounts");
        exit;

    }

    // Prepare the SQL Statement to get all the users info later.

    $customerAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'");
    $customerAccountInfo = mysqli_fetch_array($customerAccountQuery);
    mysqli_free_result($customerAccountQuery);

    if (!$customerAccountInfo) {

        header("location: /dashboard/administration/accounts");
        exit;

    }

    // Account Specific Data Storage and Variable Declaration

    $legalname = $customerAccountInfo['legalName'];
    $customeremail = $customerAccountInfo['email'];
    $mobilenumber = $customerAccountInfo['mobileNumber'];
    $customerStatus = $customerAccountInfo['accountStatus'];
    $userrole = $customerAccountInfo['userrole'];
    $dbaccountnumber = $customerAccountInfo['accountNumber'];
    $statusreason = $customerAccountInfo['statusReason'];

    // Check the account number to ensure its a real account number

    

    // Account Notes Section

    $notesResults = mysqli_query($con, "SELECT * FROM caliweb_accountnotes WHERE accountNumber='$accountnumber' ORDER BY id DESC");

    // Get the Interaction Dates Information for the Account Header

    $newInteractionDate = date('Y-m-d H:i:s');
    mysqli_query($con, "UPDATE caliweb_users SET lastInteractionDate='$newInteractionDate' WHERE accountNumber='$accountnumber'");

    $firstinteractiondate = isset($customerAccountInfo['firstInteractionDate']) ? $customerAccountInfo['firstInteractionDate'] : null;
    $lastinteractiondate = mysqli_fetch_assoc(mysqli_query($con, "SELECT lastInteractionDate FROM caliweb_users WHERE accountNumber='$accountnumber'"))['lastInteractionDate'] ?? null;

    // Business Sepecific Data Storage and Variable Declaration for the customers business

    $businessAccountInfo = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM caliweb_businesses WHERE email = '".mysqli_real_escape_string($con, $customeremail)."'"));
    $businessname = $businessAccountInfo['businessName'] ?? $legalname;
    $businessindustry = $businessAccountInfo['businessIndustry'] ?? "Not Assigned";
    $websitedomain = $businessAccountInfo ? (mysqli_fetch_array(mysqli_query($con, "SELECT * FROM caliweb_websites WHERE email = '".mysqli_real_escape_string($con, $customeremail)."'"))['domainName'] ?? "Not Assigned") : "Not Assigned";

    // Fetch website info

    $websiteAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_websites WHERE email = '$customeremail'");
    $websiteAccountInfo = mysqli_fetch_array($websiteAccountQuery);
    mysqli_free_result($websiteAccountQuery);

    $websitedomain = $websiteAccountInfo['domainName'] ?? 'Not Assigned';

    // Function to load the tables.

    include($_SERVER["DOCUMENT_ROOT"].'/modules/caliTables/accountTables/index.php');

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
                                        <p class="no-padding">Authorized Users</p>
                                        <a href="/dashboard/administration/accounts/createAuthorizedUser/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Create User</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <?php

                                            fetchAndDisplayTable(
                                                $con,
                                                "SELECT * FROM caliweb_users WHERE userrole = 'authorized user' AND accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'",
                                                ['Name', 'Phone', 'Type', 'Status', 'Actions'],
                                                ['legalName', 'mobileNumber', 'userrole', 'accountStatus'],
                                                ['25%', '20%', '20%', '20%'],
                                                [
                                                    'View' => "/dashboard/administration/accounts/manageAccount/{accountNumber}",
                                                    'Edit' => "/dashboard/administration/accounts/editAccount/{accountNumber}",
                                                    'Delete' => "/dashboard/administration/accounts/deleteAccount/{accountNumber}"
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

                                            fetchAndDisplayTable(
                                                $con,
                                                "SELECT * FROM caliweb_services WHERE accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'",
                                                ['Service Name', 'Type', 'Started', 'Renewal', 'Cost', 'Status', 'Actions'],
                                                ['serviceName', 'serviceType', 'serviceStartDate', 'serviceEndDate', 'serviceCost', 'serviceStatus'],
                                                ['20%', '15%', '15%', '15%', '10%', '10%'],
                                                [
                                                    'View' => "/modules/{linkedServiceName}/?account_number={accountNumber}",
                                                    'Edit' => "/dashboard/administration/accounts/editServices/?account_number={urlAccountNumber}&service_name={urlServiceName}",
                                                    'Delete' => "/dashboard/administration/accounts/deleteServices/?account_number={urlAccountNumber}&service_name={urlServiceName}"
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

                                            fetchAndDisplayTable(
                                                $con,
                                                "SELECT * FROM caliweb_cases WHERE accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'",
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
                        <div class="caliweb-card dashboard-card" style="margin-bottom:2%;">
                            <div class="card-header">
                                <p class="no-padding">Account Insights</p>
                            </div>
                            <div class="card-body">
                                <!-- Insights content here -->
                            </div>
                        </div>
                        <div class="caliweb-card dashboard-card">
                            <div class="card-header">
                                <div class="display-flex align-center" style="justify-content:space-between;">
                                    <p class="no-padding">Notes and Activity</p>
                                    <a href="/dashboard/administration/accounts/addActivityNote/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Place Note</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (mysqli_num_rows($notesResults) == 0): ?>
                                    <p class="font-14px no-padding" style="margin-top:10px; margin-bottom:10px;">No notes have been made for this account.</p>
                                <?php endif; ?>
                                <?php if ($statusreason): ?>
                                    <div class="caliweb-card dashboard-card note-card">
                                        <div class="card-header">
                                            <div class="display-flex align-center">
                                                <div class="no-padding margin-20px-right icon-size-formatted" style="height: 40px; width: 40px;">
                                                    <img src="/assets/img/systemIcons/notesicon.png" alt="Notes Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                                </div>
                                                <div>
                                                    <p class="no-padding font-12px"><strong>Account Status</strong></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="no-padding font-12px"><?= $statusreason ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php 
                                    while ($row = mysqli_fetch_assoc($notesResults)): 
                                    $addedAtDateModify = DateTime::createFromFormat('d-m-Y h:i:sa', $row['added_at'])->format('Y-m-d H:i:s');
                                ?>
                                    <div class="caliweb-card dashboard-card note-card" style="margin-bottom:10px;">
                                        <div class="card-header">
                                            <div class="display-flex align-center">
                                                <div class="no-padding margin-20px-right icon-size-formatted" style="height: 40px; width: 40px;">
                                                    <img src="/assets/img/systemIcons/notesicon.png" alt="Notes Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                                </div>
                                                <div>
                                                    <p class="no-padding font-12px"><strong>Note Added:</strong></p>
                                                    <p class="no-padding font-12px"><?= $addedAtDateModify ?></p>
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

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>