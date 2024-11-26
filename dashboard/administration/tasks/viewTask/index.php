<?php

    $pagetitle = "Tasks";
    $pagesubtitle = "Details";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/tables/taskTables/index.php');
    
    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    $taskid = $_GET['task_id'];

    if (!$taskid) {

        header("location: /dashboard/administration/tasks");
        exit;

    }

    $manageTaskDefintionK = new \NexureSolutions\Utility\TaskView();
    $manageTaskDefintionK->manageTask($con, $taskid);

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <?php include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Task/Headers/index.php'); ?>
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
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>