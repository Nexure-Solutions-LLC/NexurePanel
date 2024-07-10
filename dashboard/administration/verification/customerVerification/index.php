<?php
    session_start();

    include($_SERVER["DOCUMENT_ROOT"]."/assets/php/loginHeader.php");
    include($_SERVER["DOCUMENT_ROOT"]."/lang/en_US.php");

    use Twilio\Rest\Client;

    $accountnumber = $_GET['account_number'];
    $passorginURL = "/dashboard/administration/accounts/manageAccount/?account_number=$accountnumber";
    $sid    = $_ENV['TWILLIOAPISID'];
    $token  = $_ENV['TWILLIOAPITOKEN'];
    $twilio = new Client($sid, $token);
    $customerAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '".$accountnumber."'");
    $customerAccountInfo = mysqli_fetch_array($customerAccountQuery);
    mysqli_free_result($customerAccountQuery);

    if ($customerAccountInfo != NULL) {

        $customerSystemID = $customerAccountInfo['id'];
        $customerPhoneNumber = $customerAccountInfo['mobileNumber'];
        $customerStatus = $customerAccountInfo['accountStatus'];
        $dbaccountnumber = $customerAccountInfo['accountNumber'];

        if ($accountnumber != $dbaccountnumber) {

            echo '<script>window.location.href = "/dashboard/administration/accounts";</script>';

        }

    } else {

        echo '<script>window.location.href = "/dashboard/administration/accounts";</script>';

    }

    function isValidPhoneNumber($customerPhoneNumber) {

        return preg_match('/^1\d{10}$/', $customerPhoneNumber);

    }

    function sendVerificationCode($customerPhoneNumber) {

        global $twilio;
        $verificationCode = rand(100000, 999999);

        $_SESSION['verification_code'] = $verificationCode;

        try {

            $message = $twilio->messages->create(
                $customerPhoneNumber,
                array(
                    "from" => $_ENV['TWILLIONUMBER'],
                    "body" => "[CALI WEB DESIGN FREE MSG]: Thank you for calling Cali Web Design. Your code is: $verificationCode. Remember: Cali Web Design will not call or text you to ask for this code."
                )
            );

            return $message->sid;

        } catch (Exception $e) {

            // If message sending fails, handle the exception (e.g., log it).
            return false;

        }
    }

    echo '<title>'.$orgshortname.' - Security Quiz</title>';
    echo '<style></style>';

    echo '<section class="section" style="padding-top:5%; padding-left:0%; padding-bottom:8%;">
            <div class="container caliweb-container" style="width:60%;">
                <h3 class="caliweb-login-heading license-text-dark">'.$LANG_CUSTOMER_VERIFICATION_TITLE_PAR_1.' <span style="font-weight:700;">'.$LANG_CUSTOMER_VERIFICATION_TITLE_PAR_2.'</span></h3>
                <p class="caliweb-login-sublink license-text-dark" style="font-weight:700; padding-top:0; margin-top:0;">'.$LANG_CUSTOMER_VERIFICATION_TITLE.'</p>
        ';

    if ($customerPhoneNumber != NULL && $customerPhoneNumber != "" && isValidPhoneNumber($customerPhoneNumber)) {

        // Attempt to send verification code
        $messageSid = sendVerificationCode($customerPhoneNumber);

        if ($messageSid) {

            echo '
                <p class="caliweb-login-sublink license-text-dark width-50">'.$LANG_CUSTOMER_VERIFICATION_TEXT_PHONE_VERIFY.'</p>

                <div style="margin-top:2%;">
                    <form action="/dashboard/administration/verification/customerVerification/callback/?account_number='.$accountnumber.'" method="POST" class="caliweb-form-plugin" id="verificationForm">
                        <input class="form-input width-25" name="verification_code" id="verification_code" type="text" placeholder="123456" />
                </div>
            ';

        } else {

            // If message sending failed, fallback to security questions
            echo '
                <p class="caliweb-login-sublink license-text-dark width-50">'.$LANG_CUSTOMER_VERIFICATION_TEXT.'</p>
                <form action="/dashboard/administration/verification/customerVerification/callback/?account_number='.$accountnumber.'" method="POST" class="caliweb-form-plugin" id="verificationForm">
                    <div style="margin-top:4%;">
                        <p>In which state was your social security number issued in?</p>
                        <div style="display:flex; align-items:center; margin-top:2%;">
                            <input type="radio" name="securityquiz1" id="securityquiz1" />
                            <p>New York</p>
                        </div>
                        <div style="display:flex; align-items:center; margin-top:10px;">
                            <input type="radio" name="securityquiz1" id="securityquiz1" />
                            <p>Florida</p>
                        </div>
                        <div style="display:flex; align-items:center; margin-top:10px;">
                            <input type="radio" name="securityquiz1" id="securityquiz1" />
                            <p>North Dakota</p>
                        </div>
                        <div style="display:flex; align-items:center; margin-top:10px;">
                            <input type="radio" name="securityquiz1" id="securityquiz1" />
                            <p>California</p>
                        </div>
                    </div>
            ';

        }
        
    } else {

        echo '
            <p class="caliweb-login-sublink license-text-dark width-50">'.$LANG_CUSTOMER_VERIFICATION_TEXT.'</p>
            <form action="/dashboard/administration/verification/customerVerification/callback/?account_number='.$accountnumber.'" method="POST" class="caliweb-form-plugin" id="verificationForm">
                <div style="margin-top:4%;">
                    <p>In which state was your social security number issued in?</p>
                    <div style="display:flex; align-items:center; margin-top:2%;">
                        <input type="radio" name="securityquiz1" id="securityquiz1" />
                        <p>New York</p>
                    </div>
                    <div style="display:flex; align-items:center; margin-top:10px;">
                        <input type="radio" name="securityquiz1" id="securityquiz1" />
                        <p>Florida</p>
                    </div>
                    <div style="display:flex; align-items:center; margin-top:10px;">
                        <input type="radio" name="securityquiz1" id="securityquiz1" />
                        <p>North Dakota</p>
                    </div>
                    <div style="display:flex; align-items:center; margin-top:10px;">
                        <input type="radio" name="securityquiz1" id="securityquiz1" />
                        <p>California</p>
                    </div>
                </div>
        ';
        
    }

    echo '
            <div style="margin-top:4%; float:right;">
                <button name="submit" type="submit" class="caliweb-button primary" style="padding:8px 24px;">Submit</button>
            </div>
        </form>
        </div>
    </section>
    ';

    echo '<div class="caliweb-login-footer license-footer">
            <div class="container caliweb-container">
                <div class="caliweb-grid-2">
                    <div class="">
                        <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Corporation - All rights reserved. It is illegal to copy this website.</p>
                    </div>
                    <div class="list-links-footer">
                        <a href="'.$paneldomain.'/terms">Terms of Service</a>
                        <a href="'.$paneldomain.'/privacy">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>
    ';

    echo '
        <div class="preloader">
            <div style="margin-left:auto;margin-right:auto;max-width:80%;">
                <div class="logo" style="margin-top:-6%;">
                    <img src="https://caliwebdesignservices.com/assets/img/logos/CaliWebDesign-Logo.svg" width="150px" loading="lazy" alt="Cali Web Design Logo" class="caliweb-navbar-logo-img light-mode" style="width:150px;">
                    <img src="https://caliwebdesignservices.com/assets/img/logos/CaliWebDesign-WhiteLogo.svg" width="150px" loading="lazy" alt="Cali Web Design Dark Logo" class="caliweb-navbar-logo-img dark-mode" style="width:150px;">
                </div>
                <div style="margin-top:6%;" class="loading-bar">
                    <div class="loading-bar-inner"></div>
                </div>
            </div>
        </div>

        <script>
            window.addEventListener("load", function() {const preloader = document.querySelector(".preloader");setTimeout(function() {preloader.classList.add("loaded");}, 1);});
        </script>
    ';

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/loginFooter.php');
?>