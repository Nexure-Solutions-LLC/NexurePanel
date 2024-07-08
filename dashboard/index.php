<?php
    ob_clean();
    ob_start();

    require($_SERVER["DOCUMENT_ROOT"].'/lang/en_US.php');
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    use Detection\MobileDetect;

    session_start();

    // Check if the user is accessing from a mobile device

    $detect = new MobileDetect();

    if ($detect->isMobile() || $detect->isTablet()) {
        header("Location: /error/mobileExperiance/");
        exit();
    }

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

    use Dotenv\Dotenv;

    // Load environment variables from .env file
    $dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
    $dotenv->load();

    // Get database credentials from environment variables
    $licenseKeyfromConfig = $_ENV['LICENCE_KEY'];

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    $caliemail = $_SESSION['caliid'];

    // Perform query
    $result = mysqli_query($con, "SELECT * FROM caliweb_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($result);
    // Free result set
    mysqli_free_result($result);

    $userrole = $userinfo['userrole'];

    if ($userrole == "Customer" || $userrole == "customer") {
        header("location:/dashboard/customers");
    } else if ($userrole == "Authorized User" || $userrole == "authorized user") {
        header("location:/dashboard/customers/authorizedUserView");
    } else if ($userrole == "Partner" || $userrole == "partner") {
        header("location:/dashboard/partnerships");
    } else if ($userrole == "Administrator" || $userrole == "administrator") {
        header("location:/dashboard/administration");
    } else {
        header("Location: /error/genericSystemError/");
    }

?>