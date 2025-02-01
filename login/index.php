<?php

    session_start();

    $pagetitle = "Login Page";
    $_SESSION['pagetitle'] = $pagetitle;

    if (isset($_SESSION['caliid'])) {

        header("Location: /dashboard");
        
        exit;

    }

    if (isset($_POST['emailaddress'])) {

        require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');

        try {

            $cali_id = stripslashes($_REQUEST['emailaddress']);

            $cali_id = mysqli_real_escape_string($con, $cali_id);

            $password = stripslashes($_REQUEST['password']);

            $password = mysqli_real_escape_string($con, $password);

            $client_ip = $_SERVER['REMOTE_ADDR'];

            $query = "SELECT * FROM `nexure_users` WHERE `email` = '$cali_id' AND `password` = '" . hash("sha512", $password) . "'";

            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) == 1) {

                unset($_SESSION['failed_attempts']);

                $_SESSION['caliid'] = $cali_id;

                header("Location: /dashboard");

                exit;
            } else {

                if (!isset($_SESSION['failed_attempts'])) {

                    $_SESSION['failed_attempts'] = 0;
                }

                $_SESSION['failed_attempts']++;

                if ($_SESSION['failed_attempts'] > 5) {

                    $ban_query = "INSERT INTO `nexure_networks` (`ipAddress`, `listType`) VALUES ('$client_ip', 'Blacklist')";

                    mysqli_query($con, $ban_query);

                    header("location: /error/bannedUser");
                }


                $login_error = true;
            }
        } catch (\Throwable $exception) {

            \Sentry\captureException($exception);
        }
    }

    require($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/Login/Headers/index.php");


    $passableUserId = $variableDefinitionX->userId;
    $passableApiKey = $variableDefinitionX->apiKey;


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

        header("Location: /error/bannedUser");

        exit;
    }

    // Assuming $pdo is your PDO connection

    if (!isIpAllowed($clientIp, $con)) {

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

    echo '<title>'.$variableDefinitionX->orgShortName.' | Unified Portal</title>';

?>

    <section class="login-container">
        <div class="caliweb-login-box login-only">
            <div class="container caliweb-container">
                <div class="caliweb-login-box-header">
                    <h3 class="caliweb-login-heading">
                        <a href="<?php echo $variableDefinitionX->paneldomain; ?>">
                            <img src="<?php echo $variableDefinitionX->orglogosquare; ?>" width="72px" height="70px" loading="lazy" alt="<?php echo $variableDefinitionX->panelName; ?> Logo" class="login-box-logo-header">
                        </a>
                    </h3>
                </div>
                <div class="caliweb-login-box-content">
                    <div class="caliweb-login-box-body">
                        <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
                            <div class="form-control">
                                <label for="emailaddress" class="text-gray-label"><?php echo $variableDefinitionX->orgShortName; ?> ID:</label>
                                <input type="email" class="form-input" name="emailaddress" id="emailaddress" placeholder="" required="" />
                            </div>
                            <div class="form-control">
                                <label for="password" class="text-gray-label"><?php echo $LANG_LOGIN_PASSWORD ?></label>
                                <input type="password" class="form-input" name="password" id="password" placeholder="" />
                            </div>
                            <div class="form-control">
                                <?php
                                    $loginModulesLookupQuery = "SELECT * FROM nexure_modules WHERE moduleStatus = 'Active' AND modulePositionType = 'Authentication'";
                                    $loginModulesLookupResult = mysqli_query($con, $loginModulesLookupQuery);

                                    if (mysqli_num_rows($loginModulesLookupResult) > 0) {
                                        while ($loginModulesLookupRow = mysqli_fetch_assoc($loginModulesLookupResult)) {
                                            $loginModulesName = $loginModulesLookupRow['moduleName'];

                                            if ($loginModulesName == "Cali OAuth") {

                                                include($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Oauth//index.php");

                                            }
                                        }
                                    }

                                ?>
                                <button class="caliweb-button primary" type="submit" name="submit"><?php echo $LANG_LOGIN_BUTTON; ?></button>
                            </div>
                            <?php if (isset($login_error)): ?>
                                <div class="caliweb-error-box">
                                    <p class="caliweb-login-sublink" style="font-weight:700; padding-top:0; margin-top:0;"><?php echo $LANG_LOGIN_AUTH_ERROR_TITLE; ?></p>
                                    <p class="caliweb-login-sublink" style="font-size:12px;"><?php echo $LANG_LOGIN_AUTH_ERROR_TEXT; ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="caliweb-horizantal-spacer mt-5-per"></div>
                            <div class="form-control mt-5-per" style="margin-bottom:0;">
                                <div class="caliweb-two-grid must-be-grid">
                                    <div class="text-left">
                                        <a href="/resetPassword" class="careers-link" style="font-size:12px;">Forgot password?</a>
                                    </div>
                                    <div class="text-right">
                                        <a href="/registration" class="careers-link" style="font-size:12px;">Register Here</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="caliweb-login-footer">
        <div class="container caliweb-container">
            <div class="caliweb-grid-2">
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; 2024 - Nexure Solutions LLC - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <div class="list-links-footer">
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/terms">Terms of Service</a>
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

<?php 
    
    include($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/Login/Footers/index.php"); 
    
?>
