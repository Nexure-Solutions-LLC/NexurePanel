<?php

    ob_clean();
    ob_start();

    require($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/Login/Headers/index.php");


    $caliemail = $_SESSION['caliid'];

    $currentAccount = new \NexureSolutions\Accounts\AccountHandler($con);
    $success = $currentAccount->fetchByEmail($caliemail);

    try {

        $redirectMap = [
            "Terminated" => [
                "Closed" => [
                    "The customer is running a prohibited business and their application was denied." => "/onboarding/decision/deniedApp",
                    "The customer scored too high on the risk score and we cant serve this customer." => "/onboarding/decision/deniedApp"
                ],
                "Suspended" => "/error/suspendedAccount",
                "UnderReview" => "/error/underReviewAccount",
                "Active" => "/dashboard"
            ],
            "Suspended" => [
                "Closed" => [
                    "The customer is running a prohibited business and their application was denied." => "/onboarding/decision/deniedApp",
                    "The customer scored too high on the risk score and we cant serve this customer." => "/onboarding/decision/deniedApp"
                ],
                "Terminated" => "/error/terminatedAccount",
                "UnderReview" => "/error/underReviewAccount",
                "Active" => "/dashboard"
            ],
            "Under Review" => [
                "Closed" => [
                    "The customer is running a prohibited business and their application was denied." => "/onboarding/decision/deniedApp",
                    "The customer scored too high on the risk score and we cant serve this customer." => "/onboarding/decision/deniedApp"
                ],
                "Suspended" => "/error/suspendedAccount",
                "Terminated" => "/error/underReviewAccount",
                "Active" => "/dashboard"
            ]
        ];
        
        // Get the current URL path

        $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $currentStatus = $currentAccount->accountStatus->name;
        $currentReason = $currentAccount->statusReason ?? '';
        
        $redirectUrl = null;
        
        if (isset($redirectMap[$pagetitle])) {

            if ($currentStatus === "Closed" && isset($redirectMap[$pagetitle][$currentStatus][$currentReason])) {

                $redirectUrl = $redirectMap[$pagetitle][$currentStatus][$currentReason];

            } elseif (isset($redirectMap[$pagetitle][$currentStatus])) {

                $redirectUrl = $redirectMap[$pagetitle][$currentStatus];

            }
        }
        
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

    } catch (\Throwable $exception) {
            
        \Sentry\captureException($exception);
    
    }

?>