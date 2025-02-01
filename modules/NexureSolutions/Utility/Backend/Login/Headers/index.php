<?php

    require($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/System/Login.php");

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
                Phone: +1-877-597-7325
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
                <script src="https://caliwebdesignservices.com/assets/js/darkmode.js" type="text/javascript"></script>
                <meta charset="utf-8" />
                <meta content="width=device-width, initial-scale=1" name="viewport" />
                <meta name="author" content="Nexure Development Team, Nick Derry, Michael Brinkley">
                <link href="https://caliwebdesignservices.com/assets/css/2024-01-29-styling.css" rel="stylesheet" type="text/css" />
                <link href="/assets/css/login-css-2024.css" rel="stylesheet" type="text/css" />
                <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
                <link rel="apple-touch-icon" sizes="180x180" href="https://nexuresolutions.com/assets/img/favicon/apple-touch-icon.png">
                <link rel="icon" type="image/png" sizes="32x32" href="https://nexuresolutions.com/assets/img/favicon/favicon-32x32.png">
                <link rel="icon" type="image/png" sizes="16x16" href="https://nexuresolutions.com/assets/img/favicon/favicon-16x16.png">
                <link rel="manifest" href="https://nexuresolutions.com/assets/img/favicon/site.webmanifest">
                <?php include($_SERVER["DOCUMENT_ROOT"]."/dashboard/company/themes/index.php"); ?>

                <script type="text/javascript">   
                    window.antiFlicker = {
                        active: true,
                        timeout: 3000
                    }           
                </script>
                <script async defer>
                    (function(h,o,t,j,a,r){
                        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                        h._hjSettings={hjid:3806731,hjsv:6};
                        a=o.getElementsByTagName('head')[0];
                        r=o.createElement('script');r.async=1;
                        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                        a.appendChild(r);
                    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
                </script>
                <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
                <script src="https://js.stripe.com/v3/"></script>
                <style id="no-bg-img">*{background-image:none!important /* Remove Background Images until the DOM Loads */}</style>
            </head>
            <body>
                <div class="caliweb-navbar" id="caliweb-header">
                    <div class="container caliweb-navbar-container">
                        <div class="caliweb-navbar-logo">
                        
                        </div>
                        <div>

                        </div>
                        <div style="display:flex; align-items:center;">
                            <nav class="caliweb-navbar-menu" id="caliweb-navigation">
                                
                            </nav>
                            <form action="" method="POST">
                                <div class="form-control" style="">
                                    <select type="text" class="form-input" style="padding:6px 10px" name="langPreference" id="langPreference" required="" onchange="this.form.submit()">
                                        <option value="en_US" <?php echo isSelectedLang("en_US"); ?>>English</option>
                                        <option value="es_es" <?php echo isSelectedLang("es_es"); ?>>Spanish</option>
                                    </select>
                                </div>
                            </form>
                            <span class="toggle-container">
                                <span class="lnr lnr-sun" class="toggle-input" id="lightModeIcon"></span>
                                <span class="lnr lnr-moon"  class="toggle-input" id="darkModeIcon"></span>
                            </span>
                        </div>
                    </div>
                </div>
<?php 
    
    if (isset($_SESSION["lang"])) {

        if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/lang/'.$_SESSION["lang"].'.php')) {

            $_SESSION["lang"] = 'en_US';

        }
        include($_SERVER["DOCUMENT_ROOT"].'/lang/'.$_SESSION["lang"].'.php');

    } else {

        include($_SERVER["DOCUMENT_ROOT"]."/lang/en_US.php");

    }

?>