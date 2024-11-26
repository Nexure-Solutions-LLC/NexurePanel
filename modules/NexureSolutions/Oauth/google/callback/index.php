<?php

    session_start();

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    require($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php');

    use Dotenv\Dotenv;

    $google_dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']."/modules/NexureSolutions/Oauth/google");

    $google_dotenv->load();

    $google_client_id = $_ENV['GOOGLE_CLIENT_ID'];

    $google_client_secret = $_ENV['GOOGLE_CLIENT_SECRET'];

    $google_redirect_uri = $_ENV['GOOGLE_REDIRECT_URI'];

    if (isset($_GET['code'])) {

        try {

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

                $google_curl = curl_init('https://www.googleapis.com/oauth2/v1/userinfo?alt=json');

                curl_setopt($google_curl, CURLOPT_HTTPHEADER, array(
                    'Authorization: Bearer ' . $google_access_token
                ));
                
                curl_setopt($google_curl, CURLOPT_RETURNTRANSFER, true);

                $google_user_response = curl_exec($google_curl);
                
                curl_close($google_curl);

                $google_user_data = json_decode($google_user_response, true);

                $google_id = $google_user_data['id'];

                $google_query = "SELECT * FROM `nexure_users` WHERE `google_id` = '$google_id'";

                $google_result = mysqli_query($con, $google_query);

                $google_rows = mysqli_num_rows($google_result);

                if ($google_rows == 1) {

                    $_SESSION['caligoogleid'] = $google_id;

                    if (isset($_SESSION['caligoogleid'])) {

                        // Perform query

                        $google_query = mysqli_query($con, "SELECT * FROM nexure_users WHERE google_id = '".$_SESSION['caligoogleid']."'");

                        $google_info = mysqli_fetch_array($google_query);
                        
                        // Free result set

                        mysqli_free_result($google_query);
                
                        $caliid = $google_info['email'];
                        
                        $_SESSION['caliid'] = $caliid;

                        if (isset($_SESSION['caliid'])) {

                            echo '<script type="text/javascript">window.location = "/dashboard"</script>';

                        } else {

                            echo '<script type="text/javascript">window.location = "/login"</script>';

                        }

                    } else {

                        echo '<script type="text/javascript">window.location = "/login"</script>';

                    }

                } else {

                    echo '<script type="text/javascript">window.location = "/login"</script>';

                }

            } else {

                echo '<script type="text/javascript">window.location = "/login"</script>';
            }

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
                    
        }

    }

?>
