<?php
require($_SERVER["DOCUMENT_ROOT"] . '/lang/en_US.php');
require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require($_SERVER["DOCUMENT_ROOT"] . "/modules/NexureSolutions/Utility/Backend/index.php");
require($_SERVER["DOCUMENT_ROOT"] . "/license/index.php");

session_start();

function errorHandler($errno, $errstr, $errfile, $errline)
{

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

function isSelectedLang($lang_name)
{

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

use Dotenv\Dotenv;

// Initalize Sentry

\Sentry\init([
    'dsn' => $_ENV['SENTRY_DSN'],
    'traces_sample_rate' => 1.0,
    'profiles_sample_rate' => 1.0,
]);

// Load environment variables from .env file

$dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

// Initalize the variable class and function from Cali Utilities

$variableDefinitionX = new \NexureSolutions\Generic\VariableDefinitions();
$variableDefinitionX->variablesHeader($con);

// Check Language

if (isset($_POST['langPreference'])) {

    $_SESSION["lang"] = $_POST['langPreference'];
}

?>
<!-- Universal Rounded Floating Cali Web Design Header Bar start -->
<html lang="en-us">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="Cali Web Design Development Team, Nick Derry, Michael Brinkley">
    <link href="https://NexureSolutionsservices.com/assets/css/2024-01-29-styling.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/login-css-2024.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="apple-touch-icon" sizes="180x180" href="https://NexureSolutionsservices.com/assets/img/favico/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://NexureSolutionsservices.com/assets/img/favico/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://NexureSolutionsservices.com/assets/img/favico/favicon-16x16.png">
    <link rel="manifest" href="https://NexureSolutionsservices.com/assets/img/favico/site.webmanifest">
    <?php include($_SERVER["DOCUMENT_ROOT"] . "/dashboard/company/themes/index.php"); ?>

    <script type="text/javascript">
        window.antiFlicker = {
            active: true,
            timeout: 3000
        }
    </script>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    <script src="https://js.stripe.com/v3/"></script>
    <style id="no-bg-img">
        * {
            background-image: none !important
                /* Remove Background Images until the DOM Loads */
        }
    </style>
    <style>
        body {
            overflow: hidden;
        }
    </style>
</head>

<body>
    <?php
    ob_start();
    include($_SERVER["DOCUMENT_ROOT"] . "/lang/en_US.php");
    ?>
    <!-- 
                Unique Website Title Tag Start 
                The Page Title specified what page the user is on in 
                the browser tab and should be included for SEO
            -->
    <title><?php echo $variableDefinitionX->orgShortName; ?> - License Error</title>
    <!-- Unique Website Title Tag End -->

    <section class="section" style="padding-top:10%; padding-left:5%;">
        <div class="container caliweb-container">
            <h3 class="caliweb-login-heading license-text-dark"><?php echo $LANG_LICENSE_TITLE_PAR_1; ?> <span style="font-weight:700;"><?php echo $LANG_LICENSE_TITLE_PAR_2; ?></span></h3>
            <p class="caliweb-login-sublink license-text-dark" style="font-weight:700; padding-top:0; margin-top:0;"><?php echo $LANG_LICENSE_TITLE; ?></p>
            <p class="caliweb-login-sublink license-text-dark width-50"><?php echo $LANG_LICENSE_TEXT; ?></p>
        </div>
    </section>

    <div class="caliweb-login-footer license-footer">
        <div class="container caliweb-container">
            <div class="caliweb-grid-2">
                <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                <!-- 
                            THIS TEXT IS TO GIVE CREDIT TO THE AUTHORS AND REMOVING IT
                            MAY CAUSE YOUR LICENSE TO BE REVOKED.
                        -->
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; 2024 - Nexure Solutions LLC - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                <div class="list-links-footer">
                    <a href="https://NexureSolutionsservices.com/terms">Terms of Service</a>
                    <a href="https://NexureSolutionsservices.com/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://NexureSolutionsservices.com/assets/js/index.js"></script>
</body>

</html>