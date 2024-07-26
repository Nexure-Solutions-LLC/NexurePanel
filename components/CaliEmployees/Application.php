<?php

    ob_clean();
    ob_start();

    include($_SERVER["DOCUMENT_ROOT"]."/components/CaliHeaders/Login.php");
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require($_SERVER["DOCUMENT_ROOT"] . "/components/CaliAccounts/Account.php");

    $caliemail = $_SESSION['caliid'];

    $currentAccount = new \CaliAccounts\Account($con);
    $success = $currentAccount->fetchByEmail($caliemail);

    $redirectMap = [
        "Suspended" => "/error/suspendedAccount",
        "Terminated" => "/error/terminatedAccount",
        "Active" => "/dashboard"
    ];

    $currentStatus = $currentAccount->accountStatus->name;

    $redirectUrl = null;

    if (isset($redirectMap[$currentStatus])) {
        $redirectUrl = $redirectMap[$currentStatus];
    }

    $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    if ($redirectUrl && $currentUrl !== $redirectUrl) {

        header("Location: $redirectUrl");
        exit();

    }

    if (isset($_POST['langPreference'])) {

        $_SESSION["lang"] = $_POST['langPreference'];

    }

    if (isset($_SESSION["lang"])) {

        if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/lang/'.$_SESSION["lang"].'.php')) {

            $_SESSION["lang"] = 'en_US';

        }
        
        include($_SERVER["DOCUMENT_ROOT"].'/lang/'.$_SESSION["lang"].'.php');

    } else {

        include($_SERVER["DOCUMENT_ROOT"]."/lang/en_US.php");

    }

    if ($pagetitle == "Employment Application") {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
            // Run Form Logic

        }

    }

?>