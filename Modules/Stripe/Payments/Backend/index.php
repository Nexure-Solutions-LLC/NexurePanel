<?php

    // Nexure Solutions LLP (C) 2025 - All rights reserved.
    // This is the Stripe payment handler for Nexure CRM. This is can be used for other things but
    // was adapted specifically for Nexure CRM.
    // Author: Nexure Developers

    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Configuration/Database/index.php');

    use Stripe\StripeClient;

    function fetchStripeSecretKey(mysqli $con): ?string
    {

        $query = "SELECT secretKey FROM nexure_payments LIMIT 1";

        $result = $con->query($query);

        if ($result && $row = $result->fetch_assoc()) {

            return $row['secretKey'];

        }

        return null;

    }

    function initStripe(mysqli $con): StripeClient
    {
        $secretKey = fetchStripeSecretKey($con);

        if (!$secretKey) {

            throw new Exception("Stripe secret key not found.");

        }

        \Stripe\Stripe::setApiKey($secretKey);

        return new StripeClient($secretKey);

    }

    function processPayment(StripeClient $stripe, string $customerId, int $amountCents, string $currency, string $paymentMethodId): \Stripe\PaymentIntent
    {

        return $stripe->paymentIntents->create([
            'amount' => $amountCents,
            'currency' => $currency,
            'customer' => $customerId,
            'payment_method' => $paymentMethodId,
            'off_session' => true,
            'confirm' => true,
        ]);

    }

    function getCardInfo(StripeClient $stripe, string $paymentMethodId): array
    {

        $pm = $stripe->paymentMethods->retrieve($paymentMethodId);

        return [
            'issuer' => $pm->card->issuer ?? 'Unknown',
            'last4' => $pm->card->last4,
            'brand' => $pm->card->brand,
            'type' => $pm->card->funding,
            'exp_month' => $pm->card->exp_month,
            'exp_year' => $pm->card->exp_year
        ];

    }

    function getRiskScore(StripeClient $stripe, string $paymentIntentId): ?string
    {

        $intent = $stripe->paymentIntents->retrieve($paymentIntentId);

        return $intent->charges->data[0]->outcome->risk_level ?? null;

    }

    function createSubscription(StripeClient $stripe, string $customerId, string $priceId): \Stripe\Subscription
    {

        return $stripe->subscriptions->create([
            'customer' => $customerId,
            'items' => [['price' => $priceId]],
            'expand' => ['latest_invoice.payment_intent']
        ]);

    }

    function getCreditBalance(StripeClient $stripe, string $customerId): float
    {

        $customer = $stripe->customers->retrieve($customerId);
        
        $creditBalanceInCents = $customer->balance;
        
        return $creditBalanceInCents / 100.0;

    }

?>
