<?php

    session_start();

    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Configuration/EnvironmentFile/index.php');
    require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Configuration/Database/index.php');

    function errorHandler($errno, $errstr, $errfile, $errline) {

        $log_timestamp = date("d-m-Y H:i:sa");
        $errorMessage = "Error: [$errno] $errstr in $errfile on line $errline";
        $errorLogFile = $_SERVER["DOCUMENT_ROOT"] . "/ErrorHandling/Logs/$log_timestamp.log";

        error_log($errorMessage, 3, $errorLogFile);
        session_start();
        $_SESSION['error_log_file'] = $errorLogFile;

        while (ob_get_level()) {

            ob_end_clean();

        }

        if (headers_sent()) {

            echo '<meta http-equiv="refresh" content="0;url=/ErrorHandling/ErrorPages/GenericError">';

        } else {

            header("Location: /ErrorHandling/ErrorPages/GenericError");

        }

        exit;

    }

    set_error_handler("errorHandler");

    $error = error_get_last();

    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING])) {

        customErrorHandler($error['type'], $error['message'], $error['file'], $error['line']);

    }

    function isSelectedLang($lang_name) {

        $langPreference = "EN_US";

        if (isset($_SESSION["lang"])) {

            $langPreference = $_SESSION["lang"];

        }

        if ($langPreference == $lang_name) {

            return 'selected';

        } else {

            return '';

        }

    }

    // Initalize Sentry

    \Sentry\init([
        'dsn' => $_ENV['SENTRY_DSN'],
        'traces_sample_rate' => 1.0,
        'profiles_sample_rate' => 1.0,
    ]);

    $accountnumberlength = $_ENV["ACCOUNTNUMBERLENGTH"];

    if (isset($_POST['langPreference'])) {

        $_SESSION["lang"] = $_POST['langPreference'];

    }

?>