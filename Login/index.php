<?php

    session_start();

    $PageTitle = "Login";

    $referral_url = $_GET["referral_url"];

    if (isset($_SESSION['nexureid'])) {

        header("Location: /Dashboard");
        
        exit;

    }

    if (isset($_POST['nexureid'])) {

        require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Configuration/Database/index.php');

        try {

            $nexureid = stripslashes($_REQUEST['nexureid']);

            $nexureid = mysqli_real_escape_string($con, $nexureid);

            $password = stripslashes($_REQUEST['password']);

            $password = mysqli_real_escape_string($con, $password);

            $client_ip = $_SERVER['REMOTE_ADDR'];

            $query = "SELECT * FROM `nexure_users` WHERE `email` = '$nexureid' AND `password` = '" . hash("sha512", $password) . "'";

            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) == 1) {

                if ($referral_url != "") {

                    unset($_SESSION['failed_attempts']);

                    $_SESSION['referral_url'] = $referral_url;
                    $_SESSION['nexureid'] = $nexureid;

                    header("Location: /Dashboard");

                    exit;

                } else {

                    unset($_SESSION['failed_attempts']);

                    $_SESSION['nexureid'] = $nexureid;

                    header("Location: /Dashboard");

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

                    header("location: /ErrorHandling/ErrorPages/BannedUser");
                }


                $login_error = true;
            }
        } catch (\Throwable $exception) {

            \Sentry\captureException($exception);

        }
    }

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Headers/index.php");

?>

        <title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | <?php echo $PageTitle; ?></title>

        <!-- Login Page main content area -->

        <div class="nexure-login-section">
            <div class="nexure-login-content height-100">
                <div class="container nexure-container">
                    <div class="logo-area margin-bottom-30px">
                        <img src="<?php echo $VariableDefinitionHandler->organizationSquareLogo; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo square-logo light-mode">
                        <img src="<?php echo $VariableDefinitionHandler->organizationSquareLogo; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo square-logo dark-mode">
                    </div>
                    <form action="" method="POST" class="nexure-login-form" id="nexure-form-plugin">
                        <div class="form-control margin-top-10px">
                            <label for="nexureid" class="font-12px"><?php echo $VariableDefinitionHandler->organizationShortName; ?> ID</label><br>
                            <input class="nexure-textbox width-100" name="nexureid" id="nexureid" type="email" placeholder="me@example.com" />
                        </div>
                        <div class="form-control">
                            <label for="password" class="font-12px"><?php echo $LANG_LOGIN_PASSWORD; ?></label><br>
                            <input class="nexure-textbox width-100" name="password" id="password" type="password" placeholder="Super Secret Password" />
                        </div>
                        <div class="form-control margin-top-20px">
                            <button type="submit" name="submit" class="nexure-button primary width-100"><?php echo $LANG_LOGIN_BUTTON; ?></button>
                        </div>
                    </form>
                    <?php if (isset($login_error)): ?>
                        <div class="nexure-error-box">
                            <p class="nexure-login-sublink" style="font-weight:700; padding-top:0; margin-top:0;"><?php echo $LANG_LOGIN_AUTH_ERROR_TITLE; ?></p>
                            <p class="nexure-login-sublink" style="font-size:12px;"><?php echo $LANG_LOGIN_AUTH_ERROR_TEXT; ?></p>
                        </div>
                    <?php endif; ?>
                    <div class="after-login-area display-flex align-center justify-content-space-between margin-top-30px margin-bottom-10px padding-top-10px">
                        <p class="margin-top-10px gray-100 font-12px">Forgot Login? <a class="brand-link font-12px" href="/ResetPassword">Reset it</a>.</a>
                        <p class="margin-top-10px gray-100 font-12px">Not enrolled? <a class="brand-link font-12px" href="/Register">Register Now</a>.</a>
                    </div>
                </div>
            </div>
        </div>

<?php 
    
    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Footers/index.php"); 
    
?>