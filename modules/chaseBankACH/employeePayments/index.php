<?php

    // Cali Web Design Chase Module
    // Version: 1.2.5
    // (C) Copyright Cali Web Design Services LLC - All rights reserved
    // DISMANTLING, REVERSE ENGINEERING, OR MODIFICATION OF THIS MODULE IS PROHIBITED.
    // Employee Payments Sub Module for Cali Web Design Chase Module

    // This module is not included in the Cali Panel software by default
    // the Cali Web Design Chase Module will allow you to initate
    // payments to employees and vendors to run a small version of
    // payroll, this module requires the payroll module.
    // You can pair this module with our payroll module or other bank modules.

    // YOU MUST HAVE A CHASE ACCOUNT AND ACCESS TO THE CHASE API TO USE THIS Module
    // IF YOU ARE A CHASE CUSTOMER AND NEED API ACCESS REGISTER HERE:
    // https://developer.chase.com/

    function getAccessToken($clientId, $clientSecret, $authUrl) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $authUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic " . base64_encode($clientId . ":" . $clientSecret),
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            return null;
        } else {
            $responseArray = json_decode($response, true);
            return $responseArray['access_token'];
        }
    }

    function initiateACHPayment($accessToken, $apiUrl, $paymentData) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($paymentData),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $accessToken,
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            return null;
        } else {
            return json_decode($response, true);
        }
    }

    $clientId = "";
    $clientSecret = "";
    $authUrl = "";
    $apiUrl = "";

    // Get the access token
    $accessToken = getAccessToken($clientId, $clientSecret, $authUrl);

    if ($accessToken) {
        // Define the payment data
        $paymentData = array(
            "amount" => "1000.00", // Amount in USD
            "recipient" => array(
                "name" => "John Doe",
                "bankAccount" => "123456789",
                "routingNumber" => "987654321",
            ),
            "description" => "Payroll for May 2024",
            "currency" => "USD",
        );

        // Initiate the ACH payment
        $response = initiateACHPayment($accessToken, $apiUrl, $paymentData);

        if ($response) {
            echo "Payment initiated successfully: ";
            print_r($response);
        } else {
            echo "Failed to initiate payment.";
        }
    } else {
        echo "Failed to get access token.";
    }

?>