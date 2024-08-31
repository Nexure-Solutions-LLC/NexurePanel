<?php

    ob_start();

    // Cali Web Design GitHub OAuth Module
    // Version: 2.0.1
    // (C) Copyright Cali Web Design Services LLC - All rights reserved
    // DISMANTLING, REVERSE ENGINEERING, OR MODIFICATION OF THIS MODULE IS PROHIBITED.

    // This module is not included in the Cali Panel software by default
    // the Cali Web Design GitHub OAuth Module will allow you to authenicate
    // using your github account into the Cali Panel. This can save time instead
    // of needing to fill our registration form our you can just quickly
    // authenicate.

    // Checks to see if the module is enabled, This is required if the OAuth module is loaded.

    use Dotenv\Dotenv;

    if ($authModulesName == "GitHub OAuth" && $authModulesDepends == "Present") {

        if ($_SESSION['pagetitle'] == "Login Page") {

            // Loads Variables from ENV so that the OAuth link can be built.

            $github_dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . "/modules/CaliWebDesign/Oauth/github");
            $github_dotenv->load();

            $github_client_id = $_ENV['GITHUB_CLIENT_ID'];
            $github_client_secret = $_ENV['GITHUB_CLIENT_SECRET'];
            $github_redirect_uri = $_ENV['GITHUB_REDIRECT_URI'];

            // Builds the OAuth Discord Button.

            echo '
                        <a 
                            class="caliweb-button primary" 
                            style="
                                background-color:#238636; 
                                border:1px solid #238636; 
                                display: flex;
                                align-items: center;
                                justify-content: center; 
                                text-align: center;
                                width:100%;
                                font-size:13px;
                                padding:8px 20px;
                            "
                            href="https://github.com/login/oauth/authorize?client_id=' . $github_client_id . '&redirect_uri=' . urlencode($github_redirect_uri) . '&response_type=code&scope=user">
                                <div style="padding:0; margin:0; margin-right:15px; margin-top:0px; display: flex; align-items: center;">
                                    <svg height="30" width="30" style="fill:#fff!important;" aria-hidden="true" viewBox="0 0 16 16" version="1.1" data-view-component="true" class="octicon octicon-mark-github v-align-middle color-fg-default"><path d="M8 0c4.42 0 8 3.58 8 8a8.013 8.013 0 0 1-5.45 7.59c-.4.08-.55-.17-.55-.38 0-.27.01-1.13.01-2.2 0-.75-.25-1.23-.54-1.48 1.78-.2 3.65-.88 3.65-3.95 0-.88-.31-1.59-.82-2.15.08-.2.36-1.02-.08-2.12 0 0-.67-.22-2.2.82-.64-.18-1.32-.27-2-.27-.68 0-1.36.09-2 .27-1.53-1.03-2.2-.82-2.2-.82-.44 1.1-.16 1.92-.08 2.12-.51.56-.82 1.28-.82 2.15 0 3.06 1.86 3.75 3.64 3.95-.23.2-.44.55-.51 1.07-.46.21-1.61.55-2.33-.66-.15-.24-.6-.83-1.23-.82-.67.01-.27.38.01.53.34.19.73.9.82 1.13.16.45.68 1.31 2.69.94 0 .67.01 1.3.01 1.49 0 .21-.15.45-.55.38A7.995 7.995 0 0 1 0 8c0-4.42 3.58-8 8-8Z"></path></svg>
                                </div>
                                <div style="margin:0; padding:0;">
                                    <span style="margin:0; padding:0;">' . $LANG_LOGIN_GITHUB_BUTTON . '</span>
                                </div>
                        </a>
                    ';
        } else if ($_SESSION['pagetitle'] == "Dashboard") {

            header("location:/modules/CaliWebDesign/Oauth/github/callback");

        }

    } else {


    }

?>