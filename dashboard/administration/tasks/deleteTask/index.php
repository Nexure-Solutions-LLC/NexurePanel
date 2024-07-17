<?php

    $pagetitle = "Tasks";
    $pagesubtitle = "Delete";

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

    $taskid = $_GET['task_id'];

    if ($taskid != "" || $taskid != NULL) {

        $taskDeleteRequest = "DELETE FROM `caliweb_tasks` WHERE `id`= '$taskid'";
        $taskDeleteResult = mysqli_query($con, $taskDeleteRequest);

        if ($taskDeleteResult) {

            header ("location: /dashboard/administration/tasks");

        } else {

            header ("location: /error/genericSystemError");
    
        }

    } else {

        header ("location: /error/genericSystemError");

    }

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>