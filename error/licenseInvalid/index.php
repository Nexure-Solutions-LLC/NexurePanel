<?php
    require($_SERVER["DOCUMENT_ROOT"].'/lang/en_US.php');
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    use Dotenv\Dotenv;

    // Load environment variables from .env file
    
    $dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
    $dotenv->load();

    // Get database credentials from environment variables

    $variableDefinitionX->licenseKeyfromConfig = $_ENV['LICENCE_KEY'];

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    // Perform query

    $result = mysqli_query($con, "SELECT * FROM caliweb_panelconfig WHERE id = '1'");
    $panelinfo = mysqli_fetch_array($result);
    // Free result set

    mysqli_free_result($result);

    $variableDefinitionX->panelName = $panelinfo['panelName'];
    $variableDefinitionX->paneldomain = $panelinfo['panelDomain'];
    $variableDefinitionX->orgShortName = $panelinfo['organizationShortName'];
    $variableDefinitionX->orglogolight = $panelinfo['organizationLogoLight'];
    $variableDefinitionX->orglogodark = $panelinfo['organizationLogoDark'];
    $variableDefinitionX->licenseKeyfromDB = $panelinfo['panelKey'];

    if ($variableDefinitionX->licenseKeyfromConfig != $variableDefinitionX->licenseKeyfromDB) {

?>
<!-- Universal Rounded Floating Cali Web Design Header Bar start -->   
<html lang="en-us">
    <head>
        <meta charset="utf-8" />
        <meta name="author" content="Cali Web Design Development Team, Nick Derry, Michael Brinkley">
        <link href="https://caliwebdesignservices.com/assets/css/2024-01-29-styling.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/login-css-2024.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="apple-touch-icon" sizes="180x180" href="https://caliwebdesignservices.com/assets/img/favico/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://caliwebdesignservices.com/assets/img/favico/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="https://caliwebdesignservices.com/assets/img/favico/favicon-16x16.png">
        <link rel="manifest" href="https://caliwebdesignservices.com/assets/img/favico/site.webmanifest">
        <?php include($_SERVER["DOCUMENT_ROOT"]."/dashboard/themes/index.php"); ?>
        <script type="text/javascript">   
            window.antiFlicker = {
                active: true,
                timeout: 3000
            }           
        </script>
        <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
        <script src="https://js.stripe.com/v3/"></script>
        <style id="no-bg-img">*{background-image:none!important /* Remove Background Images until the DOM Loads */}</style>
        <style>
            body{overflow:hidden;}
        </style>
    </head>
    <body>
        <?php
            ob_start();
            include($_SERVER["DOCUMENT_ROOT"]."/lang/en_US.php");
        ?>
            <!-- 
                Unique Website Title Tag Start 
                The Page Title specified what page the user is on in 
                the browser tab and should be included for SEO
            -->
                <title><?php echo $variableDefinitionX->orgShortName; ?> - License Error</title>
            <!-- Unique Website Title Tag End -->

            <section class="section" style="padding-top:5%; padding-left:5%;">
                <div class="container caliweb-container">
                    <h3 class="caliweb-login-heading license-text-dark"><?php echo $LANG_LICENSE_TITLE_PAR_1; ?> <span style="font-weight:700;"><?php echo $LANG_LICENSE_TITLE_PAR_2; ?></span></h3>
                    <p class="caliweb-login-sublink license-text-dark" style="font-weight:700; padding-top:0; margin-top:0;"><?php echo $LANG_LICENSE_TITLE; ?></p>
                    <p class="caliweb-login-sublink license-text-dark width-50"><?php echo $LANG_LICENSE_TEXT; ?></p>
                </div>
            </section>

            <div class="caliweb-login-footer license-footer">
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
                            <a href="https://caliwebdesignservices.com/terms">Terms of Service</a>
                            <a href="https://caliwebdesignservices.com/privacy">Privacy Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        <script src="https://caliwebdesignservices.com/assets/js/index.js"></script>
    </body>
</html>
<?php
    } else {

        header("Location: /login");

    }
?>