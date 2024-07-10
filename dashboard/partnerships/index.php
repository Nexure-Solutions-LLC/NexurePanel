<?php
    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    if ($userrole == "Customer" || $userrole == "customer") {

        header("location:/dashboard/customers");

    } else if ($userrole == "Authorized User" || $userrole == "authorized user") {

        header("location:/dashboard/customers/authorizedUserView");

    } else if ($userrole == "Administrator" || $userrole == "administrator") {

        header("location:/dashboard/administration");
        
    }
?>

<?php
    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');
?>