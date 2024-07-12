<?php
    $pagetitle = "Customer Accounts";
    $pagesubtitle = "Payment Methods";

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

    if ($accountnumber == "") {

        header("location: /dashboard/administration/accounts");

    } else {

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
                <?php include($_SERVER["DOCUMENT_ROOT"].'/assets/php/accountHeader.php'); ?>
                <div class="caliweb-two-grid special-caliweb-spacing account-grid-modified">
                    <div>
                        <div class="caliweb-card dashboard-card">
                            <?php include($_SERVER["DOCUMENT_ROOT"].'/assets/php/accountMenuSelector.php'); ?>
                            <div class="caliweb-card dashboard-card">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <div>
                                            <p class="no-padding">Payment Methods</p>
                                        </div>
                                        <div class="display-flex align-center">
                                            <a href="/dashboard/administration/accounts/createPaymentMethod/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Add Payment Method</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <table style="width:100%;">
                                            <?php

                                                $proccessorResult = mysqli_query($con, "SELECT * FROM caliweb_paymentconfig");
                                                $proccessorInfo = mysqli_fetch_array($proccessorResult);
                                                mysqli_free_result($proccessorResult);

                                                $paymentProccessorName = $proccessorInfo['processorName'];

                                                if ($paymentProccessorName == "Stripe") {

                                                    require ($_SERVER["DOCUMENT_ROOT"].'/modules/paymentModule/stripe/index.php');

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