<?php

    // Initialize page variables

    unset($_SESSION['pagetitle']);
    $_SESSION['pagetitle'] = "Payment Methods";
    $pagetitle = "Payment Methods";
    $pagesubtitle = "Delete your Payment Method";
    $pagetype = "Client";

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    // Retrieve account number from query parameters and check the account number and if its present

    $email_address = $_GET['email_address'] ?? '';
    $paymentid = $_GET['payment_method_id'] ?? '';

    if (empty($email_address) && empty($paymentid)) {

        header("Location: /dashboard/customers/billingCenter");
        exit;

    }

    echo '<title>' . htmlspecialchars($pagetitle) . ' | ' . htmlspecialchars($pagesubtitle) . '</title>';

    if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

        if ($variableDefinitionX->paymentProcessorName == "Stripe") {

            require($_SERVER["DOCUMENT_ROOT"].'/modules/paymentModule/stripe/internalPayments/index.php');

        }
    
    }

    delete_paymentmethod($paymentid);

    header("Location: /dashboard/customers/billingCenter/");

?>
