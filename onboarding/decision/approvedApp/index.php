<?php
    include($_SERVER["DOCUMENT_ROOT"]."/lang/en_US.php");
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

    echo '<title>Your account was approved!</title>';

?>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>

    <section class="section purchase-complete" style="padding-top:0%; padding-left:10%;" id="birthdays">
        <div class="container caliweb-container">
            <div style="display:flex; align-items:center;">
                <div>
                    <img src="<?php echo $orglogolight; ?>" class="caliweb-navbar-logo-img light-mode" style="width:15%; margin-top:12%;" />
                    <img src="<?php echo $orglogodark; ?>" class="caliweb-navbar-logo-img dark-mode" style="width:15%; margin-top:12%;" />
                    <h6 style="font-weight:700; margin:0; padding:0; margin-top:5%; margin-bottom:5%;">Congratulations! Your account was Approved!</h6>
                    <p class="caliweb-login-sublink license-text-dark width-100">Welcome to Cali Web Design! We have decided to approve your account. Click <a class="careers-link" href="/dashboard/">here</a> to visit your dashboard.</p>
                </div>
            </div>
        </div>
    </section>
    <div class="caliweb-login-footer license-footer">
        <div class="container caliweb-container">
            <div class="caliweb-grid-2">
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Services LLC - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <div class="list-links-footer">
                    <a href="'.$paneldomain.'/terms">Terms of Service</a>
                    <a href="'.$paneldomain.'/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
<?php
    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Login.php');

    } else {
        header("Location: /error/licenseInvalid");
    }
?>
