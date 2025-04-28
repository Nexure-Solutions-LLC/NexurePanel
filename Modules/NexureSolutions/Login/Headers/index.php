<?php

    require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Login/Backend/index.php');

?>

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
        <link href="https://nexuresolutions.com/assets/css/2024-11-04-styling.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://nexuresolutions.com/assets/css/2024-11-08-fonts.css">
        <link rel="stylesheet" href="/Assets/css/2025-login-css-v2.css" />
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
                        <a href="https://nexuresolutions.com/catalog" class="text-neutral">
                            <span>Nexure</span> | <span class="font-bold">CRM Cloud</span>
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