<?php

    // Cali Web Design Panel Installer
    // Version 20.0.1
    // Copyright Cali Web Design Services LLC (C) 2024. All Rights Reserved.
    // This is the installer for the Cali Panel Web Software, It will configure
    // the panel, install dependencies, and setup the database and tables.
    // This installer is opesource under the same license as the panel.

    
    session_start();

    function errorHandler($errno, $errstr, $errfile, $errline) {

        $log_timestamp = date("d-m-Y H:i:sa");
        $errorMessage = "Error: [$errno] $errstr in $errfile on line $errline";
        $errorLogFile = $_SERVER["DOCUMENT_ROOT"] . "/error/errorLogs/$log_timestamp.log";

        error_log($errorMessage, 3, $errorLogFile);
        session_start();
        $_SESSION['error_log_file'] = $errorLogFile;

        while (ob_get_level()) {

            ob_end_clean();

        }

        if (headers_sent()) {

            echo '<meta http-equiv="refresh" content="0;url=/error/genericSystemError/">';

        } else {

            header("Location: /error/genericSystemError/");

        }

        exit;

    }

    set_error_handler("errorHandler");

    $error = error_get_last();

    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING])) {

        customErrorHandler($error['type'], $error['message'], $error['file'], $error['line']);

    }

    function isSelectedLang($lang_name) {

        $langPreference = "en_US";

        if (isset($_SESSION["lang"])) {

            $langPreference = $_SESSION["lang"];

        }

        if ($langPreference == $lang_name) {

            return 'selected';

        } else {

            return '';

        }

    }

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    use Dotenv\Dotenv;

    // Load environment variables from .env file

    $dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
    $dotenv->load();

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    // Check Language

    if (isset($_POST['langPreference'])) {

        $_SESSION["lang"] = $_POST['langPreference'];

    }

    // Initalize Sentry

    \Sentry\init([
        'dsn' => $_ENV['SENTRY_DSN'],
        'traces_sample_rate' => 1.0,
        'profiles_sample_rate' => 1.0,
    ]);

    // Perform query

    $result = mysqli_query($con, "SELECT * FROM caliweb_panelconfig WHERE id = '1'");
    $panelinfo = mysqli_fetch_array($result);

    // Free result set

    mysqli_free_result($result);

    $variableDefinitionX->panelName = $panelinfo['panelName'];
    $variableDefinitionX->paneldomain = $panelinfo['panelDomain'];
    $variableDefinitionX->orgShortName = $panelinfo['organizationShortName'];
    $variableDefinitionX->orglogolight = $panelinfo['organizationLogoLight'];
    $variableDefinitionX->orglogodark = $panelinfo['organizationLogoDark'];
    $variableDefinitionX->orglogosquare = $panelinfo['organizationLogoSquare'];
    $variableDefinitionX->licenseKeyfromDB = $panelinfo['panelKey'];

?>

    <head>
        <title>Cali Panel Installer 20.0.1</title>
        <script src="https://caliwebdesignservices.com/assets/js/darkmode.js" type="text/javascript"></script>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="author" content="Cali Web Design Development Team, Nick Derry, Michael Brinkley">
        <link href="https://caliwebdesignservices.com/assets/css/2024-01-29-styling.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/login-css-2024.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="apple-touch-icon" sizes="180x180" href="https://caliwebdesignservices.com/assets/img/favico/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://caliwebdesignservices.com/assets/img/favico/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="https://caliwebdesignservices.com/assets/img/favico/favicon-16x16.png">
        <link rel="manifest" href="https://caliwebdesignservices.com/assets/img/favico/site.webmanifest">
        <script type="text/javascript">   
            window.antiFlicker = {
                active: true,
                timeout: 3000
            }           
        </script>
        <style id="no-bg-img">*{background-image:none!important /* Remove Background Images until the DOM Loads */}</style>
    </head>

    <section class="login-container" style="height:100%; overflow:hidden;">
        <div class="caliweb-login-box login-only" style="max-width:35%; margin-top:6%;">
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
                        <h3 style="font-size:20px;">Installer</h3>
                        <p style="font-size:14px;">This is the Cali Installer.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="caliweb-login-footer">
        <div class="container caliweb-container">
            <div class="caliweb-grid-2">
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Services LLC - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <div class="list-links-footer">
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/terms">Terms of Service</a>
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

<?php 

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Login.php'); 

?>