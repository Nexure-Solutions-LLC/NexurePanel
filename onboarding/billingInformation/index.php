<?php
    $pagetitle = "Onboarding Billing";
    $pagetype = "Client";
    $_SESSION['pagetitle'] = $pagetitle;

    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require($_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Utility/Backend/Login/Headers/index.php");

    // Check the user account

    $caliemail = $_SESSION['caliid'];

    $userprofileresult = mysqli_query($con, "SELECT * FROM caliweb_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($userprofileresult);
    mysqli_free_result($userprofileresult);

    // User Profile Variable Definitions

    $fullname = $userinfo['legalName'];
    $mobilenumber = $userinfo['mobileNumber'];
    $accountStatus = $userinfo['accountStatus'];
    $stripeID = $userinfo['stripeID'];

    if ($accountStatus == "Active") {

        header ("Location: /dashboard/customers/");

    } else if ($accountStatus == "Suspended") {

        header ("Location: /error/suspendedAccount");

    } else if ($accountStatus == "Terminated") {

        header ("Location: /error/terminatedAccount");
        
    }

    echo '<title>Complete onboarding of your new account.</title>';

?>

    <section class="login-container">
        <div class="container caliweb-container bigscreens-are-strange" style="width:50%; margin-top:4%;">
            <div class="caliweb-login-box-header" style="text-align:left; margin-bottom:7%;">
                <h3 class="caliweb-login-heading"><?php echo $variableDefinitionX->orgShortName; ?> <span style="font-weight:700">Onboarding</span></h3>
                <p style="font-size:12px; margin-top:0%;"><?php echo $LANG_ONBOARD_BILLINGTITLE; ?></p>
            </div>
            <div class="caliweb-login-box-body">
                <form action="/onboarding/requiredLogic/index.php" method="POST" id="caliweb-form-plugin"  class="caliweb-ix-form-login">
                    <div class="caliweb-grid caliweb-two-grid no-grid-row-bottom">
                        <div>

                            <?php

                                if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

                                    if ($variableDefinitionX->paymentProcessorName == "Stripe") {

                                        \Stripe\Stripe::setApiKey($variableDefinitionX->apiKeysecret);
                                            
                                        $customer = \Stripe\Customer::retrieve($stripeID);
                                        $customerData = $customer;
                                        $defaultSourceId = $customerData['default_source'];

                                        if ($defaultSourceId != "") {

                                            echo '<script>window.location.href = "/onboarding/completeOnboarding";</script>';

                                        }

                                        echo '

                                            <div id="card-element" style="padding:10px; background-color:#F8F8F8; border-radius:8px; box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px; border:1px solid #ddd; margin-bottom:10%;"></div>

                                        ';

                                    } else {

                                        header ("location: /error/genericSystemError");

                                    }

                                } else {

                                    echo 'There are no payment modules available to service this request.';

                                }

                            ?>

                        </div>
                        <div>
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
                <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                <!--
                    THIS TEXT IS TO GIVE CREDIT TO THE AUTHORS AND REMOVING IT
                    MAY CAUSE YOUR LICENSE TO BE REVOKED.
                -->
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Services LLC - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                <div class="list-links-footer">
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/terms">Terms of Service</a>
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/clientside.php");
    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Login/Footers/index.php');
?>