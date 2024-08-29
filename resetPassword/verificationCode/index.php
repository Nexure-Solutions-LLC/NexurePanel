<!-- Universal Rounded Floating Cali Web Design Header Bar start -->   
<?php

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    session_start();

    if (isset($_GET["submittedCode"])) {

        try {

            $submittedVerificationCode = stripslashes($_GET["submittedCode"]);
            $submittedVerificationCode = mysqli_real_escape_string($con, $submittedVerificationCode);

            $local_email = $_SESSION["resetPassswordEmail"];
            $local_email = stripslashes($local_email);
            $local_email = mysqli_real_escape_string($con, $local_email);
            $remoteQuery = "SELECT * FROM `caliweb_recoveryrequests` WHERE email ='".$local_email."' ORDER BY timestamp DESC;";
            $queryExec = mysqli_query($con, $remoteQuery);
            $queryRecItem = mysqli_fetch_array($queryExec);
            $verificationCode = $queryRecItem["recoverycode"];


            if ($submittedVerificationCode == $verificationCode) {

                $_SESSION["recoverycode"] = $verificationCode;
                $_SESSION["recoveryrequestID"] = $queryRecItem["id"];
                header("Location: /resetPassword/changePassword");
                
            }

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
            
        }

    }

    include($_SERVER["DOCUMENT_ROOT"] . "/modules/CaliWebDesign/Utility/Backend/Login/Headers/index.php")
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

                            <label class="text-gray-label"><?php echo $LANG_RESET_PASSWORD_STAGE_2_TEXT; ?></label>
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