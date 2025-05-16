<?php

    // Import Files

    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/System/Handlers/index.php');

    ob_clean();
    ob_start();

    // Plugin Imports

    use GuzzleHttp\Client;
    use IPLib\Factory;
    use Detection\MobileDetect;
    use Stripe\Stripe;

    // Sentry Setup

    \Sentry\init([
        'dsn' => $_ENV['SENTRY_DSN'],
        'traces_sample_rate' => 1.0,
        'profiles_sample_rate' => 1.0,
    ]);

    // Variable Definitions

    $nexureid = $_SESSION['nexureid'];
    $sentryToken = $_ENV['SENTRY_TOKEN'];
    $sentryOrg = $_ENV['SENTRY_ORG'];
    $sentryProject = $_ENV['SENTRY_PROJECT'];
    $accountnumberlength = $_ENV["ACCOUNTNUMBERLENGTH"];

    // Middleware Calls

    $VariableDefinitionHandler = new \NexureSolutions\Generic\VariableDefinitions();
    $VariableDefinitionHandler->GatherOnlineAccessInformation($con, $nexureid);
    $VariableDefinitionHandler->GatherUserAccounts($con, $nexureid);
    $VariableDefinitionHandler->GatherOnlineAccessInformation($con, $nexureid);
    $VariableDefinitionHandler->GatherPanelConfiguration($con);
    $VariableDefinitionHandler->loadRiskScore($con, $nexureid);

    use NexureSolutions\Modules\NexureModules;

    $NexureModuleHandler = new NexureModules();
    $NexureModuleHandler->retrieveModules($con);


    $account = !empty($VariableDefinitionHandler->userAccounts) ? $VariableDefinitionHandler->userAccounts[0] : null;

    // Mobile Detection

    $detect = new MobileDetect();

    if ($detect->isMobile() || $detect->isTablet()) {

        header("Location: /ErrorHandling/ErrorPages/MobileExperience/");

        exit();

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

    if (isset($_POST['langPreference'])) {

        $_SESSION["lang"] = $_POST['langPreference'];

    }

?>