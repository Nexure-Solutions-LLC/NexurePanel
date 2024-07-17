<?php
    $pagetitle = "Contacts";
    $pagesubtitle = "List of Contacts";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    $lowerrole = strtolower($userrole);
    
    switch ($lowerrole) {
        case "authorized user":
            header("location:/dashboard/customers/authorizedUserView");
            break;
        case "partner":
            header("location:/dashboard/partnerships");
            break;
        case "customer":
            header("location:/dashboard/customers");
            break;
    }

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';
?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>