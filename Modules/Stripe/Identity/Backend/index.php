<?php

    namespace Modules\Stripe\Identity;

    require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

    class IdentityVerificationHandler {

        public static function createVerificationSession(array $sessionData): ?string {

            try {

                \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

                $session = \Stripe\Identity\VerificationSession::create([
                    'type' => 'document',
                    'metadata' => [
                        'user_id' => $sessionData['user_id'] ?? 'guest',
                        'nexure_application' => $sessionData['nexure_application'] ?? 'unknown',
                    ],
                    'return_url' => $sessionData['return_url'] ?? '',
                    'cancel_url' => $sessionData['cancel_url'] ?? '',
                ]);

                return $session->url;

            } catch (\Exception $e) {

                error_log('Stripe Identity Error: ' . $e->getMessage());

                return null;

            }

        }

    }

?>