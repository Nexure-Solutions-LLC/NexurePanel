<!-- Universal Rounded Floating Nexure Header Bar start -->   
<?php
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    session_start();

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

            $cali_id = stripslashes($_REQUEST['emailAddress']);
            $cali_id = mysqli_real_escape_string($con, $cali_id);
            
            // Check user is exist in the database

            $query    = "SELECT * FROM `nexure_users` WHERE `email` = '$cali_id'";
            $result = mysqli_query($con, $query) or die(mysqli_error());
            $rows = mysqli_num_rows($result);

            $verificationCode = rand(1000000000000000, 9999999999999999);
            $newQuery = "INSERT INTO `nexure_recoveryrequests` (email, recoverycode, timestamp) VALUES ('".$cali_id."', '".$verificationCode."',". time() .")";
            $result = mysqli_query($con, $newQuery) or die(mysqli_error());

            $_SESSION["verification_code"] = $verificationCode;
            $_SESSION['resetPassswordEmail'] = $cali_id;
            if ($rows != 0) {
                include($_SERVER["DOCUMENT_ROOT"]."/resetPassword/sendEmailLogic/index.php");
            }
            header("location:/resetPassword/verificationCode");

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
            
        }

    }
    
    require($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/Login/Headers/index.php");

    ob_start();
    
?>
<!-- Universal Rounded Floating Nexure Header Bar End -->

    <!-- 
        Unique Website Title Tag Start 
        The Page Title specified what page the user is on in 
        the browser tab and should be included for SEO
    -->
        <title><?php echo $variableDefinitionX->orgShortName; ?> - Unified Portal</title>
    <!-- Unique Website Title Tag End -->

        <section class="login-container">
            <div class="caliweb-login-box login-only">
                <div class="container caliweb-container">
                    <div class="caliweb-login-box-header">
                        <h3 class="caliweb-login-heading">
                            <a href="<?php echo $variableDefinitionX->paneldomain; ?>">
                                <img src="<?php echo $variableDefinitionX->orglogosquare; ?>" width="72px" height="70px" loading="lazy" alt="<?php echo $variableDefinitionX->panelName; ?> Logo" class="login-box-logo-header">
                            </a>
                        </h3>
                    </div>
                    <div class="caliweb-login-box-content">
                        <div class="caliweb-login-box-body">
                            <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
                                <div class="form-control">
                                    <label for="emailAddress" class="text-gray-label"><?php echo $RESET_PASSWORD_LABEL_EMAIL_TEXT; ?></label>
                                    <input type="email" class="form-input" name="emailAddress" id="emailAddress" placeholder="" required="" />
                                </div>
                                <div class="form-control">
                                    <button class="caliweb-button primary" type="submit" name="submit"><?php echo $LANG_LOGIN_BUTTON; ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                        <p class="caliweb-login-footer-text">&copy; <span id="nexure-year"></span> - Nexure Solutions LLC - All rights reserved. It is illegal to copy this website.</p>
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

    include($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/Login/Footers/index.php"); 
    
?>