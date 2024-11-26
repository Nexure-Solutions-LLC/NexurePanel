<?php

    // Cali Web Design Panel Installer
    // Version 20.0.1
    // Copyright Nexure Solutions LLC (C) 2024. All Rights Reserved.
    // This is the installer for the Cali Panel Web Software, It will configure
    // the panel, install dependencies, and setup the database and tables.
    // This installer is opesource under the same license as the panel.

    
    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Login/Headers/index.php'); 

?>

    <head>
        <title>Cali Panel Installer 20.0.1</title>
        <script src="https://NexureSolutionsservices.com/assets/js/darkmode.js" type="text/javascript"></script>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="author" content="Cali Web Design Development Team, Nick Derry, Michael Brinkley">
        <link href="https://NexureSolutionsservices.com/assets/css/2024-01-29-styling.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/login-css-2024.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="apple-touch-icon" sizes="180x180" href="https://NexureSolutionsservices.com/assets/img/favico/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://NexureSolutionsservices.com/assets/img/favico/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="https://NexureSolutionsservices.com/assets/img/favico/favicon-16x16.png">
        <link rel="manifest" href="https://NexureSolutionsservices.com/assets/img/favico/site.webmanifest">
        <script type="text/javascript">   
            window.antiFlicker = {
                active: true,
                timeout: 3000
            }           
        </script>
        <style id="no-bg-img">*{background-image:none!important /* Remove Background Images until the DOM Loads */}</style>
    </head>

    <section class="login-container" style="height:100%; overflow:hidden;">
        <div class="caliweb-login-box login-only" style="max-width:35%; margin-top:6%;">
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
                        <h3 style="font-size:20px;">Installer</h3>
                        <p style="font-size:14px;">This is the Cali Installer.</p>
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

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Login/Footers/index.php'); 

?>