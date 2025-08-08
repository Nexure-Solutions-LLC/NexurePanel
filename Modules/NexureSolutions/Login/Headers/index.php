<?php

    require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Login/Backend/index.php');

?>

<!-- 
        
         _   __                             _____       __      __  _                 
        / | / /__  _  ____  __________     / ___/____  / /_  __/ /_(_)___  ____  _____
       /  |/ / _ \| |/_/ / / / ___/ _ \    \__ \/ __ \/ / / / / __/ / __ \/ __ \/ ___/
      / /|  /  __/>  </ /_/ / /  /  __/   ___/ / /_/ / / /_/ / /_/ / /_/ / / / (__  ) 
     /_/ |_/\___/_/|_|\__,_/_/   \___/   /____/\____/_/\__,_/\__/_/\____/_/ /_/____/  
                                                                                 

    This site was created by Nexure Solutions LLP. http://www.nexuresolutions.com
    Last Published: Aug 07 2025 at 09:33:56 PM (Eastern Time)

    Creator/Developer: Nexure Development Team

    Images and content used on this website may come from third-party sources. Credits go
    to the respective owners of that content.

    Contact Information:
        Phone: +1-855-537-3591
        Email: support@nexuresolutions.com

    Note from Developer: 

    Dear Ari, you were my fourth love, while short lived, was the most special. You showed me what
    love could be. I wish we had more time together before swiftly parting ways. This update is
    made in dedication not of our current relationship but what our relationship could have been.

    You will be missed not only here at Nexure but also in my heart. You were one of a kind and someone
    truly special and unique. I love you and always will.

    Designed and Developed by Nexure in Pennsylvania.

    Dear rule breakers, questioners, straight-A students who skipped class: We want you.
    https://nexuresolutions.com/careers.
    

-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="author" content="Nexure Development Team">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:image" content="https://nexuresolutions.com/assets/img/opengraphimage/opengraphimage.webp" />
        <meta property="og:type" content="website" />
        <meta content="summary_large_image" name="twitter:card" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="NexureSolutions" name="generator" />
        <meta name="languageCode" content="en"/>
        <meta name="countryCode" content="us"/>
        <meta name="focusArea" content="No Contact Module"/>
        <link rel="canonical" href="https://nexuresolutions.com/"/>
        <link href="https://nexuresolutions.com/assets/css/v2/2025-15-06-styling.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://nexuresolutions.com/assets/css/2024-11-08-fonts.css">
        <link rel="stylesheet" href="/Assets/css/2025-login-css-v2.css" />

        <?php 
            
            if ($VariableDefinitionHandler->panelTheme != "NexureDefault") {
                
                echo '<link rel="stylesheet" href="/Themes/'.$VariableDefinitionHandler->panelTheme.'/Assets/css/style.css" />';

            }

        ?>

        <link rel="apple-touch-icon" sizes="180x180" href="https://nexuresolutions.com/assets/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://nexuresolutions.com/assets/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="https://nexuresolutions.com/assets/img/favicon/favicon-16x16.png">
        <link rel="manifest" href="https://nexuresolutions.com/assets/img/favicon/site.webmanifest">
        <script type="text/javascript">   
            window.antiFlicker = {
                active: true,
                timeout: 3000
            }           
        </script>
        <script src="https://nexuresolutions.com/assets/js/darkmode.js" type="text/javascript"></script>
        <script type="text/javascript">
            var languageCode = document.getElementsByName('languageCode')[0].content;
            var countryCode = document.getElementsByName('countryCode')[0].content;
            var focusArea = document.getElementsByName('focusArea')[0].content;
            /* Define digital data object based on _appInfo object */
            window.digitalData = {
                page: {
                    category: {
                        primaryCategory: '',
                    },
                    pageInfo: {
                        language: languageCode + '-' + countryCode,
                        NexureSolutions: {
                            siteID: 'MarketingAEM',
                            country: countryCode,
                            messaging: {
                                routing: {
                                    focusArea: focusArea,
                                    languageCode: languageCode,
                                    regionCode: countryCode
                                },
                                translation: {
                                    languageCode: languageCode,
                                    regionCode: countryCode
                                }
                            },
                            sections: 0,
                            patterns: 0
                        }
                    }
                }
            };
        </script>
    </head>
    <body>
    
        <!-- Nexure Login Header with light/dark mode toggle and branding -->

        <div class="nexure-header" id="nexure-header-js">
            <div class="container nexure-container nexure-nav-container">
                <div class="display-flex align-center">
                    <div class="nexure-branding">
                        <a href="https://nexuresolutions.com/" class="text-neutral">
                            <span><?php echo $VariableDefinitionHandler->organizationShortName; ?></span> | <span class="font-bold">CRM Cloud</span>
                        </a>
                    </div>
                    <div class="nexure-navbar-menu" id="nexure-navbar-js"></div>
                </div>
                <div class="display-flex align-center">
                    <span class="toggle-container">
                        <span class="lnr lnr-sun" class="toggle-input" id="lightModeIcon"></span>
                        <span class="lnr lnr-moon"  class="toggle-input" id="darkModeIcon"></span>
                    </span>
                    <form action="" method="POST">
                        <select type="text" class="nexure-textbox display-flex align-center" name="langPreference" id="langPreference" required="" onchange="this.form.submit()" style="margin-top:0; padding:10px 8px 8px 8px; margin-left:10px;">
                            <option value="EN_US" <?php echo isSelectedLang("EN_US"); ?>>English</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

<?php 
    
    if (isset($_SESSION["lang"])) {

        if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/Language/'.$_SESSION["lang"].'.php')) {

            $_SESSION["lang"] = 'EN_US';

        }

        include($_SERVER["DOCUMENT_ROOT"].'/Language/'.$_SESSION["lang"].'.php');

    } else {

        include($_SERVER["DOCUMENT_ROOT"]."/Language/EN_US.php");

    }

?>