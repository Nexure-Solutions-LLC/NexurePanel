<?php
    session_start();

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    $caliemail = $_SESSION['caliid'];

    use Stripe\Stripe;
    use Stripe\Account;
    use Stripe\AccountLink;

    $userprofileresult = mysqli_query($con, "SELECT * FROM caliweb_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($userprofileresult);
    mysqli_free_result($userprofileresult);

    $account_number = $userinfo['accountNumber'];

    $result = mysqli_query($con, "SELECT * FROM caliweb_paymentconfig WHERE id = '1'");
    $paymentgateway = mysqli_fetch_array($result);

    // Free payment processor check result set

    mysqli_free_result($result);

    $variableDefinitionX->apiKeysecret = $paymentgateway['secretKey'];
    $variableDefinitionX->apiKeypublic = $paymentgateway['publicKey'];
    $paymentgatewaystatus = $paymentgateway['status'];
    $paymentProcessorName = $paymentgateway['processorName'];

    // Checks type of payment processor.

    if ($variableDefinitionX->apiKeysecret != "" && $paymentgatewaystatus == "Active" || $paymentgatewaystatus == "active") {

        if ($paymentProcessorName == "Stripe") {

            Stripe::setApiKey($variableDefinitionX->apiKeysecret);

            try {
                
                $account = Account::create([
                    'type' => 'custom',
                    'country' => 'US',
                    'email' => $caliemail,
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers' => ['requested' => true],
                    ],
                    'business_type' => 'individual',
                    'business_profile' => [
                        'url' => 'https://merchantwebsite.com',
                    ],
                    'tos_acceptance' => [
                        'date' => time(),
                        'ip' => $_SERVER['REMOTE_ADDR'],
                    ],
                ]);
            
                $_SESSION['account_id'] = $account->id;
            
                $accountLink = AccountLink::create([
                    'account' => $account->id,
                    'refresh_url' => 'https:/'.$_SERVER["HTTP_HOST"].'/modules/paymentModule/stripe/paymentProcessing/merchantSignupFlow/',
                    'return_url' => 'https://'.$_SERVER["HTTP_HOST"].'/modules/paymentModule/stripe/paymentProcessing/?account_number='.$account_number.'',
                    'type' => 'account_onboarding',
                ]);
            
                header('Location: ' . $accountLink->url);
                exit;
            
            } catch (Exception $e) {

                echo 'Error: ' . $e->getMessage();

            } catch (\Throwable $exception) {
            
                \Sentry\captureException($exception);

            } 

        } else {

            echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

        }

    }

?>