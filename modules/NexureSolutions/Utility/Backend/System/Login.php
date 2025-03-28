<?php

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/modules/NexureSolutions/Utility/Backend/index.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/license/licensecheck.php");

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

    $variableDefinitionX = new \NexureSolutions\Generic\VariableDefinitions();
    $variableDefinitionX->variablesHeader($con);

    $passableUserId = $variableDefinitionX->userId;
    $passableApiKey = $variableDefinitionX->apiKey;

    $blacklistIPStatus = $variableDefinitionX->blacklistIPStatus;

    $accountnumberlength = $_ENV["ACCOUNTNUMBERLENGTH"];

    // IP Address Checking and Banning

    function getClientIp()
    {

        $keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($keys as $key) {

            if ($ipaddress = getenv($key)) {

                return $ipaddress;
            }
        }

        return 'UNKNOWN';
    }

    $clientIp = getClientIp();

    function isIpBlocked($ip, $con)
    {

        $query = "SELECT COUNT(*) FROM nexure_networks WHERE ipAddress = ? AND listType = 'blacklist'";

        if ($stmt = $con->prepare($query)) {

            $stmt->bind_param('s', $ip);

            $stmt->execute();

            $result = $stmt->get_result();

            $count = $result->fetch_array()[0];

            $stmt->close();

            return $count > 0;
        }

        return false;
    }

    function isIpAllowed($ip, $con)
    {

        $query = "SELECT COUNT(*) FROM nexure_networks WHERE ipAddress = ? AND listType = 'whitelist'";

        if ($stmt = $con->prepare($query)) {

            $stmt->bind_param('s', $ip);

            $stmt->execute();

            $result = $stmt->get_result();

            $count = $result->fetch_array()[0];

            $stmt->close();

            return $count > 0;
        }

        return false;
    }

    function isIpBlacklistedOrProxyVpn($ip, $passableUserId, $passableApiKey)
    {

        $url = "https://neutrinoapi.net/ip-probe";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['ip' => $ip]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-ID: $passableUserId", "API-Key: $passableApiKey"]);

        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        if (isset($data['is-hosting']) && $data['is-hosting']) {

            return true;
        }

        if (isset($data['is-proxy']) && $data['is-proxy']) {

            return true;
        }

        if (isset($data['is-vpn']) && $data['is-vpn']) {

            return true;
        }

        return false;
    }

    function hasAdBlocker()
    {

        if (!isset($_SESSION['ad_blocker_checked'])) {
            echo "<script>
                    var adBlockEnabled = false;
                    var testAd = document.createElement('div');
                    testAd.innerHTML = '&nbsp;';
                    testAd.className = 'adsbox';
                    document.body.appendChild(testAd);
                    window.setTimeout(function() {
                        if (testAd.offsetHeight === 0) {
                            adBlockEnabled = true;
                        }
                        testAd.remove();
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'check_ad_blocker.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.send('adBlockEnabled=' + adBlockEnabled);
                    }, 100);
                </script>";

            $_SESSION['ad_blocker_checked'] = true;
        }

        if (isset($_SESSION['adBlockEnabled']) && $_SESSION['adBlockEnabled']) {

            return true;
        }

        return false;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adBlockEnabled'])) {

        $_SESSION['adBlockEnabled'] = $_POST['adBlockEnabled'] == 'true' ? true : false;

        exit;
    }

    function isIPSpamListed($ip, $passableUserId, $passableApiKey)
    {

        $url = "https://neutrinoapi.net/host-reputation";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'host' => $ip,
            'list-rating' => '3',
            'zones' => ''
        ]));

        curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-ID: $passableUserId", "API-Key: $passableApiKey"]);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        if (isset($data['is-listed']) && $data['is-listed']) {

            return true;
        }

        return false;
    }

    function banIp($ip)
    {

        echo '<script>window.location.replace("/error/bannedUser");</script>';

        exit;
    }

    // Assuming $pdo is your PDO connection

    if (!isIpAllowed($clientIp, $con)  && $blacklistIPStatus == "True") {

        if (isIpBlacklistedOrProxyVpn($clientIp, $passableUserId, $passableApiKey)) {

            banIp($clientIp);
        }

        if (isIPSpamListed($clientIp, $passableUserId, $passableApiKey)) {

            banIp($clientIp);
        }

        if (hasAdBlocker()) {

            banIp($clientIp);
        }

        if (isIpBlocked($clientIp, $con)) {

            banIp($clientIp);
        }
    }

    // Check Language

    if (isset($_POST['langPreference'])) {

        $_SESSION["lang"] = $_POST['langPreference'];

    }

?>