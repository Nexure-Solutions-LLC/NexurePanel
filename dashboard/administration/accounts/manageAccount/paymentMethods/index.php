<?php

    $pagetitle = "Customer Accounts";
    $pagesubtitle = "Payment Methods";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

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
//             $accountnotes = $customerAccountInfo['accountNotes'];
            // this is being deprecated ^

            $notesQuery = "SELECT * FROM caliweb_accountnotes WHERE accountnumber = '" . $accountnumber . "' ORDER BY id DESC";
            $notesResults = mysqli_query($con, $notesQuery);


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

                                                $processorResult = mysqli_query($con, "SELECT * FROM caliweb_paymentconfig");
                                                $processorInfo = mysqli_fetch_array($processorResult);
                                                mysqli_free_result($processorResult);

                                                $paymentProcessorName = $processorInfo['processorName'];

                                                if ($paymentProcessorName == "Stripe") {

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

                                            // Formats the date to MM/DD/YYYY HH:MM AM/PM format as perfered by
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