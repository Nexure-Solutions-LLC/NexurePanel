<!-- Universal Rounded Floating Cali Web Design Header Bar start -->   
<?php

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    session_start();

    if (!isset($_SESSION["recoveryrequestID"]) || !isset($_SESSION["recoverycode"])) {
        header("location:/resetPassword/");
    }

    $submittedVerificationCode = stripslashes($_SESSION["recoverycode"]);
    $submittedVerificationCode = mysqli_real_escape_string($con, $submittedVerificationCode);

    $local_email = $_SESSION["resetPassswordEmail"];
    $local_email = stripslashes($local_email);
    $local_email = mysqli_real_escape_string($con, $local_email);

    $remoteQuery = "SELECT * FROM `caliweb_recoveryrequests` WHERE email ='".$local_email."' ORDER BY timestamp DESC;";
    $queryExec = mysqli_query($con, $remoteQuery);
    $queryRecItem = mysqli_fetch_array($queryExec);
    $verificationCode = $queryRecItem["recoverycode"];

    if ($verificationCode != $submittedVerificationCode) {
        header("location:/resetPassword/");
    }


    // unset($_SESSION['verification_code']);
    
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

            $newPassword = stripslashes($_REQUEST['newPassword']);
            $newPassword = mysqli_real_escape_string($con, $newPassword);
            $confirmNewPassword = stripslashes($_REQUEST['confirmPassword']);
            $confirmNewPassword = mysqli_real_escape_string($con, $confirmNewPassword);

            if ($newPassword == $confirmNewPassword) {

                $query    = "UPDATE `caliweb_users` SET `password`='".hash("sha512", $newPassword)."' WHERE `email`='$local_email'";
                $result = mysqli_query($con, $query) or die(mysqli_error());

                $removeQuery = "DELETE FROM `caliweb_recoveryrequests` WHERE id = " . $_SESSION["recoveryrequestID"] . " AND recoverycode = '" . $_SESSION["recoverycode"] . "' AND email = '" . $local_email . "';";
                $result = mysqli_query($con, $removeQuery) or die(mysqli_error());

                if ($result) {
                    unset($_SESSION["recoverycode"]);
                    unset($_SESSION["recoveryrequestID"]);
                    header("Location: /resetPassword/passwordChanged");

                }
            
            } else {

                $reset_error = true;

            }

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);

        }  

    }
    
    require($_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Utility/Backend/Login/Headers/index.php");
    
?>
<!-- Universal Rounded Floating Cali Web Design Header Bar End -->

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
                                    <label for="newPassword" class="text-gray-label"><?php echo $RESET_PASSWORD_LABEL_NEW_PASSWORD_TEXT; ?></label>
                                    <input type="password" class="form-input" name="newPassword" id="newPassword" placeholder="" required="" />
                                </div>
                                <div class="form-control">
                                    <label for="confirmPassword" class="text-gray-label"><?php echo $RESET_PASSWORD_LABEL_CONFIRM_NEW_PASSWORD_TEXT; ?></label>
                                    <input type="password" class="form-input" name="confirmPassword" id="confirmPassword" placeholder="" required="" />
                                </div>
                                <?php if (isset($reset_error)): ?>
                                    <div class="caliweb-error-box">
                                        <p class="caliweb-login-sublink" style="font-weight:700; padding-top:0; margin-top:0;"><?php echo $LANG_RESETPASSWORD_ERROR_TITLE; ?></p>
                                        <p class="caliweb-login-sublink" style="font-size:12px;"><?php echo $LANG_RESETPASSWORD_ERROR_TEXT; ?></p>
                                    </div>
                                <?php endif; ?>
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

    include($_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Utility/Backend/Login/Footers/index.php"); 
    
?>