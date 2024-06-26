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


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="author" content="Nick Derry, Michael Brinkley, Cali Web Design Corporation">
        <meta property="og:image" content="https://caliwebdesignservices.com/assets/img/opengraphimage/opengraphimage.jpg" />
        <meta property="og:type" content="website" />
        <meta content="summary_large_image" name="twitter:card" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="CaliWebDesignSvcs" name="generator" />
        <link href="https://caliwebdesignservices.com/assets/css/2024-01-29-styling.css" rel="stylesheet" type="text/css" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="https://caliwebdesignservices.com/assets/img/favico/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://caliwebdesignservices.com/assets/img/favico/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="https://caliwebdesignservices.com/assets/img/favico/favicon-16x16.png">
        <link rel="manifest" href="https://caliwebdesignservices.com/assets/img/favico/site.webmanifest">
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
        <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
        <script type="text/javascript">
            window.antiFlicker = {
                active: true,
                timeout: 3000
            }
        </script>
        <script src="https://caliwebdesignservices.com/assets/js/darkmode.js" type="text/javascript"></script>
    </head>
    <body>
        <center>
            <img src="<?php echo $orglogolight; ?>" class="caliweb-navbar-logo-img light-mode" style="width:15%; margin-top:12%;" />
            <img src="<?php echo $orglogodark; ?>" class="caliweb-navbar-logo-img dark-mode" style="width:15%; margin-top:12%;" />
            <p style="font-weight:300; font-size:20px; margin-top:2%; margin-bottom:4%;"><?php echo $LANG_LOGOUT_BASE_TEXT; ?> <br><?php echo $LANG_LOGOUT_SECONDARY_TEXT; ?> <span id="countdown"></span>. <br><?php echo $LANG_LOGOUT_REDIRECT_FALLBACK_TEXT; ?> <a href="/login" class="careers-link">click here</a>.</p>
            <h6 style="font-weight:700;">We hope you have a Good <span id="lblGreetings"></span></h6>
        </centeR>
    </body>
    <script>
        var myDate = new Date();
        var hrs = myDate.getHours();

        var greet;

        if (hrs < 12)
            greet = 'Morning';
        else if (hrs >= 12 && hrs <= 17)
            greet = 'Afternoon';
        else if (hrs >= 17 && hrs <= 24)
            greet = 'Evening';

        document.getElementById('lblGreetings').innerHTML = greet;

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
            startCountdown(5, '/login'); // Change 'https://example.com' to your desired URL
        };

    </script>
</html>
<?php
    } else {
        header("Location: /license");
    }
?>
