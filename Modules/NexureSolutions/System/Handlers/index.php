<?php

    // This is the Nexure Backend Middleware.
    // This replaces the separate middleware our former developer Mikey made.
    // It also was moved to Nexure Modules from Nexure Components.
    // THIS SOFTWARE IS OPENSOURCE UNDER COMMON DEVELOPMENT AND DISTRIBUTION LICENSE Version 1.0
    // (C) 2025 Nexure Solutions LLC.

    // ============= Start Middleware Logic - This needs to be first to work. ============= */

    // This is the start of the handlers for accounts, tasks, variables, etc.

    // Variable Definitions

    namespace NexureSolutions\Generic {

        use DateTime;
        use Exception;
        use Sentry;
        use NexureSolutions\Utility;

        class VariableDefinitions
        {

            public $nexureid;
            public $OnlineAccessInformation;
            public $accessType;
            public $onlineAccessStatus;

            public $firstinteractiondateformattedfinal;
            public $lastinteractiondateformattedfinal;

            public $emailverifydate;
            public $emailverifydateformatted;
            public $emailverifydateformattedfinal;

            public $emailverifystatus;

            private function fetchSingleRow(\mysqli $con, string $query, array $params = []): ?array
            {

                $stmt = $con->prepare($query);

                if (!$stmt) {

                    Sentry\captureException(new Exception("Prepare failed: " . $con->error));

                    throw new Exception("Prepare failed: " . $con->error);

                }

                if (!empty($params)) {

                    $types = str_repeat('s', count($params));

                    $stmt->bind_param($types, ...$params);

                }

                $stmt->execute();

                $result = $stmt->get_result();

                if (!$result) {

                    Sentry\captureException(new Exception("Query failed: " . $stmt->error));

                    throw new Exception("Query failed: " . $stmt->error);

                }

                $row = $result->fetch_assoc();

                $stmt->close();

                return $row ?: null;
            }

            public function GatherOnlineAccessInformation(\mysqli $con, string $nexureid): void
            {

                $this->OnlineAccessInformation = $this->fetchSingleRow(
                    $con,
                    "SELECT * FROM nexure_users WHERE email = ? LIMIT 1",
                    [$nexureid]
                );

                $this->onlineAccessStatus = $this->OnlineAccessInformation['onlineAccessStatus'] ?? null;

                $this->accessType = $this->OnlineAccessInformation['accessType'] ?? null;

                $newInteractionDate = date('Y-m-d H:i:s');

                $updateStmt = $con->prepare("UPDATE nexure_users SET lastInteractionDate = ? WHERE email = ?");

                if ($updateStmt) {

                    $updateStmt->bind_param('ss', $newInteractionDate, $nexureid);

                    $updateStmt->execute();

                    $updateStmt->close();

                } else {

                    Sentry\captureException(new Exception("Prepare failed for update: " . $con->error));

                }

                $this->firstinteractiondateformattedfinal = $this->formatDate(

                    $this->OnlineAccessInformation['firstInteractionDate'] ?? null

                );

                $this->lastinteractiondateformattedfinal = $this->formatDate(

                    $newInteractionDate

                );

                $this->emailverifydateformattedfinal = $this->formatDate(

                    $this->OnlineAccessInformation['emailVerifiedDate'] ?? null

                );

                $this->emailverifystatus = ucfirst($this->OnlineAccessInformation['emailVerifiedStatus'] ?? 'Unknown');

            }

            private function formatDate(?string $date): string
            {
                
                if (empty($date)) {

                    return 'Unknown';

                }

                try {

                    $dateTime = new DateTime($date);

                    return $dateTime->format('F j, Y g:i A');

                } catch (Exception $e) {

                    Sentry\captureException($e);

                    return 'Invalid Date';

                }

            }

        }

    }

    // Nexure Calendar Component

    namespace NexureSolutions\Calendar {

        class CalendarComponents
        {

            public $eventsresponse;
            public $accountnumber;

            public function eventsRetrive($con, $accountnumber) {

                $this->eventsresponse = mysqli_query($con, "SELECT eventName, eventDescription, eventTimeDate FROM nexure_events WHERE accountNumber = '$accountnumber' ORDER BY eventTimeDate DESC");

            }

        }

    }

    // ============= Start Additional Logic not relating to the middleware. ============= */

    namespace { 

        // Bring in the required files such as database connection.

        require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Configuration/EnvironmentFile/index.php');
        require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Configuration/Database/index.php');

        session_start();

        // Error Logging and Redirection

        function errorHandler($errno, $errstr, $errfile, $errline) {

            $log_timestamp = date("d-m-Y_H-i-sa");

            $errorMessage = "Error: [$errno] $errstr in $errfile on line $errline\n";
        
            $errorLogDir = $_SERVER["DOCUMENT_ROOT"] . "/ErrorHandling/Logs/";

            $errorLogFile = $errorLogDir . "$log_timestamp.log";
        

            if (!is_dir($errorLogDir)) {

                mkdir($errorLogDir, 0775, true);

            }
        
            error_log($errorMessage, 3, $errorLogFile);

            if (session_status() === PHP_SESSION_ACTIVE) {

                $_SESSION['error_log_file'] = $errorLogFile;

            }

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
        
        register_shutdown_function(function() {

            $error = error_get_last();

            if ($error !== null && in_array($error['type'], [

                E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING

            ])) {

                errorHandler($error['type'], $error['message'], $error['file'], $error['line']);

            }

        });

        // IP Address Checking and Banning

        $passableUserId = $_ENV['IPCHECKAPIUSER'];
        $passableApiKey = $_ENV['IPCHECKAPIKEY'];
        $blacklistIPStatus = $_ENV['BLACKLIST_IP_STATUS'] ?? "False";

        function getClientIp() {

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

        function isIpBlocked($ip, $con) {

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

        function isIpAllowed($ip, $con) {

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

        function isIpBlacklistedOrProxyVpn($ip, $passableUserId, $passableApiKey) {

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

        function hasAdBlocker() {

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

        function isIPSpamListed($ip, $passableUserId, $passableApiKey) {

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

        function banIp($ip) {

            header("Location: /ErrorHandling/ErrorPages/BannedUser");

            exit;

        }

        // Assuming $pdo is your PDO connection

        if (!isIpAllowed($clientIp, $con) && $blacklistIPStatus == "True") {

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
    }

?>