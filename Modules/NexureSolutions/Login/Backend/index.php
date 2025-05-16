<?php

    session_start();

    // Import Files

    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/System/Handlers/index.php');

    // Language Handler

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

    // Variable Definitions

    $accountnumberlength = $_ENV["ACCOUNTNUMBERLENGTH"];

    // Middleware Calls

    $VariableDefinitionHandler = new \NexureSolutions\Generic\VariableDefinitions();
    $VariableDefinitionHandler->GatherPanelConfiguration($con);

    if (isset($_POST['langPreference'])) {

        $_SESSION["lang"] = $_POST['langPreference'];

    }

?>