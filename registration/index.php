<?php

    ob_start();
    session_start();

    $pagetitle = "Registration";
    $pagesubtitle = "Basic Information";

    include($_SERVER["DOCUMENT_ROOT"] . "/modules/CaliWebDesign/Utility/Backend/Login/Headers/index.php");

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

        $query = "SELECT COUNT(*) FROM caliweb_networks WHERE ipAddress = ? AND listType = 'blacklist'";

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

        $query = "SELECT COUNT(*) FROM caliweb_networks WHERE ipAddress = ? AND listType = 'whitelist'";

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
    
    // When form submitted, insert values into the database.

    if (isset($_POST['emailaddress'])) {

        $current_time = time();

        // Check if the last submission time is stored in the session
        
        if (isset($_SESSION['last_submit_time'])) {

            $time_diff = $current_time - $_SESSION['last_submit_time'];

            if ($time_diff < 5) {

                header("Location: /error/rateLimit");
                exit;

            }
        }

        // If the rate limit check passes, update the last submission time

        $_SESSION['last_submit_time'] = $current_time;

        try {


            $data = array(
                'secret' => "0x1097356228F6a429882375bC5974c5a9a2631Bb3",
                'response' => $_POST['h-captcha-response']
            );

            $verify = curl_init();


            curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);


            $response = curl_exec($verify);
            $responseData = json_decode($response);


            if($responseData->success) {

                $legalname = stripslashes($_REQUEST['legalname']);
                $legalname = mysqli_real_escape_string($con, $legalname);
                $caliid = stripslashes($_REQUEST['emailaddress']);
                $caliid = mysqli_real_escape_string($con, $caliid);
                $mobilenumber = stripslashes($_REQUEST['phonenumber']);
                $mobilenumber = mysqli_real_escape_string($con, $mobilenumber);
                $password = stripslashes($_REQUEST['password']);
                $password = mysqli_real_escape_string($con, $password);
                $registrationdate = date("Y-m-d H:i:s");
                $accountnumber = substr(str_shuffle("0123456789"), 0, 12);
                $dispnone = stripslashes($_REQUEST['dispnone']);
                $dispnone = mysqli_real_escape_string($con, $dispnone);

                $accountnumber_starting = $_ENV['ACCOUNTSTARTNUMBER'];
                $builtaccountnumber = $accountnumber_starting.$accountnumber;

                $checkCaliID = "SELECT * FROM caliweb_users WHERE `email` = '$caliid'";
                $resultCaliIDCheck = mysqli_query($con, $checkCaliID);

                if (mysqli_num_rows($resultCaliIDCheck) == 1) {

                    $register_error = true;

                } else {

                    function generateRandomPrefix($length = 3) {

                        $characters = 'abcdefghijklmnopqrstuvwxyz';
                        $prefix = '';

                        for ($i = 0; $i < $length; $i++) {

                            $prefix .= $characters[rand(0, strlen($characters) - 1)];

                        }

                        return $prefix;

                    }
                
                    $randomPrefix = generateRandomPrefix(5);

                    if ($dispnone == "") {

                        if (mysqli_connect_errno()) {
                            echo "Failed to connect to MySQL: " . mysqli_connect_error();
                            exit();
                        }

                        // Checks type of payment processor.

                         if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

                            if ($variableDefinitionX->paymentProcessorName == "Stripe") {

                                include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

                            } else {

                                header ("location: /error/genericSystemError");

                            }

                        } else {

                            echo 'There are no payment modules available to service this request.';

                        }

                        $SS_STRIPE_ID = add_customer($legalname, $caliid, $mobilenumber, $builtaccountnumber);

                        $query    = "INSERT INTO `caliweb_users`(`email`, `password`, `legalName`, `mobileNumber`, `accountStatus`, `statusReason`, `statusDate`, `accountNotes`, `accountNumber`, `accountDBPrefix`, `emailVerfied`, `emailVerifiedDate`, `registrationDate`, `profileIMG`, `stripeID`, `discord_id`, `google_id`, `userrole`, `employeeAccessLevel`, `ownerAuthorizedEmail`, `firstInteractionDate`, `lastInteractionDate`, `lang`) VALUES ('$caliid','".hash("sha512", $password)."','$legalname','$mobilenumber','Under Review','We need more information to continuing opening an account with us.','$registrationdate','','$builtaccountnumber','$randomPrefix','false','0000-00-00 00:00:00','$registrationdate','','$SS_STRIPE_ID','','','Customer','Retail','','$registrationdate','0000-00-00 00:00:00','en-US')";
                        $result   = mysqli_query($con, $query);

                        if ($result) {

                            echo '<script type="text/javascript">window.location = "/login"</script>';

                        } else {

                            header ("location: /error/genericSystemError");

                        }

                    }

                }

            } else {

                header ("location: /error/genericSystemError");

            }

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }
        
    }

