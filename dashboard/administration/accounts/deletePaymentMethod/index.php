<?php

    // Initialize page variables

    unset($_SESSION['pagetitle']);
    $_SESSION['pagetitle'] = "Payment Methods";
    $pagetitle = "Payment Methods";
    $pagesubtitle = "Delete Payment Method";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    // Retrieve account number from query parameters and check the account number and if its present

    $accountnumber = $_GET['account_number'] ?? '';
    $paymentid = $_GET['payment_method_id'] ?? '';

    if (empty($accountnumber) && empty($paymentid)) {

        header("Location: /dashboard/administration/accounts");
        exit;

    }

    $manageAccountDefinitionR = new \CaliWebDesign\Generic\VariableDefinitions();
    $manageAccountDefinitionR->manageAccount($con, $accountnumber);

    echo '<title>' . htmlspecialchars($pagetitle) . ' | ' . htmlspecialchars($pagesubtitle) . '</title>';

    if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

        if ($variableDefinitionX->paymentProcessorName == "Stripe") {

            require($_SERVER["DOCUMENT_ROOT"].'/modules/paymentModule/stripe/internalPayments/index.php');

        }
    
    }

    delete_paymentmethod($paymentid);

    header("Location: /dashboard/administration/accounts/manageAccount/paymentMethods/?account_number=$accountnumber");

?>
