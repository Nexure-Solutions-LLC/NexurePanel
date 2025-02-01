<?php

    $pagetitle = "";
    $pagetype = "";


    require($_SERVER["DOCUMENT_ROOT"] . "/modules/NexureSolutions/Utility/Backend/System/Dashboard.php");


    use Dotenv\Dotenv;


    $discord_dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']."/modules/NexureSolutions/Discord");
    $discord_dotenv->load();


    $sql = "SELECT discord_id, userrole FROM nexure_users WHERE email = '$caliemail'";
    $result = $con->query($sql);
    $user = $result->fetch_assoc();
    $discordId = $user['discord_id'];
    $accessLevel = $user['userrole'];


    $roles = [
        'Administrator' => 1335123421641576508,
        'Partner' => 1335123416700944597,
        'Authorized User' => 1335123137934921811,
        'Customer' => 1335123137934921811
    ];

    $roleId = $roles[$accessLevel];

    if (!isset($_SESSION['access_token'])) {

        header("Location: /error/genericSystemError");
        
    }

    $accessToken = $_SESSION['access_token'];
    $clientId = $_ENV['DISCORD_CLIENT_ID'];

    $metadata = [
        "metadata" => [
            "role" => $accessLevel,
        ]
    ];
    
    $url = "https://discord.com/api/v10/users/@me/applications/$clientId/role-connection";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($metadata));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json"
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    header("location: ./success/");

?>