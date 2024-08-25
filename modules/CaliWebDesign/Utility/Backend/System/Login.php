<?php

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/modules/CaliWebDesign/Utility/Backend/index.php");

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

    $variableDefinitionX = new \CaliWebDesign\Generic\VariableDefinitions();
    $variableDefinitionX->variablesHeader($con);

    // Check Language

    if (isset($_POST['langPreference'])) {

        $_SESSION["lang"] = $_POST['langPreference'];

    }

?>