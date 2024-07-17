<?php
    $pagetitle = "Tasks";
    $pagesubtitle = "View Task Details";

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

    ob_start();

    $taskid = $_GET['task_id'];

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