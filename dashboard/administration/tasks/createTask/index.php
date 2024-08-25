<?php

    $pagetitle = "Tasks";
    $pagesubtitle = "Create";
    $pagetype = "Administration";

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    // When form submitted, insert values into the database.

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Personal Information Section

        $taskname = stripslashes($_REQUEST['taskname']);
        $taskname = mysqli_real_escape_string($con, $taskname);
        $duedate = stripslashes($_REQUEST['duedate']);
        $duedate = mysqli_real_escape_string($con, $duedate);
        $assigneduser = stripslashes($_REQUEST['assigneduser']);
        $assigneduser = mysqli_real_escape_string($con, $assigneduser);
        $taskstatus = stripslashes($_REQUEST['taskstatus']);
        $taskstatus = mysqli_real_escape_string($con, $taskstatus);
        $taskdescription = stripslashes($_REQUEST['taskdescription']);
        $taskdescription = mysqli_real_escape_string($con, $taskdescription);
        $taskpriority = stripslashes($_REQUEST['taskpriority']);
        $taskpriority = mysqli_real_escape_string($con, $taskpriority);

        // System Feilds

        $taskstartdate = date("Y-m-d H:i:s");

        // Database Calls
        
        $taskInsertRequest = "INSERT INTO `caliweb_tasks`(`taskName`, `taskDueDate`, `taskStartDate`, `status`, `assignedUser`, `taskDescription`, `taskPriority`) VALUES ('$taskname','$duedate','$taskstartdate','$taskstatus','$assigneduser','$taskdescription','$taskpriority')";
        $taskInsertResult = mysqli_query($con, $taskInsertRequest);

        if ($taskInsertResult) {

            header ("location: /dashboard/administration/tasks");
        

        } else {

            header ("location: /error/genericSystemError");

        }

    } else {

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card">
                    <form method="POST" action="">
                        <div class="card-header">
                            <div class="display-flex align-center" style="justify-content: space-between;">
                                <div class="display-flex align-center">
                                    <div class="no-padding margin-10px-right icon-size-formatted">
                                        <img src="/assets/img/systemIcons/tasksicon.png" alt="Tasks Page Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                    </div>
                                    <div>
                                        <p class="no-padding font-14px">Tasks</p>
                                        <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">Create Task</h4>
                                    </div>
                                </div>
                                <div>
                                    <button class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" type="submit" name="submit">Save</button>
                                    <a href="/dashboard/administration/tasks/createTask/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                    <a href="/dashboard/administration/tasks/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="fillable-section-holder" style="margin-top:-3% !important; padding-bottom:3%;">
                                <div class="fillable-header">
                                    <p class="fillable-text">Task Information</p>
                                </div>
                                <div class="fillable-body">
                                    <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0px !important; grid-column-gap:100px !important;">
                                        <div class="form-left-side" style="width:80%;">
                                            <div class="form-control">
                                                <label for="taskname">Task Name</label>
                                                <input type="text" name="taskname" id="taskname" class="form-input" placeholder="John Doe" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="duedate">Due Date</label>
                                                <input type="date" name="duedate" id="duedate" class="form-input" placeholder="me@example.com" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="taskstatus">Task Status</label>
                                                <select type="text" name="taskstatus" id="taskstatus" class="form-input">
                                                    <option>Please choose an option</option>
                                                    <option>Completed</option>
                                                    <option>Overdue</option>
                                                    <option>Pending</option>
                                                    <option>Stuck</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-left-side" style="display:block; width:80%;">
                                            <div class="form-control">
                                                <label for="taskpriority">Task Priority</label>
                                                <select type="text" name="taskpriority" id="taskpriority" class="form-input">
                                                    <option>Please choose an option</option>
                                                    <option>Normal</option>
                                                    <option>Elevated</option>
                                                    <option>Highest</option>
                                                </select>
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="assigneduser">Assigned User</label>
                                                <input type="email" name="assigneduser" id="assigneduser" class="form-input" placeholder="me@example.com" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="taskdescription">Task Description</label>
                                                <textarea style="height:150px" type="text" name="taskdescription" id="taskdescription" class="form-input" required=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

    }

?>