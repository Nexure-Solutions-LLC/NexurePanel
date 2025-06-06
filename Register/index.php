<?php

    session_start();

    $encryption_key = getenv('ENCRYPTION_KEY');

    $encryption_iv = getenv('ENCRYPTION_IV');

    function encryptTIN($data, $key, $iv) {

        return base64_encode(openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv));

    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {

        $businessLegalName = htmlspecialchars($_POST['businessLegalName']);

        $businessDBAName = htmlspecialchars($_POST['businessDBAName']);

        $line1 = htmlspecialchars($_POST['addressLine1']);

        $line2 = htmlspecialchars($_POST['addressLine2']);

        $city = htmlspecialchars($_POST['city']);

        $state = htmlspecialchars($_POST['state']);

        $postal = htmlspecialchars($_POST['postalCode']);

        $country = htmlspecialchars($_POST['country']);

        $tin = encryptTIN($_POST['businessTIN'], $encryption_key, $encryption_iv);

        $_SESSION['nexureApplication']['businessLegalName'] = $businessLegalName;

        $_SESSION['nexureApplication']['businessDBAName'] = $businessDBAName;

        $_SESSION['nexureApplication']['businessLine1'] = $line1;

        $_SESSION['nexureApplication']['businessLine2'] = $line2;

        $_SESSION['nexureApplication']['businessCity'] = $city;

        $_SESSION['nexureApplication']['businessState'] = $state;

        $_SESSION['nexureApplication']['businessPostalCode'] = $postal;

        $_SESSION['nexureApplication']['businessCountry'] = $country;

        $_SESSION['nexureApplication']['businessTIN'] = $tin;

        header("Location: /Onboarding/OpenAccount/Application/Business/IndustryInformation");

        exit;
    }

    include($_SERVER["DOCUMENT_ROOT"] . "/Modules/NexureSolutions/Login/Headers/index.php");

?>

    <title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | Business Account Application</title>

    <style>
        body {
            overflow-y: auto !important;
        }
    </style>

    <section class="section nexure-open-online-access-and-account" style="padding-bottom:7%;">
        <div class="container nexure-container">
            <div style="display:flex; align-items:top;">
                <div>
                    <h5 class="font-18px">Need help?</h5>
                    <p class="font-14px margin-top-10px">If you need help with your application, call us at <?php echo $VariableDefinitionHandler->organizationSupportInfo; ?></p>
                </div>
                <div class="margin-left-80px">
                    <h4 class="font-20px">Thanks for enrolling</h4>
                    <p class="font-14px margin-top-20px width-60">We need a bit more info to verify your identity. This form should only take you a few minutes and you will have access to your accounts. If you are already enrolled then you can <a href="/Login" class="brand-link">login</a>.</p>
                    <form action="" method="POST" class="width-70">
                        <div class="nexure-grid nexure-two-grid no-row-gap margin-top-60px">
                            <div class="form-control"  style="text-align:left; align-items:start;">
                                <label for="accountNumber">Account or application number</label>
                                <input type="text" name="accountNumber" class="nexure-textbox" required>
                            </div>
                            <div class="form-control" style="text-align:left; align-items:start;">
                                <label for="accountType">Account Type</label>
                                <select name="accountType" class="nexure-textbox" required>
                                    <option>Personal</option>
                                    <option>Business</option>
                                </select>
                            </div>
                        </div>
                        <div class="nexure-grid nexure-one-grid no-row-gap margin-top-20px">
                            <div class="form-control" style="text-align:left; align-items:start;">
                                <label for="userTIN">EIN or SSN</label>
                                <input type="text" name="userTIN" class="nexure-textbox" required>
                            </div>
                            <div class="form-control"  style="text-align:left; align-items:start;">
                                <label for="nexureid">Email</label>
                                <input type="text" name="nexureid" class="nexure-textbox" required>
                            </div>
                            <div class="form-control"  style="text-align:left; align-items:start;">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="nexure-textbox" required>
                            </div>
                        </div>
                        <br>
                        <div class="button-area" style="text-align:right;">
                            <button class="nexure-button primary" style="padding:10px 50px;" type="submit" name="submit">Next</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/Modules/NexureSolutions/Login/Footers/index.php"); ?>
