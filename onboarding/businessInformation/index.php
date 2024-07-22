<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

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

        $businessName = stripslashes($_REQUEST['businessName']);
        $businessName = mysqli_real_escape_string($con, $businessName);
        $businessIndustry = stripslashes($_REQUEST['businessIndustry']);
        $businessIndustry = mysqli_real_escape_string($con, $businessIndustry);
        $businessType = stripslashes($_REQUEST['businessType']);
        $businessType = mysqli_real_escape_string($con, $businessType);
        $businessRevenue = stripslashes($_REQUEST['businessRevenue']);
        $businessRevenue = mysqli_real_escape_string($con, $businessRevenue);
        $businessRegistrationDate = stripslashes($_REQUEST['businessRegistrationDate']);
        $businessRegistrationDate = mysqli_real_escape_string($con, $businessRegistrationDate);
        $businessDescription = stripslashes($_REQUEST['buisnessDescription']);
        $businessDescription = mysqli_real_escape_string($con, $businessDescription);

        // This software has an automatic approval and denial feature based on
        // a list of supported business industires compared against the user selection.
        // If the industry isnt supported the panel will reject the application and
        // send them to the denial screen.

        $checkBusinessIndustryQuery = "SELECT COUNT(*) as count FROM `caliweb_restrictedbusinesses` WHERE `businessIndustry` = '$businessIndustry'";
        $checkBusinessIndustryResult = mysqli_query($con, $checkBusinessIndustryQuery);
        $checkBusinessIndustryRow = mysqli_fetch_assoc($checkBusinessIndustryResult);

        if ($checkBusinessIndustryRow['count'] > 0) {

            $userProfileUpdateQuery = "UPDATE `caliweb_users` SET `accountStatus` = 'Closed', `statusReason`='The customer is running a prohibited business and their application was denied.', `accountNotes`='The customer is runing a prohibited business and their application was denied.' WHERE email = '$caliemail'";
            $userProfileUpdateResult = mysqli_query($con, $userProfileUpdateQuery);

            $userOwnerDeleteQuery = "DELETE FROM caliweb_ownershipinformation WHERE emailAddress = '$caliemail'";
            
            $submitBusinessInformationQuery = "INSERT INTO `caliweb_businesses`(`businessName`, `businessType`, `businessIndustry`, `businessRevenue`, `email`, `businessStatus`, `businessRegDate`, `businessDescription`, `isRestricted`) VALUES ('$businessName','$businessType','$businessIndustry','$businessRevenue','$caliemail','Denied','$businessRegistrationDate','$businessDescription','True')";
            $submitBusinessInformationResult = mysqli_query($con, $submitBusinessInformationQuery);

            if ($userProfileUpdateResult && $userOwnerDeleteQuery && $submitBusinessInformationResult) {

                echo '<script type="text/javascript">window.location = "/onboarding/decision/deniedApp"</script>';

            } else {

                echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

            }

        } else {

            $submitBusinessInformationQuery = "INSERT INTO `caliweb_businesses`(`businessName`, `businessType`, `businessIndustry`, `businessRevenue`, `email`, `businessStatus`, `businessRegDate`, `businessDescription`, `isRestricted`) VALUES ('$businessName','$businessType','$businessIndustry','$businessRevenue','$caliemail','Active','$businessRegistrationDate','$businessDescription','False')";
            $submitBusinessInformationResult   = mysqli_query($con, $submitBusinessInformationQuery);

            if ($submitBusinessInformationResult) {

                echo '<script type="text/javascript">window.location = "/onboarding/billingInformation"</script>';
    
            } else {
    
                echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';
    
            }

        }

    }

    echo '<title>Complete onbording of your new account.</title>';

?>

    <section class="login-container">
        <div class="container caliweb-container bigscreens-are-strange" style="width:50%; margin-top:4%;">
            <div class="caliweb-login-box-header" style="text-align:left; margin-bottom:7%;">
                <h3 class="caliweb-login-heading"><?php echo $orgshortname; ?> <span style="font-weight:700">Onboarding</span></h3>
                <p style="font-size:12px; margin-top:0%;">Please provide your business information so that we can make sure your business is supported.</p>
            </div>
            <div class="caliweb-login-box-body">
                <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
                    <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0;">
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="businessName" class="text-gray-label"><?php echo $LANG_ONBOARD_BUSINESSNAME; ?></label>
                                <input type="text" class="form-input" name="businessName" id="businessName" placeholder="" required="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="businessIndustry" class="text-gray-label"><?php echo $LANG_ONBOARD_BUSINESSINDUSTRY; ?></label>
                                <input type="text" class="form-input" name="businessIndustry" id="businessIndustry" placeholder="Start typing to search..." required />
                                <div id="industryResults" class="industry-results"></div>
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="businessType" class="text-gray-label"><?php echo $LANG_ONBOARD_BUSINESSTYPE; ?></label>
                                <select type="text" class="form-input" name="businessType" id="businessType" required="">
                                    <option>Please select a business type</option>
                                    <option>Privately Held Company</option>
                                    <option>Publicly Held Company</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="businessRevenue" class="text-gray-label"><?php echo $LANG_ONBOARD_BUSINESSREVENUE; ?></label>
                                <input type="text" class="form-input" name="businessRevenue" id="businessRevenue" placeholder="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="businessRegistrationDate" class="text-gray-label"><?php echo $LANG_ONBOARD_BUSINESSREGDATE; ?></label>
                                <input type="date" class="form-input" name="businessRegistrationDate" id="businessRegistrationDate" placeholder="" required="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="businessDescription" class="text-gray-label"><?php echo $LANG_ONBOARD_BUSINESSDESCRIPTION; ?></label>
                                <textarea style="height:150px;" type="text" class="form-input" name="buisnessDescription" id="buisnessDescription" placeholder="" required=""></textarea>
                            </div>
                            <div class="onboarding-button-container" style="margin-top:2%;">
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#businessIndustry').on('input', function() {

                var searchTerm = $(this).val().trim().toLowerCase();
                var resultsContainer = $('#industryResults');
                resultsContainer.empty();

                $.ajax({

                    url: '/onboarding/businessInformation/industrySearchLogic/index.php',
                    dataType: 'json',

                    data: {
                        term: searchTerm
                    },

                    success: function(data) {

                        data.forEach(function(industry) {

                            var item = $('<div class="industry-div">' + industry.name + '</div>');

                            item.on('click', function() {

                                $('#businessIndustry').val(industry.name);
                                resultsContainer.hide();

                            });

                            resultsContainer.append(item);

                        });

                        if (searchTerm.length > 0) {

                            resultsContainer.show();

                        } else {

                            resultsContainer.hide();

                        }

                    },

                    error: function(xhr, status, error) {

                        console.error('AJAX Error:', status, error);

                    }

                });

            });

            $(document).on('click', function(e) {

                if (!$(e.target).closest('#industryResults').length && !$(e.target).is('#businessIndustry')) {

                    $('#industryResults').hide();

                }

            });
            
        });
    </script>

<?php
    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/loginFooter.php');
?>