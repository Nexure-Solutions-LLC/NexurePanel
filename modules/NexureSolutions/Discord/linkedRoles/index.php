<?php

    $pagetitle = "Linked Roles";
    $pagetype = "";

    require($_SERVER["DOCUMENT_ROOT"] . "/modules/NexureSolutions/Utility/Backend/System/Dashboard.php");

    use Dotenv\Dotenv;


    $discord_dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']."/modules/NexureSolutions/Discord");
    $discord_dotenv->load();


    $client_id = $_ENV['DISCORD_CLIENT_ID'];
    $client_secret = $_ENV['DISCORD_CLIENT_SECRET'];
    $redirect_uri = $_ENV['DISCORD_LINKEDROLES_REDIRECT_URI'];

    unset($_SESSION["referral_url"]);

    $auth_url = "https://discord.com/api/oauth2/authorize?client_id=$client_id&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&scope=identify%20role_connections.write";

    
    header("Location: $auth_url");
    exit;

?>