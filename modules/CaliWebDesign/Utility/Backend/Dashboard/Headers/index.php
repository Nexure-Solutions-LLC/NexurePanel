<?php

    require($_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Utility/Backend/System/Dashboard.php");

?>
<!DOCTYPE html>
<!-- 
       ______      ___    _       __     __       ____            _           
      / ____/___ _/ (_)  | |     / /__  / /_     / __ \___  _____(_)___ _____ 
     / /   / __ `/ / /   | | /| / / _ \/ __ \   / / / / _ \/ ___/ / __ `/ __ \
    / /___/ /_/ / / /    | |/ |/ /  __/ /_/ /  / /_/ /  __(__  ) / /_/ / / / /
    \____/\__,_/_/_/     |__/|__/\___/_.___/  /_____/\___/____/_/\__, /_/ /_/ 
                                                                /____/        

    This site was created by Cali Web Design Services LLC. http://www.caliwebdesignservices.com
    Last Published: Fri Feb 2 2024 at 08:38:52 PM (Pacific Daylight Time US and Canada) 

    Creator/Developer: Cali Web Design Development Team, Michael Brinkley, Nick Derry, All logos other 
    than ours go to their respectful owners. Images are provided by undraw.co as well as pexels.com 
    and are opensource svgs, or images to use by Cali Web Design Services LLC.

    Website Registration Code: 49503994-20344
    Registration Date: July 09 2022
    Initial Development On: Jun 30 2023
    Last Update: Feb 2th 2024
    Website Version: 20.0.0
    Expiration Date: 02/19/2088 (LTSB Long-Term Servicing Branch)
    Contact Information:
        Phone: +1-877-597-7325
        Email: support@caliwebdesignservices.com

    Copyright Statement: Do not copy this website, if the code is found to be duplicated, reproduced,
    or copied we will fine you a minimum of $250,000 and criminal charges may be pressed.

    CopyOurCodeWeWillSendYouToJesus(C)2024ThisIsOurHardWork.

    Dear rule breakers, questioners, straight-A students who skipped class: We want you.
    https://caliwebdesignservices.com/careers.
    

-->
<html lang="en-us">
    <head>
        <script src="https://caliwebdesignservices.com/assets/js/darkmode.js" type="text/javascript"></script>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="author" content="Cali Web Design Development Team, Nick Derry, Michael Brinkley">
        <link href="https://caliwebdesignservices.com/assets/css/2024-01-29-styling.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/dashboard-css-2024.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="apple-touch-icon" sizes="180x180" href="https://caliwebdesignservices.com/assets/img/favico/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://caliwebdesignservices.com/assets/img/favico/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="https://caliwebdesignservices.com/assets/img/favico/favicon-16x16.png">
        <link rel="manifest" href="https://caliwebdesignservices.com/assets/img/favico/site.webmanifest">
        <?php include($_SERVER["DOCUMENT_ROOT"]."/dashboard/company/themes/index.php"); ?>
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
        <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
        <script src="https://js.stripe.com/v3/"></script>
        <style id="no-bg-img">*{background-image:none!important /* Remove Background Images until the DOM Loads */}</style>
    </head>
    <body>

        <div class="caliweb-navbar" id="caliweb-header">
            <div class="container caliweb-navbar-container">
                <div class="caliweb-navbar-logo">
                    <a href="https://caliwebdesignservices.com/">
                        <img src="<?php echo $variableDefinitionX->orglogolight; ?>" width="100px" loading="lazy" alt="Light Logo" class="caliweb-navbar-logo-img light-mode">
                        <img src="<?php echo $variableDefinitionX->orglogodark; ?>" width="100px" loading="lazy" alt=" Dark Logo" class="caliweb-navbar-logo-img dark-mode">
                    </a>
                </div>
                <div class="caliweb-header-search">
                    <input class="form-input caliweb-search-input" placeholder="Search all of <?php echo $variableDefinitionX->orgShortName ?>" />
                </div>
                <div class="caliweb-nav-buttons">
                    <a href="/dashboard/accountManagement" class="caliweb-nav-button secondary"><?php echo $currentAccount->legalName; ?></a>
                    <a href="/dashboard/messageCenter" class="toggle-container" style="padding: 6px 10px 5px 10px;">
                        <span class="lnr lnr-envelope" class="toggle-input"></span>
                    </a>
                    <span class="toggle-container">
                        <span class="lnr lnr-sun" class="toggle-input" id="lightModeIcon"></span>
                        <span class="lnr lnr-moon"  class="toggle-input" id="darkModeIcon"></span>
                    </span>
                </div>
                <button style="background-color:transparent; border:none; outline:0;" href="javascript:void(0);" class="caliweb-menu-icon" aria-label="Mobile Menu" onclick="responsiveMenu()">
                    <img src="https://caliwebdesignservices.com/assets/img/systemicons/menu.svg" loading="lazy" width="24" alt="" class="menu-icon">
                </button>
            </div>
            <div class="container display-flex align-center">
                <nav class="caliweb-navbar-menu" id="caliweb-navigation">
                    <?php include($_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Utility/Backend/Dashboard/Menus/index.php"); ?>
                </nav>
                <div class="systemLoads">
                    <span>
                        <?php

                            $loads = sys_getloadavg();

                            $rounded_loads = array_map(function($load) {

                                return number_format($load, 2);

                            }, $loads);

                            echo "System Loads: " . implode(", ", $rounded_loads);

                        ?>
                    </span>
                </div>
            </div>
        </div>