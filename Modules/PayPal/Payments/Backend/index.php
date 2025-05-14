<?php

    // Nexure Solutions LLP (C) 2025 - All rights reserved.
    // This is the PayPal payment handler for Nexure CRM. This is can be used for other things but
    // was adapted specifically for Nexure CRM.
    // Author: Nexure Developers

    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Configuration/Database/index.php');

    use GuzzleHttp\Client;

    function fetchPayPalCredentials(mysqli $con): ?array
    {
        $query = "SELECT clientID, secretKey FROM nexure_payments WHERE processorName = 'PayPal' LIMIT 1";
        $result = $con->query($query);

        if ($result && $row = $result->fetch_assoc()) {
            return [
                'client_id' => $row['clientID'],
                'secret_key' => $row['secretKey']
            ];
        }

        return null;
    }

    function getPayPalAccessToken(array $credentials): ?string
    {
        $client = new Client();

        $response = $client->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
            'auth' => [$credentials['client_id'], $credentials['secret_key']],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $body = json_decode($response->getBody(), true);
            return $body['access_token'] ?? null;
        }

        return null;
    }

    function createPayPalPayment(string $accessToken, float $amount, string $currency = 'USD'): ?array
    {
        $client = new Client();

        $response = $client->post('https://api-m.sandbox.paypal.com/v2/checkout/orders', [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => strtoupper($currency),
                        'value' => number_format($amount, 2, '.', '')
                    ]
                ]]
            ]
        ]);

        if ($response->getStatusCode() === 201) {
            return json_decode($response->getBody(), true);
        }

        return null;
    }

    function capturePayPalPayment(string $accessToken, string $orderId): ?array
    {
        $client = new Client();

        $response = $client->post("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderId}/capture", [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ]
        ]);

        if ($response->getStatusCode() === 201 || $response->getStatusCode() === 200) {
            return json_decode($response->getBody(), true);
        }

        return null;
    }

?>