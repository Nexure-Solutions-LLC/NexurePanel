<?php
    $pagetitle = "Customer Accounts";
    $pagesubtitle = "Manage Profile";

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
            $email = $customerAccountInfo['email'];
            $statusreason = $customerAccountInfo['statusReason'];
            $accountnotes = $customerAccountInfo['accountNotes'];

            $regdate = $customerAccountInfo['registrationDate'];
            $regdateformatted = new DateTime($regdate);
            $regdateformattedfinal = $regdateformatted->format('F j, Y g:i A');

            $statusdate = $customerAccountInfo['statusDate'];
            $statusdateformatted = new DateTime($regdate);
            $statusdateformattedfinal = $statusdateformatted->format('F j, Y g:i A');

            $emailverifydate = $customerAccountInfo['emailVerifiedDate'];
            $emailverifydateformatted = new DateTime($regdate);
            $emailverifydateformattedfinal = $emailverifydateformatted->format('F j, Y g:i A');

            $emailverifystatus = ucfirst($customerAccountInfo['emailVerfied']);

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
                <div class="caliweb-two-grid special-caliweb-spacing profile-grid-modified">
                    <div>
                        <div class="caliweb-card dashboard-card">
                            <div class="card-header">
                                <div class="display-flex align-center" style="justify-content:space-between;">
                                    <div>
                                        <p class="no-padding">Account Teams</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php

                                    $sql = "SELECT * FROM `caliweb_teamslisting` WHERE accountNumber = '".$accountnumber."'";
                                    $result = mysqli_query($con, $sql);

                                    if (mysqli_num_rows($result) > 0) {

                                        while ($row = mysqli_fetch_assoc($result)) {

                                            echo '
                                              <div class="display-flex align-center" style="margin-top:10px; margin-bottom:10px;">
                                                  <div class="margin-10px-right icon-size-formatted">
                                                      ';
                                                      if ($row["teamIconFile"] == "teamsdefault.png") {
                                                        echo '<img src="/assets/img/teamsBranchLogos/'.$row["teamIconFile"].'" style="background-color:#a67fff;" alt="" class="client-business-andor-profile-logo" />';
                                                      } else {
                                                        echo '<img src="/assets/img/teamsBranchLogos/'.$row["teamIconFile"].'" alt="" class="client-business-andor-profile-logo" />';
                                                      }
                                                  echo '
                                                  </div>
                                                  <div>
                                                      <p class="font-14px no-padding">'.$row["teamName"].'</p>
                                                      <p class="font-14px no-padding">Team Role: '.$row["teamRole"].'</p>
                                                      <p class="font-14px no-padding">Title: '.$row["teamTitle"].'</p>
                                                  </div>
                                              </div>
                                            ';

                                        }

                                    } else {

                                        echo '<p class="no-padding font-14px" style="margin-top:-2% !important; margin-bottom:25px;">There are no teams for this profile.<p>';

                                    }
                                ?>
                            </div>
                        </div>
                        <div class="caliweb-card dashboard-card" style="margin-top:10px;">
                            <div class="card-header">
                                <div class="display-flex align-center" style="justify-content:space-between;">
                                    <div>
                                        <p class="no-padding">Events and Action Plan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="font-14px no-padding" style="margin-top:10px; margin-bottom:10px;">No upcoming events</p>
                                <p class="font-14px no-padding" style="margin-top:10px; margin-bottom:10px;">No Past Conversations</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="caliweb-card dashboard-card">
                            <?php include($_SERVER["DOCUMENT_ROOT"].'/assets/php/accountMenuSelector.php'); ?>
                            <div class="caliweb-card dashboard-card">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <div>
                                            <p class="no-padding">Profile Details</p>
                                        </div>
                                        <div class="display-flex align-center">
                                            <a href="/dashboard/administration/accounts/modifyProfile/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Edit Profile</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="caliweb-two-grid">
                                        <div>
                                            <p style="margin-bottom:10px; font-size:14px;">Legal Name: <?php echo $legalname; ?></p>
                                            <p style="margin-bottom:10px; font-size:14px;">Mobile Number: <?php echo $mobilenumber; ?></p>
                                            <p style="margin-bottom:10px; font-size:14px;">Email Address: <?php echo $email; ?></p>
                                            <p style="margin-bottom:10px; font-size:14px;">Registration Date: <?php echo $regdateformattedfinal; ?></p>
                                            <p style="margin-bottom:10px; font-size:14px;">Last Status Update: <?php echo $statusdateformattedfinal; ?></p>
                                        </div>
                                        <div>
                                             <p style="margin-bottom:10px; font-size:14px;">Account Number: <?php echo $accountnumber; ?></p>
                                             <p style="margin-bottom:10px; font-size:14px;">Account Standing: <?php echo $customerStatus; ?></p>
                                             <p style="margin-bottom:10px; font-size:14px;">Email Verification Status: <?php echo $emailverifystatus; ?></p>
                                             <p style="margin-bottom:10px; font-size:14px;">Email Verified On: <?php echo $emailverifydateformattedfinal; ?></p>
                                        </div>
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
                                <p class="font-14px no-padding" style="margin-top:10px; margin-bottom:10px;"><?php echo $statusreason; ?></p>
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