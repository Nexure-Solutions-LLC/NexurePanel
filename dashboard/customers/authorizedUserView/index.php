<?php
    $pagetitle = "Client";
    $pagesubtitle = "Overview";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    $lowerrole = strtolower($userrole);
    
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
    }


?>

<?php
    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');
?>