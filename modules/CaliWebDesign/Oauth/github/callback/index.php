<?php

    session_start();

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    
    require($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php');

    use Dotenv\Dotenv;

    // Load environment variables for GitHub

    $github_dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']."/modules/CaliWebDesign/Oauth/github");

    $github_dotenv->load();

    $github_client_id = $_ENV['GITHUB_CLIENT_ID'];

    $github_client_secret = $_ENV['GITHUB_CLIENT_SECRET'];

    $github_redirect_uri = $_ENV['GITHUB_REDIRECT_URI'];

    if (isset($_GET['code'])) {

        try {

            $github_code = $_GET['code'];
            $github_token_request = array(
                'client_id' => $github_client_id,
                'client_secret' => $github_client_secret,
                'code' => $github_code,
                'redirect_uri' => $github_redirect_uri
            );

            // Exchange the authorization code for an access token

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

                $github_user_info_curl = curl_init('https://api.github.com/user');

                curl_setopt($github_user_info_curl, CURLOPT_HTTPHEADER, array(
                    'Authorization: Bearer ' . $github_access_token,
                    'User-Agent: CaliWebDesign' // GitHub API requires a User-Agent header
                ));
                
                curl_setopt($github_user_info_curl, CURLOPT_RETURNTRANSFER, true);

                $github_user_response = curl_exec($github_user_info_curl);

                curl_close($github_user_info_curl);

                $github_user_data = json_decode($github_user_response, true);

                $github_id = $github_user_data['id'];

                $github_query = "SELECT * FROM `caliweb_users` WHERE `github_id` = '$github_id'";

                $github_result = mysqli_query($con, $github_query);

                $github_rows = mysqli_num_rows($github_result);

                if ($github_rows == 1) {

                    $_SESSION['caligithubid'] = $github_id;

                    if (isset($_SESSION['caligithubid'])) {

                        // Perform query

                        $github_query = mysqli_query($con, "SELECT * FROM caliweb_users WHERE github_id = '".$_SESSION['caligithubid']."'");

                        $github_info = mysqli_fetch_array($github_query);
                        
                        // Free result set

                        mysqli_free_result($github_query);
                
                        $caliid = $github_info['email'];

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
