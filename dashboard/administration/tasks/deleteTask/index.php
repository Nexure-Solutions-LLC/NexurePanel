<?php

    $pagetitle = "Tasks";
    $pagesubtitle = "Delete";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    $taskid = $_GET['task_id'];

    if ($taskid != "" || $taskid != NULL) {

        $taskDeleteRequest = "DELETE FROM `nexure_tasks` WHERE `id`= '$taskid'";
        $taskDeleteResult = mysqli_query($con, $taskDeleteRequest);

        if ($taskDeleteResult) {

            header ("location: /dashboard/administration/tasks");

        } else {

            header ("location: /error/genericSystemError");
    
        }

    } else {

        header ("location: /error/genericSystemError");

    }

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>