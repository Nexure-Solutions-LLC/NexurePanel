<?php

    $pagetitle = "Tasks";
    $pagesubtitle = "Edit";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/tables/taskTables/index.php');
    
    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    $taskid = $_GET['task_id'];

    if (!$taskid) {

        header("location: /dashboard/administration/tasks");
        exit;

    }

    $manageTaskDefintionK = new \CaliWebDesign\Utility\TaskView();
    $manageTaskDefintionK->manageTask($con, $taskid);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $editStatus = stripslashes($_REQUEST['editStatus']);
        $editStatus = mysqli_real_escape_string($con, $editStatus);
        $editPriority = stripslashes($_REQUEST['editPriority']);
        $editPriority = mysqli_real_escape_string($con, $editPriority);

        $current_time = time();

        // Check if the last submission time is stored in the session
        
        if (isset($_SESSION['last_submit_time'])) {

            $time_diff = $current_time - $_SESSION['last_submit_time'];

            if ($time_diff < 5) {

                header("Location: /error/rateLimit");
                exit;

            }
        }

        // If the rate limit check passes, update the last submission time

        $_SESSION['last_submit_time'] = $current_time;

        $taskUpdateRequest = "UPDATE `nexure_tasks` SET `status`='$editStatus',`taskPriority`='$editPriority' WHERE id = '$taskid'";
        $taskUpdateResult = mysqli_query($con, $taskUpdateRequest);

        if ($taskUpdateResult) {

            header ("location: /dashboard/administration/tasks/viewTask/?task_id=$taskid");
        

        } else {

            header ("location: /error/genericSystemError");

        }

    }

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <form method="POST">
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <?php include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Task/Headers/index.php'); ?>
                    <div class="caliweb-one-grid special-caliweb-spacing">
                        <div>
                            <div class="caliweb-card dashboard-card">
                                <div class="caliweb-card dashboard-card">
                                    <div class="card-header" style="margin:0; padding:0; margin-bottom:2%;">
                                        <div class="display-flex align-center" style="justify-content:space-between;">
                                            <p class="no-padding">Task Details</p>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php

                                            echo '<p style="font-size:14px; margin:0; padding:0;">'.nl2br($manageTaskDefintionK->taskdescription).'</p>';

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>