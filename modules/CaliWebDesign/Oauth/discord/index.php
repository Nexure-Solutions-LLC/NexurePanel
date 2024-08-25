<?php
    ob_start();

    // Cali Web Design Discord OAuth Module
    // Version: 2.0.1
    // (C) Copyright Cali Web Design Services LLC - All rights reserved
    // DISMANTLING, REVERSE ENGINEERING, OR MODIFICATION OF THIS MODULE IS PROHIBITED.

    // This module is not included in the Cali Panel software by default
    // the Cali Web Design Discord OAuth Module will allow you to authenicate
    // using your discord account into the Cali Panel. This can save time instead
    // of needing to fill our registration form our you can just quickly
    // authenicate.

    // Checks to see if the module is enabled, This is required if the OAuth module is loaded.

    use Dotenv\Dotenv;

    if ($authModulesName == "Discord OAuth" && $authModulesDepends == "Present") {

        if ($_SESSION['pagetitle'] == "Login Page") {

            // Loads Variables from ENV so that the OAuth link can be built.

            $discord_dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']."/modules/CaliWebDesign/Oauth/discord");
            $discord_dotenv->load();

            $discord_client_id = $_ENV['DISCORD_CLIENT_ID'];
            $discord_client_secret = $_ENV['DISCORD_CLIENT_SECRET'];
            $discord_redirect_uri = $_ENV['DISCORD_REDIRECT_URI'];

            // Builds the OAuth Discord Button.

            echo '
                <a 
                    class="caliweb-button primary" 
                    style="
                        background-color:#5865F2; 
                        border:1px solid #5865F2; 
                        display: flex;
                        align-items: center;
                        justify-content: center; 
                        text-align: center;
                        width:100%;
                        font-size:13px;
                        padding:6px 20px;
                    "
                    href="https://discord.com/api/oauth2/authorize?client_id='.$discord_client_id.'&redirect_uri='.urlencode($discord_redirect_uri).'&response_type=code&scope=identify email">
                        <div style="padding:0; margin:0; margin-right:15px; margin-top:4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" style="padding:0; margin:0;" width="25" height="25" viewBox="0 0 127.14 96.36">
                                <path fill="#fff" d="M107.7,8.07A105.15,105.15,0,0,0,81.47,0a72.06,72.06,0,0,0-3.36,6.83A97.68,97.68,0,0,0,49,6.83,72.37,72.37,0,0,0,45.64,0,105.89,105.89,0,0,0,19.39,8.09C2.79,32.65-1.71,56.6.54,80.21h0A105.73,105.73,0,0,0,32.71,96.36,77.7,77.7,0,0,0,39.6,85.25a68.42,68.42,0,0,1-10.85-5.18c.91-.66,1.8-1.34,2.66-2a75.57,75.57,0,0,0,64.32,0c.87.71,1.76,1.39,2.66,2a68.68,68.68,0,0,1-10.87,5.19,77,77,0,0,0,6.89,11.1A105.25,105.25,0,0,0,126.6,80.22h0C129.24,52.84,122.09,29.11,107.7,8.07ZM42.45,65.69C36.18,65.69,31,60,31,53s5-12.74,11.43-12.74S54,46,53.89,53,48.84,65.69,42.45,65.69Zm42.24,0C78.41,65.69,73.25,60,73.25,53s5-12.74,11.44-12.74S96.23,46,96.12,53,91.08,65.69,84.69,65.69Z"/>
                            </svg>
                        </div>
                        <div style="margin:0; padding:0;">
                            <span style="margin:0; padding:0;">'.$LANG_LOGIN_DISCORD_BUTTON.'</span>
                        </div>
                </a>
            ';

        } else if ($_SESSION['pagetitle'] == "Dashboard") {

            header("location:/modules/CaliWebDesign/Oauth/discord/callback");
            
        }

    } else {
        
    }
?>
