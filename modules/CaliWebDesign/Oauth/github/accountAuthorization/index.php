<?php

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');

    require($_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php');

    use Dotenv\Dotenv;

    $github_dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . "/modules/CaliWebDesign/Oauth/github");
    $github_dotenv->load();

    $github_client_id = $_ENV['GITHUB_CLIENT_ID'];

    $github_client_secret = $_ENV['GITHUB_CLIENT_SECRET'];

    $github_redirect_uri = $_ENV['GITHUB_REDIRECT_NEWAUTH_URI'];

    if ($_SESSION['pagetitle'] == "Account Management") {

        if (isset($_GET['code'])) {

            try {

                $github_code = $_GET['code'];

                $github_token_request = array(
                    'client_id' => $github_client_id,
                    'client_secret' => $github_client_secret,
                    'code' => $github_code,
                    'redirect_uri' => $github_redirect_uri
                );

                $github_curl = curl_init('https://github.com/login/oauth/access_token');

                curl_setopt($github_curl, CURLOPT_POST, true);

                curl_setopt($github_curl, CURLOPT_POSTFIELDS, http_build_query($github_token_request));

                curl_setopt($github_curl, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($github_curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));

                $github_token_response = curl_exec($github_curl);

                curl_close($github_curl);

                $github_token_data = json_decode($github_token_response, true);

                if (isset($github_token_data['access_token'])) {

                    // Fetch user information from GitHub

                    $github_access_token = $github_token_data['access_token'];

                    $github_curl = curl_init('https://api.github.com/user');

                    curl_setopt($github_curl, CURLOPT_HTTPHEADER, array(
                        'Authorization: Bearer ' . $github_access_token,
                        'User-Agent: CaliWebDesign'
                    ));

                    curl_setopt($github_curl, CURLOPT_RETURNTRANSFER, true);

                    $github_user_response = curl_exec($github_curl);

                    curl_close($github_curl);

                    $github_user_data = json_decode($github_user_response, true);

                    $github_id = $github_user_data['id'];

                    $update_query = "UPDATE `nexure_users` SET `github_id` = '$github_id' WHERE `email` = '" . $_SESSION['caliid'] . "'";

                    mysqli_query($con, $update_query);

                    echo '<script type="text/javascript">window.location = "/dashboard/accountManagement/accountSettings/integrations"</script>';

                } else {

                    echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

                }

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);
            }

        } else {

            // Build the Authorize with GitHub Button

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
                        padding:6px 20px;
                        margin-top:4%;
                    "
                    href="https://github.com/login/oauth/authorize?client_id=' . $github_client_id . '&redirect_uri=' . urlencode($github_redirect_uri) . '&scope=user:email">
                        <div style="padding:0; margin:0; margin-right:15px; margin-top:0px;">
                            <svg height="30" width="30" style="fill:#fff!important;" aria-hidden="true" viewBox="0 0 16 16" version="1.1" data-view-component="true" class="octicon octicon-mark-github v-align-middle color-fg-default"><path d="M8 0c4.42 0 8 3.58 8 8a8.013 8.013 0 0 1-5.45 7.59c-.4.08-.55-.17-.55-.38 0-.27.01-1.13.01-2.2 0-.75-.25-1.23-.54-1.48 1.78-.2 3.65-.88 3.65-3.95 0-.88-.31-1.59-.82-2.15.08-.2.36-1.02-.08-2.12 0 0-.67-.22-2.2.82-.64-.18-1.32-.27-2-.27-.68 0-1.36.09-2 .27-1.53-1.03-2.2-.82-2.2-.82-.44 1.1-.16 1.92-.08 2.12-.51.56-.82 1.28-.82 2.15 0 3.06 1.86 3.75 3.64 3.95-.23.2-.44.55-.51 1.07-.46.21-1.61.55-2.33-.66-.15-.24-.6-.83-1.23-.82-.67.01-.27.38.01.53.34.19.73.9.82 1.13.16.45.68 1.31 2.69.94 0 .67.01 1.3.01 1.49 0 .21-.15.45-.55.38A7.995 7.995 0 0 1 0 8c0-4.42 3.58-8 8-8Z"></path></svg>
                        </div>
                        <div style="margin:0; padding:0;">
                            <span style="margin:0; padding:0;">Sign in with GitHub</span>
                        </div>
                </a>

            ';

        }

    }
    
?>