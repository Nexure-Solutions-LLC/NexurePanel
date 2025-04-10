<?php

    session_start();

    $pagetitle = "Login Page";
    $_SESSION['pagetitle'] = $pagetitle;

    $referral_url = $_GET["referral_url"];

    if (isset($_SESSION['caliid'])) {

        header("Location: /dashboard");
        
        exit;

    }

    if (isset($_POST['emailaddress'])) {

        require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');

        try {

            $cali_id = stripslashes($_REQUEST['emailaddress']);

            $cali_id = mysqli_real_escape_string($con, $cali_id);

            $password = stripslashes($_REQUEST['password']);

            $password = mysqli_real_escape_string($con, $password);

            $client_ip = $_SERVER['REMOTE_ADDR'];

            $query = "SELECT * FROM `nexure_users` WHERE `email` = '$cali_id' AND `password` = '" . hash("sha512", $password) . "'";

            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) == 1) {

                if ($referral_url != "") {

                    unset($_SESSION['failed_attempts']);

                    $_SESSION['referral_url'] = $referral_url;
                    $_SESSION['caliid'] = $cali_id;

                    header("Location: /dashboard");

                    exit;

                } else {

                    unset($_SESSION['failed_attempts']);

                    $_SESSION['caliid'] = $cali_id;

                    header("Location: /dashboard");

                    exit;

                }

            } else {

                if (!isset($_SESSION['failed_attempts'])) {

                    $_SESSION['failed_attempts'] = 0;
                }

                $_SESSION['failed_attempts']++;

                if ($_SESSION['failed_attempts'] > 5) {

                    $ban_query = "INSERT INTO `nexure_networks` (`ipAddress`, `listType`) VALUES ('$client_ip', 'Blacklist')";

                    mysqli_query($con, $ban_query);

                    header("location: /error/bannedUser");
                }


                $login_error = true;
            }
        } catch (\Throwable $exception) {

            \Sentry\captureException($exception);
        }
    }

    require($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/Login/Headers/index.php");

    echo '<title>'.$variableDefinitionX->orgShortName.' | Unified Portal</title>';

?>

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
                                <label for="emailaddress" class="text-gray-label"><?php echo $variableDefinitionX->orgShortName; ?> ID:</label>
                                <input type="email" class="form-input" name="emailaddress" id="emailaddress" placeholder="" required="" />
                            </div>
                            <div class="form-control">
                                <label for="password" class="text-gray-label"><?php echo $LANG_LOGIN_PASSWORD ?></label>
                                <input type="password" class="form-input" name="password" id="password" placeholder="" />
                            </div>
                            <div class="form-control">
                                <?php
                                    $loginModulesLookupQuery = "SELECT * FROM nexure_modules WHERE moduleStatus = 'Active' AND modulePositionType = 'Authentication'";
                                    $loginModulesLookupResult = mysqli_query($con, $loginModulesLookupQuery);

                                    if (mysqli_num_rows($loginModulesLookupResult) > 0) {
                                        while ($loginModulesLookupRow = mysqli_fetch_assoc($loginModulesLookupResult)) {
                                            $loginModulesName = $loginModulesLookupRow['moduleName'];

                                            if ($loginModulesName == "Nexure OAuth") {

                                                include($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Oauth//index.php");

                                            }
                                        }
                                    }

                                ?>
                                <button class="caliweb-button primary" type="submit" name="submit"><?php echo $LANG_LOGIN_BUTTON; ?></button>
                            </div>
                            <?php if (isset($login_error)): ?>
                                <div class="caliweb-error-box">
                                    <p class="caliweb-login-sublink" style="font-weight:700; padding-top:0; margin-top:0;"><?php echo $LANG_LOGIN_AUTH_ERROR_TITLE; ?></p>
                                    <p class="caliweb-login-sublink" style="font-size:12px;"><?php echo $LANG_LOGIN_AUTH_ERROR_TEXT; ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="caliweb-horizantal-spacer mt-5-per"></div>
                            <div class="form-control mt-5-per" style="margin-bottom:0;">
                                <div class="caliweb-two-grid must-be-grid">
                                    <div class="text-left">
                                        <a href="/resetPassword" class="careers-link" style="font-size:12px;">Forgot password?</a>
                                    </div>
                                    <div class="text-right">
                                        <a href="/registration" class="careers-link" style="font-size:12px;">Register Here</a>
                                    </div>
                                </div>
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
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; <span id="nexure-year"></span> - Nexure Solutions LLP - All rights reserved. It is illegal to copy this website.</p>
                </div>
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
