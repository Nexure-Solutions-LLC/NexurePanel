<?php
    $pagetitle = "Connected Payments";
    $pagesubtitle = "Home";
    $pagetype = "Administration";
    
    $accountnumber = $_GET['account_number'] ?? '';

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');
    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    if (!$accountnumber) {

        header("location: /dashboard/administration/accounts");
        exit;

    }

    // Prepare the SQL Statement to get all the users info later.

    $customerAccountQuery = mysqli_query($con, "SELECT * FROM nexure_users WHERE accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'");
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

    // Account Notes Section

    $notesResults = mysqli_query($con, "SELECT * FROM nexure_accountnotes WHERE accountNumber='$accountnumber' ORDER BY id DESC");

    // Get the Interaction Dates Information for the Account Header

    $newInteractionDate = date('Y-m-d H:i:s');
    mysqli_query($con, "UPDATE nexure_users SET lastInteractionDate='$newInteractionDate' WHERE accountNumber='$accountnumber'");

    $firstinteractiondate = isset($customerAccountInfo['firstInteractionDate']) ? $customerAccountInfo['firstInteractionDate'] : null;
    $lastinteractiondate = mysqli_fetch_assoc(mysqli_query($con, "SELECT lastInteractionDate FROM nexure_users WHERE accountNumber='$accountnumber'"))['lastInteractionDate'] ?? null;

    // Business Sepecific Data Storage and Variable Declaration for the customers business

    $businessAccountInfo = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM nexure_businesses WHERE email = '".mysqli_real_escape_string($con, $customeremail)."'"));
    $businessname = $businessAccountInfo['businessName'] ?? $legalname;
    $businessindustry = $businessAccountInfo['businessIndustry'] ?? "Not Assigned";
    $websitedomain = $businessAccountInfo ? (mysqli_fetch_array(mysqli_query($con, "SELECT * FROM nexure_websites WHERE email = '".mysqli_real_escape_string($con, $customeremail)."'"))['domainName'] ?? "Not Assigned") : "Not Assigned";

    // Fetch website info

    $websiteAccountQuery = mysqli_query($con, "SELECT * FROM nexure_websites WHERE email = '$customeremail'");
    $websiteAccountInfo = mysqli_fetch_array($websiteAccountQuery);
    mysqli_free_result($websiteAccountQuery);

    $websitedomain = $websiteAccountInfo['domainName'] ?? 'Not Assigned';


?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
               <?php
                    if ($accountnumber == "" || $accountnumber == NULL) {
                        echo '
                        
                            This is if there is no account number.
                        
                        ';
                    } else {
                        echo '
                            <div class="caliweb-one-grid special-caliweb-spacing">
                                <div class="caliweb-card dashboard-card">
                                    <div class="card-body">                
                                        <div class="display-flex align-center" style="justify-content:space-between;">
                                            <div>
                                                <h4 class="text-bold font-size-20 no-padding">Payments Home</h4>
                                            </div>
                                            <div>
                                                <a href="" class="caliweb-button secondary red no-margin margin-10px-right" style="padding:6px 24px;">Disable Merchant</a>
                                                <a href="" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Pause Payouts</a>
                                                <a href="" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Change Terms</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="caliweb-three-grid special-caliweb-spacing">
                                <div class="caliweb-card dashboard-card">
                                    <div class="card-header">
                                        <p class="no-padding">Total Payments</p>
                                    </div>
                                    <div class="card-body">
                                        
                                    </div>
                                    <div class="card-footer">
                                        <a href="" class="careers-link">View Report</a>
                                        <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                                    </div>
                                </div>
                                <div class="caliweb-card dashboard-card">
                                    <div class="card-header">
                                        <p class="no-padding">Customers</p>
                                    </div>
                                    <div class="card-body">                
                                        
                                    </div>
                                    <div class="card-footer">
                                        <a href="" class="careers-link">View Report</a>
                                        <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                                    </div>
                                </div>
                                <div class="caliweb-card dashboard-card">
                                    <div class="card-header">
                                        <p class="no-padding">High Risk Payments</p>
                                    </div>
                                    <div class="card-body">
                                            
                                    </div>
                                    <div class="card-footer">
                                        <a href="" class="careers-link">View Report</a>
                                        <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                                    </div>
                                </div>
                                <div class="caliweb-card dashboard-card">
                                    <div class="card-header">
                                        <p class="no-padding">Gross Volume</p>
                                    </div>
                                    <div class="card-body">
                                        
                                    </div>
                                    <div class="card-footer">
                                        <a href="" class="careers-link">View Report</a>
                                        <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                                    </div>
                                </div>
                                <div class="caliweb-card dashboard-card">
                                    <div class="card-header">
                                        <p class="no-padding">Net Revenue</p>
                                    </div>
                                    <div class="card-body">                
                                        
                                    </div>
                                    <div class="card-footer">
                                        <a href="" class="careers-link">View Cases</a>
                                        <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                                    </div>
                                </div>
                                <div class="caliweb-card dashboard-card">
                                    <div class="card-header">
                                        <p class="no-padding">Failed Payments</p>
                                    </div>
                                    <div class="card-body">                
                                        
                                    </div>
                                    <div class="card-footer">
                                        <a href="" class="careers-link">View Report</a>
                                        <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                                    </div>
                                </div>
                            </div>
                        
                        ';
                    }
                ?>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>