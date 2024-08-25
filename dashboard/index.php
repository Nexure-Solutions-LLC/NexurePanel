<?php

    $pagetitle = "";
    $pagesubtitle = "";
    $pagetype = "";
     
    require($_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Utility/Backend/System/Dashboard.php");

    $accountnumber = $currentAccount->accountNumber;

    if (!$accountnumber) {
        header("Location:/error/genericSystemError");
    }

    $manageAccountDefinitionR = new \CaliWebDesign\Generic\VariableDefinitions();
    $manageAccountDefinitionR->manageAccount($con, $accountnumber);

    $upperrole = $manageAccountDefinitionR->userrole;
    $lowerrole = strtolower($upperrole);

    switch ($lowerrole) {
        case "authorized user":
            header("location:/dashboard/customers/authorizedUserView");
            break;
        case "partner":
            header("location:/dashboard/partnerships");
            break;
        case "administrator":
            header("location:/dashboard/administration");
            break;
        case 'customer':
            header("location:/dashboard/customers");
            break;
        default:
            header("Location: /error/genericSystemError/");
            break;
    }

?>