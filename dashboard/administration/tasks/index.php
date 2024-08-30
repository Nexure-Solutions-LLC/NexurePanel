<?php

    $pagetitle = "Tasks";
    $pagesubtitle = "List";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/tables/taskTables/index.php');
    
    echo "<title>$pagetitle | $pagesubtitle</title>";

?>

<section class="section first-dashboard-area-cards">
    <div class="container width-98">
        <div class="caliweb-one-grid special-caliweb-spacing">
            <div class="caliweb-card dashboard-card">
                <div class="card-header">
                    <div class="display-flex align-center" style="justify-content: space-between;">
                        <div class="display-flex align-center">
                            <div class="no-padding margin-10px-right icon-size-formatted">
                                <img src="/assets/img/systemIcons/tasksicon.png" alt="Tasks Page Icon" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                            </div>
                            <div>
                                <p class="no-padding font-14px">Tasks</p>
                                <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">List Tasks</h4>
                            </div>
                        </div>
                        <div>
                            <a href="/dashboard/administration/tasks/createTask/" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create New</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dashboard-table">
                        <?php

                            $sql = "SELECT * FROM caliweb_tasks";

                            function displayTasks($con, $baseSql, $accessLevel, $currentUser) {

                                if ($accessLevel === "Executive" || $accessLevel === "Manager") {

                                    $sql = $baseSql;

                                } else {

                                    $sql = $baseSql . " WHERE assignedUser = '{$currentUser}'";

                                }

                                tasksHomeListingTable(
                                    $con,
                                    $sql,
                                    ['Task Name', 'Start Date', 'Due Date', 'Assigned To', 'Status', 'Actions'],
                                    ['taskName', 'taskStartDate', 'taskDueDate', 'assignedUser', 'status'],
                                    ['30%', '20%', '20%', '20%', '10%'],
                                    [
                                        'View' => "/dashboard/administration/tasks/viewTask/?task_id={id}",
                                        'Edit' => "/dashboard/administration/tasks/editTask/?task_id={id}",
                                        'Delete' => "openModal({id})"
                                    ]
                                );

                            }

                            displayTasks($con, $sql, $currentAccount->accessLevel->name, $currentAccount->legalName);

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <div id="tasksModal" class="modal">
        <div class="modal-content">
            <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Delete this task?</h6>
            <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">What you are about to do is permanent and can't be undone. Are you sure you would like to delete this task. You will need to remake the task if you would like to restore it.</p>
            <div style="display:flex; align-items:right; justify-content:right;">
                <a id="deleteLink" href="#" class="caliweb-button secondary red" style="margin-right:20px;">Delete Task</a>
                <button class="caliweb-button primary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        var modal = document.getElementById("tasksModal");

        function openModal(taskID) {
            deleteLink.href = "/dashboard/administration/tasks/deleteTask/?task_id=" + encodeURIComponent(taskID);
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }
    </script>

<?php include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php'); ?>