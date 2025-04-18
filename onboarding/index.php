<?php

    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/Login/Headers/index.php");

    $caliemail = $_SESSION['caliid'];

    $userprofileresult = mysqli_query($con, "SELECT * FROM nexure_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($userprofileresult);
    mysqli_free_result($userprofileresult);

    // User Profile Variable Definitions

    $fullname = $userinfo['legalName'];
    $mobilenumber = $userinfo['mobileNumber'];
    $accountStatus = $userinfo['accountStatus'];

    if ($accountStatus == "Active") {

        echo '<script type="text/javascript">window.location = "Location: /dashboard/customers/"</script>';

    } else if ($accountStatus == "Suspended") {

        echo '<script type="text/javascript">window.location = "Location: /error/suspendedAccount/"</script>';

    } else if ($accountStatus == "Terminated") {

        echo '<script type="text/javascript">window.location = "Location: /error/terminatedAccount/"</script>';
        
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $current_time = time();

        // Check if the last submission time is stored in the session
        
        if (isset($_SESSION['last_submit_time'])) {

            $time_diff = $current_time - $_SESSION['last_submit_time'];

            if ($time_diff < 5) {

                header("Location: /error/rateLimit");
                exit;

            }
        }

        // If the rate limit check passes, update the last submission time

        $_SESSION['last_submit_time'] = $current_time;
        
        try {

            $dateofbirth = stripslashes($_REQUEST['dateofbirth']);
            $dateofbirth = mysqli_real_escape_string($con, $dateofbirth);
            $einssnnumber = stripslashes($_REQUEST['einssnnumber']);
            $einssnnumber = mysqli_real_escape_string($con, $einssnnumber);

            $encryptKey = hex2bin($_ENV['ENCRYPTION_KEY']);
            $encryptIv = hex2bin($_ENV['ENCRYPTION_IV']);

            function encryptSSN($ssn, $encryptKey, $encryptIv) {

                $cipher = 'aes-256-cbc';
                $encrypted = openssl_encrypt($ssn, $cipher, $encryptKey, 0, $encryptIv);
                return base64_encode($encrypted . '::' . $encryptIv);

            }
            
            $encryptedeinssnumber = encryptSSN($einssnnumber, $encryptKey, $encryptIv);

            $query = "INSERT INTO `nexure_ownershipinformation`(`legalName`, `phoneNumber`, `emailAddress`, `dateOfBirth`, `EINorSSNNumber`, `addressline1`, `addressline2`, `city`, `state`, `postalcode`, `country`) VALUES ('$fullname','$mobilenumber','$caliemail','$dateofbirth','$encryptedeinssnumber','','','','','','')";
            $result   = mysqli_query($con, $query);

            if ($result) {

                echo '<script type="text/javascript">window.location = "/onboarding/addressInformation"</script>';

            } else {

                echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

            }
        
        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
            
        }

    }

    echo '<title>Complete onbording of your new account.</title>';

?>

    <section class="login-container">
        <div class="container caliweb-container bigscreens-are-strange" style="width:50%; margin-top:4%;">
            <div class="caliweb-login-box-header" style="text-align:left; margin-bottom:7%;">
                <h3 class="caliweb-login-heading"><?php echo $variableDefinitionX->orgShortName; ?> <span style="font-weight:700">Onboarding</span></h3>
                <p style="font-size:12px; margin-top:0%;"><?php echo $LANG_ONBOARD_STARTTITLE; ?></p>
            </div>
            <div class="caliweb-login-box-body">
                <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
                    <div class="caliweb-grid caliweb-two-grid no-grid-row-bottom">
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="legalname" class="text-gray-label"><?php echo $LANG_ONBOARD_NAMEFEILD; ?></label>
                                <input type="text" class="form-input" name="legalname" id="legalname" value="<?php echo $fullname; ?>" required="" readonly />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="phonenumber" class="text-gray-label"><?php echo $LANG_ONBOARD_PHONEFEILD; ?></label>
                                <input type="number" class="form-input" name="phonenumber" id="phonenumber" value="<?php echo $mobilenumber; ?>" maxlength="10" inputmode="numeric" required="" readonly />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="emailaddress" class="text-gray-label"><?php echo $LANG_ONBOARD_EMAILFEILD; ?></label>
                                <input type="email" class="form-input" name="emailaddress" id="emailaddress" value="<?php echo $caliemail; ?>" required="" readonly />
                            </div>
                        </div>
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="dateofbirth" class="text-gray-label"><?php echo $LANG_ONBOARD_DOBFEILD; ?></label>
                                <input type="date" class="form-input" name="dateofbirth" id="dateofbirth" placeholder="" requird="" />
                            </div>
                            <div style="border:1px solid green; border-radius:8px; padding:10px; margin-top:0%;">
                                <label style="color:green; font-size:12px;"><?php echo $LANG_ONBOARD_SECUREFORMTITLE; ?></label>
                                <div class="form-control" style="margin-top:4%;">
                                    <label for="einssnnumber" class="text-gray-label"><?php echo $LANG_ONBOARD_EINORSSNFEILD; ?></label>
                                    <input type="password" class="form-input" name="einssnnumber" id="einssnnumber" placeholder="" maxlength="9" inputmode="numeric" requird="" />
                                </div>
                                <p style="font-size:12px; padding:0; margin:0;"><?php echo $LANG_ONBOARD_SUBMITDISCLAIMER; ?></p>
                            </div>
                            <div class="onboarding-button-container" style="margin-top:8%;">
                                <button class="onboarding-button" type="submit" name="submit" style="float: right;">
                                    <span class="lnr lnr-arrow-right"></span>
                                    <span class="tooltip-text">Next Question</span>
                                </button>
                            </div>
                        </div>
                    <div>
                </form>
            </div>
        </div>
    </section>
    <div class="caliweb-login-footer">
        <div class="container caliweb-container">
            <div class="caliweb-grid-2">
                <!-- DO NOT REMOVE THE Nexure COPYRIGHT TEXT -->
                <!--
                    THIS TEXT IS TO GIVE CREDIT TO THE AUTHORS AND REMOVING IT
                    MAY CAUSE YOUR LICENSE TO BE REVOKED.
                -->
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; <span id="nexure-year"></span> - Nexure Solutions LLP - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <!-- DO NOT REMOVE THE Nexure COPYRIGHT TEXT -->
                <div class="list-links-footer">
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/terms">Terms of Service</a>
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Login/Footers/index.php');

?>