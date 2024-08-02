<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    if (isset($_POST['langPreference'])) {
        $_SESSION["lang"] = $_POST["langPreference"];
    }

    if (isset($_SESSION["lang"])) {

        if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/lang/'.$_SESSION["lang"].'.php')) {

            $_SESSION["lang"] = 'en_US';

        }

        include($_SERVER["DOCUMENT_ROOT"].'/lang/'.$_SESSION["lang"].'.php');

    } else {

        include($_SERVER["DOCUMENT_ROOT"]."/lang/en_US.php");

    }

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

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

    // Perform query

    $result = mysqli_query($con, "SELECT * FROM caliweb_panelconfig WHERE id = '1'");
    $panelinfo = mysqli_fetch_array($result);

    // Free result set

    mysqli_free_result($result);

    $panelname = $panelinfo['panelName'];
    $paneldomain = $panelinfo['panelDomain'];
    $orgshortname = $panelinfo['organizationShortName'];
    $orglogolight = $panelinfo['organizationLogoLight'];
    $orglogodark = $panelinfo['organizationLogoDark'];
    $licenseKeyfromDB = $panelinfo['panelKey'];


    if ($licenseKeyfromConfig == $licenseKeyfromDB) {

        include($_SERVER["DOCUMENT_ROOT"]."/components/CaliHeaders/Login.php");

        try {

            echo '<title>You have been signed out of your Cali account.</title>';

?>
            <section class="section" style="padding-top:0%; padding-left:10%;">
                <div class="container caliweb-container">
                    <div style="display:flex; align-items:center;">
                        <div>
                            <img src="<?php echo $orglogolight; ?>" class="caliweb-navbar-logo-img light-mode" style="width:15%; margin-top:12%;" />
                            <img src="<?php echo $orglogodark; ?>" class="caliweb-navbar-logo-img dark-mode" style="width:15%; margin-top:12%;" />
                            <h6 style="font-weight:700; margin:0; padding:0; margin-top:5%; margin-bottom:5%;"><?php echo $LANG_PASSWORD_RESETTITLE_TEXT; ?>.</h6>
                            <p class="caliweb-login-sublink license-text-dark width-100"><?php echo $LANG_PASSWORD_RESETSUCCESS_BASE_TEXT; ?> <?php echo $LANG_PASSWORD_RESETSUCCESS_SECONDARY_TEXT; ?> <span id="countdown"></span>. <?php echo $LANG_PASSWORD_RESETSUCCESS_REDIRECTFALLBACK_TEXT; ?> <a href="/login" class="careers-link">click here</a>.</p>
                        </div>
                    </div>
                </div>
            </section>
            <div class="caliweb-login-footer license-footer">
                <div class="container caliweb-container">
                    <div class="caliweb-grid-2">
                        <div class="">
                            <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Corporation - All rights reserved. It is illegal to copy this website.</p>
                        </div>
                        <div class="list-links-footer">
                            <a href="'.$paneldomain.'/terms">Terms of Service</a>
                            <a href="'.$paneldomain.'/privacy">Privacy Policy</a>
                        </div>
                    </div>
                </div>
            </div>
            <script>

                // Function to start countdown

                function startCountdown(seconds, redirectUrl) {

                    var countdownElement = document.getElementById('countdown');
                    var remainingSeconds = seconds;
                    
                    var countdownInterval = setInterval(function() {

                        countdownElement.innerHTML = remainingSeconds;
                        remainingSeconds--;
                        
                        if (remainingSeconds < 0) {

                            clearInterval(countdownInterval);
                            window.location.href = redirectUrl;

                        }

                    }, 1000);

                }

                // Start the countdown when the page loads

                window.onload = function() {

                    startCountdown(5, '/login');

                };

            </script>
<?php
            include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Login.php');

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);

        } 

    } else {

        header("Location: /error/licenseInvalid");
    
    }

?>
