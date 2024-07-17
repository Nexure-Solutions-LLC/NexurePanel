<?php
    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    $lowerrole = strtolower($userrole);
    
    switch ($lowerrole) {
        case "customer":
            header("location:/dashboard/customers");
            break;
        case "authorized user":
            header("location:/dashboard/customers/authorizedUserView");
            break;
        case "administrator":
            header("location:/dashboard/administration");
            break;
    }
?>

<?php
    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');
?>