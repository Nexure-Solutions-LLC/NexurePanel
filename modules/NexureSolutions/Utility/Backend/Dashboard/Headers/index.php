<?php

    require($_SERVER["DOCUMENT_ROOT"] . "/modules/NexureSolutions/Utility/Backend/System/Dashboard.php");

?>
    <!DOCTYPE html>
    <!-- 
            
            _   __                             _____       __      __  _                 
           / | / /__  _  ____  __________     / ___/____  / /_  __/ /_(_)___  ____  _____
          /  |/ / _ \| |/_/ / / / ___/ _ \    \__ \/ __ \/ / / / / __/ / __ \/ __ \/ ___/
         / /|  /  __/>  </ /_/ / /  /  __/   ___/ / /_/ / / /_/ / /_/ / /_/ / / / (__  ) 
        /_/ |_/\___/_/|_|\__,_/_/   \___/   /____/\____/_/\__,_/\__/_/\____/_/ /_/____/  
                                                                                    

        This site was created by Nexure Solutions LLC. http://www.nexuresolutions.com
        Last Published: January 30 2025 at 06:57:42 PM (Eastern Time)

        Creator/Developer: Nexure Development Team

        Images and content used on this website may come from third-party sources. Credits go
        to the respective owners of that content.

        Website Registration Code: 1099203-662835
        Registration Date: November 4 2024
        Initial Development On: June 30 2023
        Last Update: January 30 2024 06:58:02 AM Eastern Time
        Website Version: 20.0.2
        Expiration Date: 04/20/2098 (LTSB Long-Term Servicing Branch)

        Contact Information:
            Phone: +1-855-537-3591
            Email: support@nexuresolutions.com

        Copyright Statement: Do not copy this website, if the code is found to be duplicated, reproduced,
        or copied we will fine you a minimum of $250,000 and criminal charges may be pressed.

        Note from Developer: 

        To Mcaopin thank you for standing by me through every challenge, every long night, and every 
        quiet day of hard work. Your patience, love, and unwavering support has meant everything to me.
        You were the reason why I started this and I can't thank you enough. Your a real one.

        CopyOurCodeWeWillSendYouToJesus(C)2024ThisIsOurHardWork.

        Dear rule breakers, questioners, straight-A students who skipped class: We want you.
        https://nexuresolutions.com/careers.

    -->
    <html lang="en-us">

    <head>
        <script src="https://nexuresolutions.com/assets/js/v1/darkmode.js" type="text/javascript"></script>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="author" content="Nexure Development Team, Nick Derry, Michael Brinkley">
        <link href="https://nexuresolutions.com/assets/css/v1/2024-01-29-styling.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/dashboard-css-2024.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="apple-touch-icon" sizes="180x180" href="https://nexuresolutions.com/assets/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://nexuresolutions.com/assets/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="https://nexuresolutions.com/assets/img/favicon/favicon-16x16.png">
        <link rel="manifest" href="https://nexuresolutions.com/assets/img/favicon/site.webmanifest">
        <?php include($_SERVER["DOCUMENT_ROOT"] . "/dashboard/company/themes/index.php"); ?>
        <?php

        if (in_array($pagetitle, $clientPages) || (isset($pagesubtitle) && $pagesubtitle == "Client") || $pagetype == "Client") {

            echo '<link href="/assets/css/client-dashboard-css-2024.css" rel="stylesheet" type="text/css" />';
        } else {

            echo '';
        }

        ?>
        <script type="text/javascript">
            window.antiFlicker = {
                active: true,
                timeout: 3000
            }
        </script>
        <script async defer>
            (function(h, o, t, j, a, r) {
                h.hj = h.hj || function() {
                    (h.hj.q = h.hj.q || []).push(arguments)
                };
                h._hjSettings = {
                    hjid: 3806731,
                    hjsv: 6
                };
                a = o.getElementsByTagName('head')[0];
                r = o.createElement('script');
                r.async = 1;
                r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
                a.appendChild(r);
            })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
        </script>
        <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
        <script src="https://js.stripe.com/v3/"></script>
        <style id="no-bg-img">
            * {
                background-image: none !important
                    /* Remove Background Images until the DOM Loads */
            }
        </style>
    </head>

    <body>

        <div class="caliweb-navbar" id="caliweb-header">
            <div class="background-darker-300">
                <div class="container caliweb-navbar-container">
                    <div class="caliweb-navbar-logo">
                        <a href="https://nexuresolutions.com/">
                            <img src="<?php echo $variableDefinitionX->orglogolight; ?>" width="100px" loading="lazy" alt="Light Logo" class="caliweb-navbar-logo-img light-mode">
                            <img src="<?php echo $variableDefinitionX->orglogodark; ?>" width="100px" loading="lazy" alt=" Dark Logo" class="caliweb-navbar-logo-img dark-mode">
                        </a>
                    </div>
                    <div class="caliweb-header-search">
                        <input class="form-input caliweb-search-input" id="systemSearch" placeholder="Search all of <?php echo $variableDefinitionX->orgShortName ?>" />
                        <div id="systemSearchResults" class="systemwide-search-results"></div>
                    </div>
                    <div class="caliweb-nav-buttons display-flex align-center">
                        <a href="/dashboard/accountManagement" class="display-flex align-center profile-block dark-mode-white">
                            <?php

                            if ($currentAccount->profile_url != "") {

                                echo '<img src="' . $currentAccount->profile_url . '" class="profileImage" />';
                            } else {

                                echo '<img src="/assets/img/profileImages/default.png" class="profileImage" />';
                            }

                            ?><span><?php echo $currentAccount->legalName; ?></span>
                        </a>
                        <a href="/dashboard/messageCenter" class="toggle-container" style="padding: 6px 10px 5px 10px;">
                            <span class="lnr lnr-envelope" class="toggle-input"></span>
                        </a>
                        <span class="toggle-container">
                            <span class="lnr lnr-sun" class="toggle-input" id="lightModeIcon"></span>
                            <span class="lnr lnr-moon" class="toggle-input" id="darkModeIcon"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="border-top-grey-300">
                <div class="container display-flex align-center">
                    <nav class="caliweb-navbar-menu" id="caliweb-navigation">
                        <?php include($_SERVER["DOCUMENT_ROOT"] . "/modules/NexureSolutions/Utility/Backend/Dashboard/Menus/index.php"); ?>
                    </nav>
                    <div class="systemLoads display-flex align-center">
                        <a href="/dashboard/administration/systemStatus" class="dark-mode-white" style="text-decoration:none;">
                            <div class="dashboard-system-status">
                                <p class="display-flex align-center" style="font-size:14px;"><img src="/assets/img/systemIcons/serviceStatus.png" style="height:20px; width:20px; margin-right:10px;" /> <span>All services are online</span></p>
                            </div>
                        </a>
                        <span>
                            <?php

                            $loads = sys_getloadavg();

                            $rounded_loads = array_map(function ($load) {

                                return number_format($load, 2);
                            }, $loads);

                            echo "System Loads: " . implode(", ", $rounded_loads);

                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>