?>
<!-- Universal Rounded Floating Cali Web Design Header Bar End -->

    <!--
        Unique Website Title Tag Start
        The Page Title specified what page the user is on in
        the browser tab and should be included for SEO
    -->
        <title><?php echo $variableDefinitionX->orgShortName; ?> - Unified Portal</title>
    <!-- Unique Website Title Tag End -->

        <section class="login-container">
            <div class="container caliweb-container bigscreens-are-strange" style="width:42%; margin-top:2%;">
                <div class="caliweb-login-box-header" style="text-align:left; margin-bottom:7%;">
                    <h3 class="caliweb-login-heading"><?php echo $variableDefinitionX->orgShortName; ?> <span style="font-weight:700"> <?php echo $LANG_SELF_REGISTER_TITLE; ?></h3>
                    <p style="font-size:12px; margin-top:-2%;"><?php echo $LANG_SELF_REGISTER_SUBTITLE; ?></p>
                </div>
                <div class="caliweb-login-box-body">
                    <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="legalname" class="text-gray-label"><?php echo $LANG_SELF_REGISTER_LEGAL_NAME ?></label>
                            <input type="text" class="form-input" name="legalname" id="legalname" placeholder="" required="" />
                        </div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="phonenumber" class="text-gray-label"><?php echo $LANG_SELF_REGISTER_PHONE_NUMBER ?></label>
                            <input type="number" class="form-input" name="phonenumber" id="phonenumber" placeholder="" inputmode="numeric" maxlength="10" required="" />
                        </div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="emailaddress" class="text-gray-label"><?php echo $LANG_SELF_REGISTER_EMAIL_ADDR ?></label>
                            <input type="email" class="form-input" name="emailaddress" id="emailaddress" placeholder="" required="" />
                        </div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="password" class="text-gray-label"><?php echo $LANG_SELF_REGISTER_PASSWORD ?></label>
                            <input type="password" class="form-input" name="password" id="password" placeholder="" />
                        </div>
                        <div class="form-control">
                            <input type="text" class="form-input" style="display:none;" name="dispnone" id="dispnone" placeholder="" />
                        </div>
                        <?php if (isset($register_error)): ?>
                            <div class="caliweb-error-box" style="margin-bottom:2%; margin-top:-1%;">
                                <p class="caliweb-login-sublink" style="font-weight:700; padding-top:0; margin-top:0;"><?php echo $LANG_REG_SUBMIT_ERROR_TITLE; ?></p>
                                <p class="caliweb-login-sublink" style="font-size:12px;"><?php echo $LANG_REG_SUBMIT_ERROR_TEXT; ?></p>
                            </div>
                        <?php endif; ?>
                        <div style="padding-top:2%;">
                            <div class="h-captcha" id="h-captcha" data-sitekey="509db1ec-9483-4051-aea3-8ba88d8bbc8e"></div>
                        </div>
                        <div class="mt-5-per" style="display:flex; align-items:center; justify-content:space-between;">
                            <div class="form-control width-50">
                                <p style="font-size:14px; padding:0; margin:0;"><?php echo $LANG_ID_DISCLAIMER; ?></p>
                            </div>
                            <div class="form-control width-25">
                                <button class="caliweb-button primary" style="text-align:left; display:flex; align-center; justify-content:space-between;" type="submit" name="submit"><?php echo $LANG_LOGIN_BUTTON; ?><span class="lnr lnr-arrow-right" style=""></span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <div class="caliweb-login-footer">
            <div class="container caliweb-container">
                <div class="caliweb-grid-2">
                    <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                    <!--
                        THIS TEXT IS TO GIVE CREDIT TO THE AUTHORS AND REMOVING IT
                        MAY CAUSE YOUR LICENSE TO BE REVOKED.
                    -->
                    <div class="">
                        <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Services LLC - All rights reserved. It is illegal to copy this website.</p>
                    </div>
                    <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                    <div class="list-links-footer">
                        <a href="<?php echo $variableDefinitionX->paneldomain; ?>/terms">Terms of Service</a>
                        <a href="<?php echo $variableDefinitionX->paneldomain; ?>/privacy">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>

<?php 
    
    include($_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Utility/Backend/Login/Footers/index.php"); 
    
?>