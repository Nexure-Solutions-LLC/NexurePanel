<?php

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php');

    use Dotenv\Dotenv;

    $google_dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']."/modules/NexureSolutions/Oauth/google");
    $google_dotenv->load();

    $google_client_id = $_ENV['GOOGLE_CLIENT_ID'];
    $google_client_secret = $_ENV['GOOGLE_CLIENT_SECRET'];
    $google_redirect_uri = $_ENV['GOOGLE_REDIRECT_NEWAUTH_URI'];

    if ($_SESSION['pagetitle'] == "Account Management") {

        if (isset($_GET['code'])) {

            try {

                session_start();

                $google_code = $_GET['code'];
                $google_token_request = array(
                    'client_id' => $google_client_id,
                    'client_secret' => $google_client_secret,
                    'grant_type' => 'authorization_code',
                    'code' => $google_code,
                    'redirect_uri' => $google_redirect_uri
                );

                $google_curl = curl_init('https://oauth2.googleapis.com/token');

                curl_setopt($google_curl, CURLOPT_POST, true);
                curl_setopt($google_curl, CURLOPT_POSTFIELDS, http_build_query($google_token_request));
                curl_setopt($google_curl, CURLOPT_RETURNTRANSFER, true);

                $google_token_response = curl_exec($google_curl);

                curl_close($google_curl);

                $google_token_data = json_decode($google_token_response, true);

                if (isset($google_token_data['access_token'])) {

                    // Fetch user information from Google

                    $google_access_token = $google_token_data['access_token'];

                    $google_user_info_curl = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
                    curl_setopt($google_user_info_curl, CURLOPT_HTTPHEADER, array(
                        'Authorization: Bearer ' . $google_access_token
                    ));

                    curl_setopt($google_user_info_curl, CURLOPT_RETURNTRANSFER, true);

                    $google_user_response = curl_exec($google_user_info_curl);
                    curl_close($google_user_info_curl);

                    $google_user_data = json_decode($google_user_response, true);

                    $google_id = $google_user_data['id'];

                    $update_query = "UPDATE `nexure_users` SET `google_id` = '$google_id' WHERE `email` = '".$_SESSION['caliid']."'";
                    mysqli_query($con, $update_query);

                    echo '<script type="text/javascript">window.location = "/dashboard/accountManagement/accountSettings/integrations"</script>';

                } else {

                    echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

                }

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);

            }

        } else {

            // Builds the Authorize with Google Button.

            echo '
                <a 
                    class="caliweb-button primary" 
                    style="
                        background-color:#fff; 
                        border:1px solid #ddd; 
                        color: black;
                        display: flex;
                        align-items: center;
                        justify-content: center; 
                        text-align: center;
                        width:100%;
                        font-size:13px;
                        padding:6px 20px;
                        margin-top:4%;
                    "
                    href="https://accounts.google.com/o/oauth2/v2/auth?client_id='.$google_client_id.'&redirect_uri='.urlencode($google_redirect_uri).'&response_type=code&scope='.urlencode('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'). '">
                        <div style="padding:0; margin:0; margin-right:15px; margin-top:0px; display: flex; align-items: center;">
                            <?xml version="1.0" ?><svg width="35" height="35" id="Capa_1" style="enable-background:new 0 0 150 150;" version="1.1" viewBox="0 0 150 150" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><style type="text/css">
                                .st0{fill:#1A73E8;}
                                .st1{fill:#EA4335;}
                                .st2{fill:#4285F4;}
                                .st3{fill:#FBBC04;}
                                .st4{fill:#34A853;}
                                .st5{fill:#4CAF50;}
                                .st6{fill:#1E88E5;}
                                .st7{fill:#E53935;}
                                .st8{fill:#C62828;}
                                .st9{fill:#FBC02D;}
                                .st10{fill:#1565C0;}
                                .st11{fill:#2E7D32;}
                                .st12{fill:#F6B704;}
                                .st13{fill:#E54335;}
                                .st14{fill:#4280EF;}
                                .st15{fill:#34A353;}
                                .st16{clip-path:url(#SVGID_2_);}
                                .st17{fill:#188038;}
                                .st18{opacity:0.2;fill:#FFFFFF;enable-background:new    ;}
                                .st19{opacity:0.3;fill:#0D652D;enable-background:new    ;}
                                .st20{clip-path:url(#SVGID_4_);}
                                .st21{opacity:0.3;fill:url(#_45_shadow_1_);enable-background:new    ;}
                                .st22{clip-path:url(#SVGID_6_);}
                                .st23{fill:#FA7B17;}
                                .st24{opacity:0.3;fill:#174EA6;enable-background:new    ;}
                                .st25{opacity:0.3;fill:#A50E0E;enable-background:new    ;}
                                .st26{opacity:0.3;fill:#E37400;enable-background:new    ;}
                                .st27{fill:url(#Finish_mask_1_);}
                                .st28{fill:#FFFFFF;}
                                .st29{fill:#0C9D58;}
                                .st30{opacity:0.2;fill:#004D40;enable-background:new    ;}
                                .st31{opacity:0.2;fill:#3E2723;enable-background:new    ;}
                                .st32{fill:#FFC107;}
                                .st33{opacity:0.2;fill:#1A237E;enable-background:new    ;}
                                .st34{opacity:0.2;}
                                .st35{fill:#1A237E;}
                                .st36{fill:url(#SVGID_7_);}
                                .st37{fill:#FBBC05;}
                                .st38{clip-path:url(#SVGID_9_);fill:#E53935;}
                                .st39{clip-path:url(#SVGID_11_);fill:#FBC02D;}
                                .st40{clip-path:url(#SVGID_13_);fill:#E53935;}
                                .st41{clip-path:url(#SVGID_15_);fill:#FBC02D;}
                            </style><g><path class="st14" d="M120,76.1c0-3.1-0.3-6.3-0.8-9.3H75.9v17.7h24.8c-1,5.7-4.3,10.7-9.2,13.9l14.8,11.5   C115,101.8,120,90,120,76.1L120,76.1z"/><path class="st15" d="M75.9,120.9c12.4,0,22.8-4.1,30.4-11.1L91.5,98.4c-4.1,2.8-9.4,4.4-15.6,4.4c-12,0-22.1-8.1-25.8-18.9   L34.9,95.6C42.7,111.1,58.5,120.9,75.9,120.9z"/><path class="st12" d="M50.1,83.8c-1.9-5.7-1.9-11.9,0-17.6L34.9,54.4c-6.5,13-6.5,28.3,0,41.2L50.1,83.8z"/><path class="st13" d="M75.9,47.3c6.5-0.1,12.9,2.4,17.6,6.9L106.6,41C98.3,33.2,87.3,29,75.9,29.1c-17.4,0-33.2,9.8-41,25.3   l15.2,11.8C53.8,55.3,63.9,47.3,75.9,47.3z"/></g></svg>
                        </div>
                        <div style="margin:0; padding:0;">
                            <span style="margin:0; padding:0;">Authorize with Google</span>
                        </div>
                </a>
            ';
        
        }

    }
?>
