<?php

    $pagetitle = "Tasks";
    $pagesubtitle = "Delete";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    if ($userrole == "Customer" || $userrole == "customer") {

        header("location:/dashboard/customers");

    } else if ($userrole == "Authorized User" || $userrole == "authorized user") {

        header("location:/dashboard/customers/authorizedUserView");

    } else if ($userrole == "Partner" || $userrole == "partner") {

        header("location:/dashboard/partnerships");

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