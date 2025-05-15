<?php

    // This is the Nexure Backend Middleware.
    // Author: Nexure Developers
    // Nexure Solutions LLP (C) 2025 - All rights reserved.

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
            public $displayName;
            public $OnlineAccessInformation;
            public $accessType;
            public $onlineAccessStatus;
            public $firstinteractiondateformattedfinal;
            public $lastinteractiondateformattedfinal;
            public $emailverifydate;
            public $emailverifydateformatted;
            public $emailverifydateformattedfinal;
            public $emailverifystatus;
            public $paymentID;
            public $userAccounts = [];

            public $accountNumber;
            public $accountDisplayName;
            public $headerName;
            public $creditLimit;
            public $balance;
            public $minimumPayment;
            public $dueDate;
            public $accountStatus;
            public $accountServices = [];
            public $selectedAccountDetails;

            public $PanelConfigurationInformation;
            public $organizationLegalName;
            public $organizationShortName;
            public $organizationSquareLogo;
            public $organizationWideLogo;
            public $organizationWideLogoDark;
            public $organizationAddressLine1;
            public $organizationAddressLine2;
            public $organizationCity;
            public $organizationState;
            public $organizationCountry;
            public $organizationPostalCode;
            public $organizationSupportInfo;
            public $paymentDescriptor;
            public $licenseKey;
            public $activationDate;
            public $expirationDate;
            public $organizationID;
            public $registrationDisabledMessage;
            public $maintenanceEnabledMessage;
            public $maintenanceStatus;
            public $registrationStatus;
            public $panelTheme;

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

            public function GatherPanelConfiguration(\mysqli $con): void
            {
                $this->PanelConfigurationInformation = $this->fetchSingleRow(
                    $con,
                    "SELECT * FROM nexure_config WHERE 1"
                );

                $this->organizationLegalName = $this->PanelConfigurationInformation['organizationLegalName'] ?? null;

                $this->organizationShortName = $this->PanelConfigurationInformation['organizationShortName'] ?? null;

                $this->organizationSquareLogo = $this->PanelConfigurationInformation['organizationSquareLogo'] ?? null;

                $this->organizationWideLogo = $this->PanelConfigurationInformation['organizationWideLogo'] ?? null;

                $this->organizationWideLogoDark = $this->PanelConfigurationInformation['organizationWideLogoDark'] ?? null;

                $this->organizationAddressLine1 = $this->PanelConfigurationInformation['organizationAddressLine1'] ?? null;

                $this->organizationAddressLine2 = $this->PanelConfigurationInformation['organizationAddressLine2'] ?? null;

                $this->organizationCity = $this->PanelConfigurationInformation['organizationCity'] ?? null;

                $this->organizationState = $this->PanelConfigurationInformation['organizationState'] ?? null;

                $this->organizationCountry = $this->PanelConfigurationInformation['organizationCountry'] ?? null;

                $this->organizationPostalCode = $this->PanelConfigurationInformation['organizationPostalCode'] ?? null;

                $this->organizationSupportInfo = $this->PanelConfigurationInformation['organizationSupportInfo'] ?? null;

                $this->paymentDescriptor = $this->PanelConfigurationInformation['paymentDescriptor'] ?? null;

                $this->licenseKey = $this->PanelConfigurationInformation['licenseKey'] ?? null;

                $this->activationDate = $this->PanelConfigurationInformation['activationDate'] ?? null;

                $this->expirationDate = $this->PanelConfigurationInformation['expirationDate'] ?? null;

                $this->organizationID = $this->PanelConfigurationInformation['organizationID'] ?? null;

                $this->registrationDisabledMessage = $this->PanelConfiguratioInformation['registrationDisabledMessage'] ?? null;

                $this->maintenanceEnabledMessage = $this->PanelConfigurationInformation['maintenanceEnabledMessage'] ?? null;

                $this->maintenanceStatus = $this->PanelConfigurationInformation['maintenanceStatus'] ?? null;

                $this->registrationStatus = $this->PanelConfigurationInformation['registrationStatus'] ?? null;
                
                $this->panelTheme = $this->PanelConfigurationInformation['panelTheme'] ?? null;

            }

            public function GatherOnlineAccessInformation(\mysqli $con, string $nexureid): void
            {

                $this->OnlineAccessInformation = $this->fetchSingleRow(
                    $con,
                    "SELECT * FROM nexure_users WHERE email = ? LIMIT 1",
                    [$nexureid]
                );

                $this->onlineAccessStatus = $this->OnlineAccessInformation['onlineAccessStatus'] ?? null;

                $this->displayName = $this->OnlineAccessInformation['displayName'] ?? 'User';

                $this->paymentID = $this->OnlineAccessInformation['paymentID'] ?? '';

                $this->accessType = $this->OnlineAccessInformation['accessLevel'] ?? null;

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

                    $this->OnlineAccessInformation['emailVerificationDate'] ?? null

                );

                $this->emailverifystatus = ucfirst($this->OnlineAccessInformation['emailStatus'] ?? 'Unknown');

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

            public function GatherUserAccounts(\mysqli $con, string $nexureid): void
            {

                $stmt = $con->prepare("SELECT accountNumber, accountStatus, openedDate FROM nexure_accounts WHERE email = ? ORDER BY openedDate DESC");

                if (!$stmt) {

                    \Sentry\captureException(new \Exception("Prepare failed: " . $con->error));

                    throw new \Exception("Prepare failed: " . $con->error);

                }

                $stmt->bind_param('s', $nexureid);

                $stmt->execute();

                $result = $stmt->get_result();

                if (!$result) {

                    \Sentry\captureException(new \Exception("Query failed: " . $stmt->error));

                    throw new \Exception("Query failed: " . $stmt->error);

                }

                $accounts = [];

                while ($row = $result->fetch_assoc()) {

                    $accounts[] = $row;

                }

                $stmt->close();

                $latestAccountNumber = $accounts[0]['accountNumber'] ?? null;

                foreach ($accounts as &$account) {

                    $accountNumber = $account['accountNumber'];

                    $stmt = $con->prepare("SELECT serviceName FROM nexure_services WHERE accountNumber = ? ORDER BY orderDate DESC LIMIT 1");

                    $stmt->bind_param('s', $accountNumber);

                    $stmt->execute();

                    $serviceResult = $stmt->get_result();

                    $accountDisplayName = ($serviceResult && $serviceResult->num_rows > 0)
                        ? $serviceResult->fetch_assoc()['serviceName']
                        : "Unnamed Service";

                    $stmt->close();

                    $stmt = $con->prepare("SELECT businessLegalName FROM nexure_businesses WHERE accountNumber = ? LIMIT 1");

                    $stmt->bind_param('s', $accountNumber);

                    $stmt->execute();

                    $businessResult = $stmt->get_result();

                    $businessName = ($businessResult && $businessResult->num_rows > 0)
                        ? $businessResult->fetch_assoc()['businessLegalName']
                        : null;

                    $stmt->close();

                    $stmt = $con->prepare("SELECT legalName FROM nexure_ownership WHERE accountNumber = ? LIMIT 1");

                    $stmt->bind_param('s', $accountNumber);

                    $stmt->execute();

                    $ownershipResult = $stmt->get_result();

                    $legalName = ($ownershipResult && $ownershipResult->num_rows > 0)
                        ? $ownershipResult->fetch_assoc()['legalName']
                        : "Personal Account";

                    $stmt->close();

                    $headerName = ($businessName && $accountNumber === $latestAccountNumber)
                        ? $businessName
                        : $legalName;

                    $gatewayStmt = $con->prepare("SELECT processorName FROM nexure_payments WHERE status = 'Active'");

                    $gatewayStmt->execute();

                    $gatewayResult = $gatewayStmt->get_result();

                    $processors = [];

                    while ($row = $gatewayResult->fetch_assoc()) {

                        $processors[] = $row['processorName'];

                    }

                    $gatewayStmt->close();

                    $balanceInfo = [
                        'credit' => 0.0,
                        'subscription' => 0.0
                    ];

                    $balanceDisplay = '&mdash;';
                    
                    $balance = 0.0;

                    foreach ($processors as $processor) {

                        $filePath = $_SERVER["DOCUMENT_ROOT"]."/Modules/{$processor}/Payments/Backend/index.php";

                        if (file_exists($filePath)) {

                            include_once $filePath;

                            $stripe = initStripe($con);

                            $creditBalance = getCreditBalance($stripe, $this->paymentID);

                            $balanceInfo['credit'] += $creditBalance;
                                
                        }

                    }

                    $credit = floatval($balanceInfo['credit']);

                    $balance = $credit;

                    if ($credit !== 0.0) {

                        $balanceDisplay = ($balance < 0)

                            ? "-" . number_format(abs($balance), 2)
                            : "" . number_format($balance, 2);

                    } elseif ($credit === 0.0) {

                        $balanceDisplay = "0.00";

                    }

                    $this->userAccounts[] = [
                        'accountNumber' => $accountNumber,
                        'accountStatus' => $account['accountStatus'],
                        'balance' => $balanceDisplay,
                        'dueDate' => $account['dueDate'] ?? 'N/A',
                        'accountDisplayName' => $accountDisplayName,
                        'headerName' => $headerName,
                    ];

                }

            }

            public function GatherSingleAccountDetails(\mysqli $con, string $accountNumber): void
            {

                $accountStmt = $con->prepare("SELECT * FROM nexure_accounts WHERE accountNumber = ? LIMIT 1");

                $accountStmt->bind_param("s", $accountNumber);

                $accountStmt->execute();

                $accountResult = $accountStmt->get_result();

                $accountDetails = $accountResult->fetch_assoc();

                $accountStmt->close();

                if (!$accountDetails) {

                    $this->selectedAccountDetails = null;

                    return;

                }

                $businessStmt = $con->prepare("SELECT businessLegalName FROM nexure_businesses WHERE accountNumber = ? LIMIT 1");

                $businessStmt->bind_param("s", $accountNumber);

                $businessStmt->execute();

                $businessResult = $businessStmt->get_result();

                $businessDetails = $businessResult->fetch_assoc();

                $businessStmt->close();

                $ownershipStmt = $con->prepare("SELECT legalName FROM nexure_ownership WHERE accountNumber = ? LIMIT 1");

                $ownershipStmt->bind_param("s", $accountNumber);

                $ownershipStmt->execute();

                $ownershipResult = $ownershipStmt->get_result();

                $ownershipDetails = $ownershipResult->fetch_assoc();

                $ownershipStmt->close();

                $headerName = $businessDetails['businessLegalName'] ?? ($ownershipDetails['legalName'] ?? 'Unknown');

                $gatewayStmt = $con->prepare("SELECT processorName FROM nexure_payments WHERE status = 'Active'");

                $gatewayStmt->execute();

                $gatewayResult = $gatewayStmt->get_result();

                $processors = [];

                while ($row = $gatewayResult->fetch_assoc()) {

                    $processors[] = $row['processorName'];

                }

                $gatewayStmt->close();

                $balanceInfo = [
                    'credit' => 0.0,
                    'subscription' => 0.0
                ];

                $balanceDisplay = '&mdash;';
                
                $balance = 0.0;

                foreach ($processors as $processor) {

                    $filePath = $_SERVER["DOCUMENT_ROOT"]."/Modules/{$processor}/Payments/Backend/index.php";

                    if (file_exists($filePath)) {

                        include_once $filePath;

                        $stripe = initStripe($con);

                        $creditBalance = getCreditBalance($stripe, $this->paymentID);

                        $balanceInfo['credit'] += $creditBalance;
                            
                    }

                }

                $credit = floatval($balanceInfo['credit']);

                $balance = $credit;

                if ($credit !== 0.0) {

                    $balanceDisplay = ($balance < 0)

                        ? "-" . number_format(abs($balance), 2)
                        : "" . number_format($balance, 2);

                } elseif ($credit === 0.0) {

                    $balanceDisplay = "0.00";

                }

                $minimumPayment = ($balance > 50.00) ? round($balance * 0.30, 2) : $balance;

                $servicesStmt = $con->prepare("SELECT * FROM nexure_services WHERE accountNumber = ?");

                $servicesStmt->bind_param("s", $accountNumber);

                $servicesStmt->execute();

                $servicesResult = $servicesStmt->get_result();

                $services = [];

                while ($row = $servicesResult->fetch_assoc()) {

                    $services[] = $row;

                }

                $servicesStmt->close();

                $serviceStmt = $con->prepare("SELECT serviceName FROM nexure_services WHERE accountNumber = ? ORDER BY orderDate DESC LIMIT 1");

                $serviceStmt->bind_param("s", $accountNumber);

                $serviceStmt->execute();

                $serviceResult = $serviceStmt->get_result();

                $serviceDetails = $serviceResult->fetch_assoc();

                $serviceStmt->close();

                $accountDisplayName = $serviceDetails['serviceName'] ?? 'Unknown';

                $this->selectedAccountDetails = [
                    'accountNumber' => $accountNumber,
                    'accountDisplayName' => $accountDisplayName,
                    'headerName' => $headerName,
                    'creditLimit' => $accountDetails['creditLimit'] ?? 0,
                    'accountStatus' => $accountDetails['accountStatus'] ?? 'Unknown',
                    'balance' => $balanceDisplay,
                    'minimumPayment' => $minimumPayment,
                    'dueDate' => 'May 30 2025',
                    'services' => $services
                ];
                
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