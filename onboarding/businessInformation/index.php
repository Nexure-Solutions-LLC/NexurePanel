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

            echo '<script type="text/javascript">window.location = "/onboarding/businessInformation"</script>';

        } else {

            echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

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
                    <div class="caliweb-grid caliweb-two-grid">
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="addressline1" class="text-gray-label">Business Name</label>
                                <input type="text" class="form-input" name="businessName" id="businessName" placeholder="" required="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="city" class="text-gray-label">Business Industry</label>
                                <input type="text" class="form-input" name="businessIndustry" id="businessIndustry" placeholder="Start typing to search..." required />
                                <div id="industryResults" class="industry-results"></div>
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="postalcode" class="text-gray-label">Business Type</label>
                                <select type="text" class="form-input" name="businessType" id="businessType" required="">
                                    <option>Please slect a business type</option>
                                    <option>Privatly Held Company</option>
                                    <option>Publically Held Company</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="addressline2" class="text-gray-label">Business Revenue</label>
                                <input type="text" class="form-input" name="businessRevenue" id="businessRevenue" placeholder="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="state" class="text-gray-label">Business Registration Date</label>
                                <input type="text" class="form-input" name="businessRegistrationDate" id="businessRegistrationDate" placeholder="" required="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="country" class="text-gray-label">Business Description</label>
                                <textarea style="height:150px;" type="text" class="form-input" name="buisnessDescription" id="buisnessDescription" placeholder="" required=""></textarea>
                            </div>
                            <div class="mt-5-per" style="display:flex; align-items:center; justify-content:space-between; float:right;">
                                <div class="form-control width-100">
                                    <button class="caliweb-button primary" style="text-align:left; display:flex; align-center; justify-content:space-between;" type="submit" name="submit"><?php echo $LANG_LOGIN_BUTTON; ?><span class="lnr lnr-arrow-right" style=""></span></button>
                                </div>
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