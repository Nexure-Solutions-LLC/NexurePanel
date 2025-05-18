<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Backend/index.php");

?>

    <html>

        <header>
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
            <link rel="stylesheet" href="/Assets/css/2025-dashboard-css-v2.css" />
            
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
        </header>

        <body>

            <div class="nexure-header" id="nexure-header-js">
                <div class="background-darker-300">
                    <div class="container nexure-container nexure-nav-container">
                        <div class="display-flex align-center">
                            <div class="nexure-branding">
                                <a href="https://nexuresolutions.com/">
                                    <img src="<?php echo $VariableDefinitionHandler->organizationWideLogo; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo light-mode">
                                    <img src="<?php echo $VariableDefinitionHandler->organizationWideLogoDark; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo dark-mode">
                                </a>
                            </div>
                        </div>
                        <div class="search-container width-50">
                            <form method="POST" action="" class="no-margin no-padding">
                                <input class="nexure-textbox searchbar" name="nexuresearch" id="nexuresearch" placeholder="Search all of <?php echo $VariableDefinitionHandler->organizationShortName; ?>" />
                            </form>
                        </div>
                        <div class="display-flex align-center">
                            <a href="/Dashboard/Settings/Account" class="profile-link display-flex align-center">
                                <?php if ($VariableDefinitionHandler->profileImage != "" || $VariableDefinitionHandler->profileImage != NULL): ?> 
                                    <img src="<?php echo $VariableDefinitionHandler->profileImage; ?>" class="image-fluid profile-image" />
                                <?php else: ?>
                                    <img src="/Assets/img/ProfileImages/Default.png" class="image-fluid profile-image" />
                                <?php endif; ?>
                                <span class="secondary-font font-14px margin-left-10px"><?php echo $VariableDefinitionHandler->displayName; ?></span>
                            </a>
                            <span class="toggle-container">
                                <span class="lnr lnr-sun" class="toggle-input" id="lightModeIcon"></span>
                                <span class="lnr lnr-moon"  class="toggle-input" id="darkModeIcon"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="border-top-grey-300">
                    <div class="container display-flex align-center justify-content-space-between">
                        <nav class="nexure-navbar-menu display-flex align-center" id="nexure-navbar-js">
                            <p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Dashboard</p>
                            <li class="nav-links"><a href="/">Dashboard</a></li>
                            <li class="nav-links"><a href="/Onboarding">Open an account</a></li>
                            <li class="nav-links"><a href="/Dashboard/Customer/Billing">Billing</a></li>
                            <li class="nav-links"><a href="/Dashboard/Customer/SupportCenter">Support Center</a></li>
                            <li class="nav-links"><a href="/Dashboard/Customer/SecurityCenter">Access & Security</a></li>
                            <li class="nav-links"><a href="/Dashboard/Customer/ServiceInformation">Service Information</a></li>
                            <li class="nav-links"><a href="/Logout">Sign Off</a></li>
                        </nav>
                        <div class="systemLoads display-flex align-center">
                            <p class="font-14px">
                                <?php if ($PageTitle == "Admin Center") {

                                    $loads = sys_getloadavg();

                                    $rounded_loads = array_map(function ($load) {

                                        return number_format($load, 2);
                                    }, $loads);

                                    echo "System Loads: " . implode(", ", $rounded_loads);

                                } ?>
                            </p>
                            <button style="background-color:transparent; border:none; outline:0;" href="javascript:void(0);" class="nexure-menu-icon" aria-label="Mobile Menu" onclick="responsiveMenu()">
                                <img src="https://nexuresolutions.com/assets/img/systemicons/menu.svg" loading="lazy" width="24" alt="" class="menu-icon">
                            </button>
                        </div>
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
