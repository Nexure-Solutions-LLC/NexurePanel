<?php

    $pagetitle = "";
    $pagetype = "";

    require($_SERVER["DOCUMENT_ROOT"] . "/modules/NexureSolutions/Utility/Backend/System/Dashboard.php");


    use Dotenv\Dotenv;


    $discord_dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']."/modules/NexureSolutions/Discord");
    $discord_dotenv->load();


    $client_id = $_ENV['DISCORD_CLIENT_ID'];
    $client_secret = $_ENV['DISCORD_CLIENT_SECRET'];
    $redirect_uri = $_ENV['DISCORD_LINKEDROLES_REDIRECT_URI'];


    if (isset($_GET['code'])) {

        $code = $_GET['code'];


        $url = "https://discord.com/api/oauth2/token";
        $data = [
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => $redirect_uri,
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded"
        ]);


        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);


        if (isset($result['access_token'])) {

            $access_token = $result['access_token'];
            $_SESSION['access_token'] = $access_token;

            header("Location: ../completedLinkage/");
            exit;

        } else {

            header("Location: /error/genericSystemError");

        }

    } else {

        header("Location: /error/genericSystemError");
        
    }

?>
