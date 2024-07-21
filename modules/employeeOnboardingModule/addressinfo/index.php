<?php

    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    include($_SERVER["DOCUMENT_ROOT"]."/assets/php/loginHeader.php");

    $caliemail = $_SESSION['caliid'];

    $userprofileresult = mysqli_query($con, "SELECT * FROM caliweb_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($userprofileresult);
    mysqli_free_result($userprofileresult);

    // User Profile Variable Definitions

    $fullname = $userinfo['legalName'];
    $mobilenumber = $userinfo['mobileNumber'];
    $accountStatus = $userinfo['accountStatus'];

    if ($accountStatus == "Active") {

        header ("Location: /dashboard/customers/");

    } else if ($accountStatus == "Suspended") {

        header ("Location: /error/suspendedAccount");

    } else if ($accountStatus == "Terminated") {

        header ("Location: /error/terminatedAccount");
        
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $addressline1 = stripslashes($_REQUEST['addressline1']);
        $addressline1 = mysqli_real_escape_string($con, $addressline1);
        $addressline2 = stripslashes($_REQUEST['addressline2']);
        $addressline2 = mysqli_real_escape_string($con, $addressline2);
        $city = stripslashes($_REQUEST['city']);
        $city = mysqli_real_escape_string($con, $city);
        $state = stripslashes($_REQUEST['state']);
        $state = mysqli_real_escape_string($con, $state);
        $postalcode = stripslashes($_REQUEST['postalcode']);
        $postalcode = mysqli_real_escape_string($con, $postalcode);
        $country = stripslashes($_REQUEST['country']);
        $country = mysqli_real_escape_string($con, $country);

        $query = "UPDATE `caliweb_ownershipinformation` SET `addressline1`='$addressline1',`addressline2`='$addressline2',`city`='$city',`state`='$state',`postalcode`='$postalcode',`country`='$country' WHERE `emailAddress` = '$caliemail'";
        $result   = mysqli_query($con, $query);

        if ($result) {

            echo '<script type="text/javascript">window.location = "/modules/employeeOnboardingModule/pasthistory"</script>';

        } else {

            echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

        }

    }

    echo '<title>Complete onbording of your new account.</title>';

?>

<section class="login-container">
    <div class="container caliweb-container bigscreens-are-strange" style="width:50%; margin-top:4%;">
        <div class="caliweb-login-box-header" style="text-align:left; margin-bottom:7%;">
            <h3 class="caliweb-login-heading"><?php echo $orgshortname; ?> <span style="font-weight:700">Employment Applcation | Address Information</span></h3>
            <p style="font-size:12px; margin-top:0%;">Please provide your home address so that we can make sure your location is supported.</p>
        </div>
        <div class="caliweb-login-box-body">
            <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
            <div class="caliweb-grid display: block;">
            <div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="addressline1" class="text-gray-label">Address Line 1</label>
                            <input type="text" class="form-input" name="addressline1" id="addressline1" placeholder="" required="" />
                        </div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="city" class="text-gray-label">City</label>
                            <input type="text" class="form-input" name="city" id="city" placeholder="" required="" />
                        </div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="postalcode" class="text-gray-label">Postal Code</label>
                            <input type="text" class="form-input" name="postalcode" id="postalcode" placeholder="" required="" />
                        </div>
                    </div>
                    <div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="addressline2" class="text-gray-label">Address Line 2</label>
                            <input type="text" class="form-input" name="addressline2" id="addressline2" placeholder="" />
                        </div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="state" class="text-gray-label">State</label>
                            <input type="text" class="form-input" name="state" id="state" placeholder="" required="" />
                        </div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="country" class="text-gray-label">Country</label>
                            <input type="text" class="form-input" name="country" id="country" placeholder="" required="" />
                        </div>
                    </div>
                    </div>
                        <div style=" padding-bottom: 50px; display: flex; justify-content: center; gap: 20px; width: 100%;">
                            <div class="form-control width-100">
                            <button class="caliweb-button second" style="text-align: left; display: flex; align-items: center; justify-content: space-between; " type="button" onclick="history.back()"><span class="lnr lnr-arrow-left"></span><?php echo $LANG_BUTTON_BACK; ?></button>
                            
                            </div>
                            <div class="form-control width-100">
                                <button class="caliweb-button primary" style="text-align:left; display:flex; align-center; justify-content:space-between;" type="submit" name="submit"><?php echo $LANG_LOGIN_BUTTON; ?><span class="lnr lnr-arrow-right" style=""></span></button>
                            </div>
                        </div>
                </div>
            </form>
        </div>
    </div>
   
</section>



    </section>
    <div class="caliweb-login-footer">
        <div class="container caliweb-container">
            <div class="caliweb-grid-2">
                <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                <!--
                    THIS TEXT IS TO GIVE CREDIT TO THE AUTHORS AND REMOVING IT
                    MAY CAUSE YOUR LICENSE TO BE REVOKED.
                -->
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Corporation - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                <div class="list-links-footer">
                    <a href="<?php echo $paneldomain; ?>/terms">Terms of Service</a>
                    <a href="<?php echo $paneldomain; ?>/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

<?php
    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/loginFooter.php');
?>