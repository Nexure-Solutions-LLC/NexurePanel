<?php

    // Nexure Panel Required and System Imports

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require($_SERVER["DOCUMENT_ROOT"] . "/modules/NexureSolutions/Utility/Backend/index.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/license/licensecheck.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

    ob_clean();
    ob_start();

    // Custom Error Handler

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
    use GuzzleHttp\Client;
    use IPLib\Factory;
    use Detection\MobileDetect;
    use Stripe\Stripe;

    \Sentry\init([
        'dsn' => $_ENV['SENTRY_DSN'],
        'traces_sample_rate' => 1.0,
        'profiles_sample_rate' => 1.0,
    ]);

    $dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);

    $dotenv->load();

    $caliemail = $_SESSION['caliid'];

    $variableDefinitionX = new \NexureSolutions\Generic\VariableDefinitions();

    $variableDefinitionX->variablesHeader($con);

    $currentAccount = new \NexureSolutions\Accounts\AccountHandler($con);

    $success = $currentAccount->fetchByEmail($caliemail);

    $passableUserId = $variableDefinitionX->userId;

    $passableApiKey = $variableDefinitionX->apiKey;

    $blacklistIPStatus = $variableDefinitionX->blacklistIPStatus;

    $sentryToken = $_ENV['SENTRY_TOKEN'];

    $sentryOrg = $_ENV['SENTRY_ORG'];

    $sentryProject = $_ENV['SENTRY_PROJECT'];

    $accountnumberlength = $_ENV["ACCOUNTNUMBERLENGTH"];

    // Mobile Detection

    $detect = new MobileDetect();

    if ($detect->isMobile() || $detect->isTablet()) {

        header("Location: /error/mobileExperience/");

        exit();

    }

    // Language Definition

    if (isset($_SESSION["lang"])) {

        $lang = $_SESSION['lang'];

    } else {

        $lang = "en_US";

    }
    
    $lang_preset = ($lang !== null) ? $lang : "en_US";

    if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/lang/'.$lang_preset.'.php')) {

        $lang_preset = 'en_US';

    }

    include($_SERVER["DOCUMENT_ROOT"].'/lang/'.$lang_preset.'.php');


    // IP Address Checking and Banning

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

        header("Location: /error/bannedUser");

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

    // Checks the users account status and send them to the right page.
    // If the user is active load the dashboard like normal.
    // Also checks the users role instead of doing it on each page

    if ($currentAccount->accountStatus->name == "UnderReview") {

        switch ($currentAccount->statusReason) {
            case "The customers risk score flagged for review and needs to be approved by a Nexure Team Member.":
                header("Location: /onboarding/decision/manualReview");
                break;
            case "This customer needs to speak to the Online Team, transfer them. FOR ONLINE TEAM USE ONLY. The account was flagged for unusual activity, verify customer.":
                header("Location: /onboarding/decision/callOnlineTeam");
                break;
            case "DO NOT ASSIST OVER PHONE. Have customer email the internal risk team. FOR INTERNAL RISK TEAM. The customer flagged high on Stripe. Check with Stripe to see further actions.":
                header("Location: /onboarding/decision/emailRiskTeam");
                break;
            case "Customer needs to verify identity at a branch, do not assist over the phone or email. Close after 60 days if they dont present to a branch.":
                header("Location: /onboarding/decision/presentBranch");
                break;
            default:
                header("Location: /error/underReviewAccount");
        }

    } elseif ($currentAccount->accountStatus->name == "Closed" && in_array($currentAccount->statusReason, [
        "The customer is running a prohibited business and their application was denied.",
        "The customer scored too high on the risk score and we cant serve this customer."
    ])) {

        header("Location: /onboarding/decision/deniedApp");

    } else {

        switch ($currentAccount->accountStatus->name) {
            case "Suspended":
                header("Location: /error/suspendedAccount");
                break;
            case "Terminated":
                header("Location: /error/terminatedAccount");
                break;
        }

    }

    $redirectMap = [
        "Client" => [
            "authorized user" => "/dashboard/customers/authorizedUserView",
            "partner" => "/dashboard/partnerships",
            "administrator" => "/dashboard/administration"
        ],
        "Administration" => [
            "authorized user" => "/dashboard/customers/authorizedUserView",
            "partner" => "/dashboard/partnerships",
            "customer" => "/dashboard/customers"
        ],
        "Authorized User" => [
            "customer" => "/dashboard/customers",
            "partner" => "/dashboard/partnerships",
            "administrator" => "/dashboard/administration"
        ],
        "Partners" => [
            "authorized user" => "/dashboard/customers/authorizedUserView",
            "administrator" => "/dashboard/administration",
            "customer" => "/dashboard/customers"
        ]
    ];

    $redirectUrl = $redirectMap[$pagetype][strtolower($currentAccount->role->name)] ?? null;

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

    if ($pagetitle == "Account Management") {

        $roleTitles = [
            'customer' => 'Account Management | Customer',
            'authorized user' => 'Account Management | Authorized User',
            'administrator' => 'Account Management | Administrator',
            'partner' => 'Account Management | Partners'
        ];

        $pagetitle = isset($roleTitles[strtolower($currentAccount->role->name)]) ? $roleTitles[strtolower($currentAccount->role->name)] : 'Account Management';

    }

    function truncateString($string, $length = 30) {

        if (strlen($string) > $length) {

            return substr($string, 0, $length) . '...';

        }

        return $string;
        
    }

    if (isset($_SESSION["referral_url"])) {

        header("location: /modules/NexureSolutions/Discord/linkedRoles");

    }

    function createStripeCustomers($con) {

        $query = "SELECT secretKey FROM nexure_paymentconfig WHERE processorName = 'Stripe' LIMIT 1";
        $result = mysqli_query($con, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
    
            $row = mysqli_fetch_assoc($result);
            $secretKey = $row['secretKey'];

            
            Stripe::setApiKey($secretKey);


            $query = "SELECT n.id, n.email, n.legalName, n.mobileNumber, n.accountNumber, 
                     oi.emailAddress, oi.addressline1, oi.addressline2, 
                     oi.city, oi.state, oi.postalcode, oi.country 
              FROM nexure_users n 
              LEFT JOIN nexure_ownershipinformation oi ON n.email = oi.emailAddress
              WHERE n.stripeID = ''";


            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {

                while ($user = mysqli_fetch_assoc($result)) {

                    try {

                        $customer = \Stripe\Customer::create([
                            'email' => $user['email'],
                            'name' => $user['legalName'],
                            'phone' => $user['mobileNumber'],
                            'address' => [
                                'line1' => $user['addressline1'],
                                'line2' => $user['addressline2'],
                                'city' => $user['city'],
                                'state' => $user['state'],
                                'postal_code' => $user['postalcode'],
                                'country' => $user['country'],
                            ],
                            'description' => "Account Number: " . $user['accountNumber'],
                        ]);

                        $updateQuery = "UPDATE nexure_users SET stripeID = ? WHERE id = ?";
                        $stmt = $con->prepare($updateQuery);
                        $stmt->bind_param("si", $customer->id, $user['id']);
                        $stmt->execute();
                        $stmt->close();

                    } catch (Exception $e) {

                        redirect("/error/genericSystemError");

                    }

                }

            }

        }

    }

    createStripeCustomers($con);
    
?>