<?php

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
    require($_SERVER["DOCUMENT_ROOT"] . "/components/CaliUtilities/VariableDefinitions.php");

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

    // Initalize the variable class and function from Cali Utilities 

    $variableDefinitionX = new \CaliUtilities\VariableDefinitions();
    $variableDefinitionX->variablesHeader($con);

    $passableUserId = $variableDefinitionX->userId;
    $passableApiKey = $variableDefinitionX->apiKey;

    if ($variableDefinitionX->licenseKeyfromConfig == $variableDefinitionX->licenseKeyfromDB) {

?>
        <!DOCTYPE html>
        <!-- 
            ______      ___    _       __     __       ____            _           
            / ____/___ _/ (_)  | |     / /__  / /_     / __ \___  _____(_)___ _____ 
            / /   / __ `/ / /   | | /| / / _ \/ __ \   / / / / _ \/ ___/ / __ `/ __ \
            / /___/ /_/ / / /    | |/ |/ /  __/ /_/ /  / /_/ /  __(__  ) / /_/ / / / /
            \____/\__,_/_/_/     |__/|__/\___/_.___/  /_____/\___/____/_/\__, /_/ /_/ 
                                                                        /____/        

            This site was created by Cali Web Design Services LLC. http://www.caliwebdesignservices.com
            Last Published: Fri Feb 2 2024 at 08:38:52 PM (Pacific Daylight Time US and Canada) 

            Creator/Developer: Cali Web Design Development Team, Michael Brinkley, Nick Derry, All logos other 
            than ours go to their respectful owners. Images are provided by undraw.co as well as pexels.com 
            and are opensource svgs, or images to use by Cali Web Design Services LLC.

            Website Registration Code: 49503994-20344
            Registration Date: July 09 2022
            Initial Development On: Jun 30 2023
            Last Update: Feb 2th 2024
            Website Version: 20.0.0
            Expiration Date: 02/19/2088 (LTSB Long-Term Servicing Branch)
            Contact Information:
                Phone: +1-877-597-7325
                Email: support@caliwebdesignservices.com

            Copyright Statement: Do not copy this website, if the code is found to be duplicated, reproduced,
            or copied we will fine you a minimum of $250,000 and criminal charges may be pressed.

            CopyOurCodeWeWillSendYouToJesus(C)2024ThisIsOurHardWork.

            Dear rule breakers, questioners, straight-A students who skipped class: We want you.
            https://caliwebdesignservices.com/careers.
            

        -->
        <html lang="en-us">
            <head>
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
                <?php include($_SERVER["DOCUMENT_ROOT"]."/dashboard/company/themes/index.php"); ?>

                <script type="text/javascript">   
                    window.antiFlicker = {
                        active: true,
                        timeout: 3000
                    }           
                </script>
                <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
                <script src="https://js.stripe.com/v3/"></script>
                <style id="no-bg-img">*{background-image:none!important /* Remove Background Images until the DOM Loads */}</style>
            </head>
            <body>
                <div class="caliweb-navbar" id="caliweb-header">
                    <div class="container caliweb-navbar-container">
                        <div class="caliweb-navbar-logo">
                        
                        </div>
                        <div>

                        </div>
                        <div style="display:flex; align-items:center;">
                            <nav class="caliweb-navbar-menu" id="caliweb-navigation">
                                
                            </nav>
                            <form action="" method="POST">
                                <div class="form-control" style="">
                                    <select type="text" class="form-input" style="padding:6px 10px" name="langPreference" id="langPreference" required="" onchange="this.form.submit()">
                                        <option value="en_US" <?php echo isSelectedLang("en_US"); ?>>English</option>
                                        <option value="es_es" <?php echo isSelectedLang("es_es"); ?>>Spanish</option>
                                    </select>
                                </div>
                            </form>
                            <span class="toggle-container">
                                <span class="lnr lnr-sun" class="toggle-input" id="lightModeIcon"></span>
                                <span class="lnr lnr-moon"  class="toggle-input" id="darkModeIcon"></span>
                            </span>
                        </div>
                    </div>
                </div>
<?php 
    
        if (isset($_SESSION["lang"])) {

            if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/lang/'.$_SESSION["lang"].'.php')) {

                $_SESSION["lang"] = 'en_US';

            }
            include($_SERVER["DOCUMENT_ROOT"].'/lang/'.$_SESSION["lang"].'.php');

        } else {

            include($_SERVER["DOCUMENT_ROOT"]."/lang/en_US.php");

        }

    } else {

    header("Location: /error/licenseInvalid");
    
} ?>