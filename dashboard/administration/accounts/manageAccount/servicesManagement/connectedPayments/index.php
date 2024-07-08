<?php
    $pagetitle = "Connected Payments";
    $pagesubtitle = "Home";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    if ($userrole == "Customer" || $userrole == "customer") {
        header("location:/dashboard/customers");
    } else if ($userrole == "Authorized User" || $userrole == "authorized user") {
        header("location:/dashboard/customers/authorizedUserView");
    } else if ($userrole == "Partner" || $userrole == "partner") {
        header("location:/dashboard/partnerships");
    }

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

    ob_start();

    $accountnumber = $_GET['account_number'];

    if (!isset($_SESSION['verification_code'])) {
        header("location: /dashboard/administration/verification/customerVerification/?account_number=$accountnumber");
    }



    $customerAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '".$accountnumber."'");
    $customerAccountInfo = mysqli_fetch_array($customerAccountQuery);
    mysqli_free_result($customerAccountQuery);

    if ($customerAccountInfo != NULL) {

        $legalname = $customerAccountInfo['legalName'];
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
            $businessAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_businesses WHERE id = '".$customerSystemID."'");
            $businessAccountInfo = mysqli_fetch_array($businessAccountQuery);
            mysqli_free_result($businessAccountQuery);

            if ($businessAccountInfo != NULL) {
                $businessname = $businessAccountInfo['businessName'];
                $businessindustry = $businessAccountInfo['businessIndustry'];

                $websiteAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_websites WHERE id = '".$customerSystemID."'");
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
                                        <h4 class="text-bold font-size-20 no-padding">Payments Home</h4>
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
                                        <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                                    </div>
                                </div>
                                <div class="caliweb-card dashboard-card">
                                    <div class="card-header">
                                        <p class="no-padding">Customers</p>
                                    </div>
                                    <div class="card-body">                
                                        <h4 class="text-bold font-size-20 no-padding">2</h4>
                                    </div>
                                    <div class="card-footer">
                                        <a href="" class="careers-link">View Report</a>
                                        <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
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
                                        <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
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
                                        <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
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
                                        <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                                    </div>
                                </div>
                                <div class="caliweb-card dashboard-card">
                                    <div class="card-header">
                                        <p class="no-padding">Failed Payments</p>
                                    </div>
                                    <div class="card-body">                
                                        <h4 class="text-bold font-size-20 no-padding">3</h4>
                                    </div>
                                    <div class="card-footer">
                                        <a href="" class="careers-link">View Report</a>
                                        <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
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

            } else {
                header("location: /dashboard/administration/accounts");
            }
        }
    } else {
        header("location: /dashboard/administration/accounts");
    }

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>