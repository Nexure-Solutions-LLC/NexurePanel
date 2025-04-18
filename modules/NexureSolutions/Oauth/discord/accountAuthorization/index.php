<?php

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php');

    use Dotenv\Dotenv;

    $discord_dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']."/modules/NexureSolutions/Discord");

    $discord_dotenv->load();

    $discord_client_id = $_ENV['DISCORD_CLIENT_ID'];

    $discord_client_secret = $_ENV['DISCORD_CLIENT_SECRET'];

    $discord_newauth_redirect_uri = $_ENV['DISCORD_REDIRECT_NEWAUTH_URI'];

    if ($_SESSION['pagetitle'] == "Account Management | Administrator") {

        if (isset($_GET['code'])) {

            try {

                session_start();

                $discord_code = $_GET['code'];
                $discord_token_request = array(
                    'client_id' => $discord_client_id,
                    'client_secret' => $discord_client_secret,
                    'grant_type' => 'authorization_code',
                    'code' => $discord_code,
                    'redirect_uri' => $discord_newauth_redirect_uri,
                    'scope' => 'identify email'
                );
            
                $discord_curl = curl_init('https://discord.com/api/oauth2/token');

                curl_setopt($discord_curl, CURLOPT_POST, true);

                curl_setopt($discord_curl, CURLOPT_POSTFIELDS, http_build_query($discord_token_request));

                curl_setopt($discord_curl, CURLOPT_RETURNTRANSFER, true);

                $discord_token_response = curl_exec($discord_curl);

                curl_close($discord_curl);

                $discord_token_data = json_decode($discord_token_response, true);
                
            
                if (isset($discord_token_data['access_token'])) {
                    
                    // Fetch user information from Discord

                    $discord_access_token = $discord_token_data['access_token'];

                    $discord_curl = curl_init('https://discord.com/api/users/@me');
                    
                    curl_setopt($discord_curl, CURLOPT_HTTPHEADER, array(
                        'Authorization: Bearer ' . $discord_access_token
                    ));

                    curl_setopt($discord_curl, CURLOPT_RETURNTRANSFER, true);

                    $discord_user_response = curl_exec($discord_curl);

                    curl_close($discord_curl);

                    $discord_user_data = json_decode($discord_user_response, true);

                    $discord_id = $discord_user_data['id'];
                    
                    $update_query = "UPDATE `nexure_users` SET `discord_id` = '$discord_id' WHERE `email` = '".$_SESSION['caliid']."'";

                    mysqli_query($con, $update_query);

                    echo '<script type="text/javascript">window.location = "/dashboard/accountManagement/integrations/"</script>';
                        
                } else {

                    echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

                }
                
            } catch (\Throwable $exception) {
            
                \Sentry\captureException($exception);
                
            }

        }

    }

?>