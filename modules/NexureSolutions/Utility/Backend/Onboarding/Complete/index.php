<?php

    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php');
    require($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/index.php");

    $caliemail = $_SESSION['caliid'];

    $currentAccount = new \NexureSolutions\Accounts\AccountHandler($con);
    $success = $currentAccount->fetchByEmail($caliemail);

    $variableDefinitionX = new \NexureSolutions\Generic\VariableDefinitions();
    $variableDefinitionX->variablesHeader($con);

    if ($currentAccount->accountStatus->name == "Active") {

        header ("Location: /dashboard/customers/");

    } else if ($currentAccount->accountStatus->name == "Suspended") {

        header ("Location: /error/suspendedAccount");

    } else if ($currentAccount->accountStatus->name == "Terminated") {

        header ("Location: /error/terminatedAccount");

    }

?>