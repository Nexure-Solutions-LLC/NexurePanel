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
    $VariableDefinitionHandler->GatherPanelConfiguration($con);

    $NexureModuleHandler = new \NexureSolutions\Modules\NexureModules;
    $NexureModuleHandler->retrieveModules($con);

    $CurrentOnlineAccessAccount =  new \NexureSolutions\Accounts\AccountHandler($con);
    $CurrentOnlineAccessAccount->GatherOnlineAccessInformation($con, $nexureid);
    $CurrentOnlineAccessAccount->GatherUserAccounts($con, $nexureid);
    $CurrentOnlineAccessAccount->loadRiskScore($con, $nexureid);

    $account = !empty($CurrentOnlineAccessAccount->userAccounts) ? $CurrentOnlineAccessAccount->userAccounts[0] : null;

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

    $redirectMap = [
        "Client" => [
            "authorized user" => "/Dashboard/customers/authorizedUserView",
            "partner" => "/Dashboard/Partners",
            "administrator" => "/Dashboard/administration"
        ],
        "Administration" => [
            "authorized user" => "/Dashboard/Customers/AuthorizedUser",
            "partner" => "/Dashboard/Partners",
            "customer" => "/Dashboard/Customers"
        ],
        "Authorized User" => [
            "customer" => "/Dashboard/Customers",
            "partner" => "/Dashboard/Partners",
            "administrator" => "/Dashboard/Administration"
        ],
        "Partners" => [
            "authorized user" => "/Dashboard/Customers/AuthorizedUserView",
            "administrator" => "/Dashboard/Administration",
            "customer" => "/Dashboard/Customers"
        ]
    ];

    $redirectUrl = $redirectMap[$PageType][strtolower($CurrentOnlineAccessAccount->accessType->name)] ?? null;

    $clientPages = [
        "Client",
        "Customer",
        "Account Management | Customer",
        "Account Management | Partners",
        "Account Management | Authorized User",
        "Web Design Services Management | Client"
    ];

    if ($redirectUrl) {

        header("Location: $redirectUrl");
        exit();
        
    }

?>