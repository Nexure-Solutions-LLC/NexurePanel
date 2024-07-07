<?php
    $pagetitle = "Tasks";
    $pagesubtitle = "List";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    if ($userrole == "Customer" || $userrole == "customer") {
        header("location:/dashboard/customers");
    } else if ($userrole == "Authorized User" || $userrole == "authorized user") {
        header("location:/dashboard/customers/authorizedUserView");
    } else if ($userrole == "Partner" || $userrole == "partner") {
        header("location:/dashboard/partnerships");
    }

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';
?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                         <div class="display-flex align-center">
                            <div class="no-padding margin-10px-right icon-size-formatted">
                                <img src="/assets/img/systemIcons/tasksicon.png" alt="Tasks Page Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                            </div>
                            <div>
                                <p class="no-padding font-14px">Tasks</p>
                                <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">List Tasks</h4>
                            </div>
                         </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            <?php
                                if ($employeeAccessLevel == "Executive") {
                                    $sql = "SELECT * FROM caliweb_tasks";
                                    $result = mysqli_query($con, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        // Output table header
                                        echo '<table style="width:100%; margin-top:-3%;">
                                                <tr>
                                                    <th style="width:20%;">Task Name</th>
                                                    <th style="width:20%;">Task Start Date</th>
                                                    <th style="width:20%;">Task Due Date</th>
                                                    <th style="width:20%;">Assigned To</th>
                                                    <th style="width:20%;">Status</th>
                                                    <th style="width:20%;">Actions</th>
                                                </tr>';

                                        // Output table rows
                                        while ($row = mysqli_fetch_assoc($result)) {

                                            $tasksStatusColorAssignment = $row['status'];

                                            $startDate = $row['taskStartDate'];
                                            $startDateFormatted = new DateTime($startDate);
                                            $startDateFormattedFinal = $startDateFormatted->format('F j, Y g:i A');

                                            $dueDate = $row['taskDueDate'];
                                            $dueDateFormatted = new DateTime($dueDate);
                                            $dueDateFormattedFinal = $dueDateFormatted->format('F j, Y g:i A');

                                            echo '<tr>';
                                                echo '<td style="width:20%;">' . $row['taskName'] . '</td>';
                                                echo '<td style="width:20%;">' . $startDateFormattedFinal . '</td>';
                                                echo '<td style="width:20%;">' . $dueDateFormattedFinal . '</td>';
                                                echo '<td style="width:20%;">' . $row['assignedUser'] . '</td>';

                                                if ($tasksStatusColorAssignment == "Completed" || $tasksStatusColorAssignment == "Completed") {
                                                    echo '<td style="width:20%;"><span class="account-status-badge green" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                } else if ($tasksStatusColorAssignment == "Overdue" || $tasksStatusColorAssignment == "overdue") {
                                                echo '<td style="width:20%;"><span class="account-status-badge red" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                } else if ($tasksStatusColorAssignment == "Pending" || $tasksStatusColorAssignment == "pending") {
                                                echo '<td style="width:20%;"><span class="account-status-badge yellow" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                } else if ($tasksStatusColorAssignment == "Stuck" || $tasksStatusColorAssignment == "stuck") {
                                                echo '<td style="width:20%;"><span class="account-status-badge red-dark" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                }

                                                echo '<td class="">
                                                        <a href="/dashboard/administration/tasks/viewTask/?task_id='.$row['id'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">View</a><a href="/dashboard/administration/tasks/deleteTask/task_id='.$row['id'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">Delete</a><a href="/dashboard/administration/tasks/editTask/task_id='.$row['id'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a>
                                                    </td>
                                                ';
                                            echo '</tr>';

                                        }

                                        echo '</table>';
                                    }
                                } else if ($employeeAccessLevel == "Manager") {
                                    $sql = "SELECT * FROM caliweb_tasks";
                                    $result = mysqli_query($con, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        // Output table header
                                        echo '<table style="width:100%; margin-top:-3%;">
                                                <tr>
                                                    <th style="width:20%;">Task Name</th>
                                                    <th style="width:20%;">Task Start Date</th>
                                                    <th style="width:20%;">Task Due Date</th>
                                                    <th style="width:20%;">Assigned To</th>
                                                    <th style="width:20%;">Status</th>
                                                    <th style="width:20%;">Actions</th>
                                                </tr>';

                                        // Output table rows
                                        while ($row = mysqli_fetch_assoc($result)) {

                                            $tasksStatusColorAssignment = $row['status'];

                                            $startDate = $row['taskStartDate'];
                                            $startDateFormatted = new DateTime($startDate);
                                            $startDateFormattedFinal = $startDateFormatted->format('F j, Y g:i A');

                                            $dueDate = $row['taskDueDate'];
                                            $dueDateFormatted = new DateTime($dueDate);
                                            $dueDateFormattedFinal = $dueDateFormatted->format('F j, Y g:i A');

                                            echo '<tr>';
                                                echo '<td style="width:20%;">' . $row['taskName'] . '</td>';
                                                echo '<td style="width:20%;">' . $startDateFormattedFinal . '</td>';
                                                echo '<td style="width:20%;">' . $dueDateFormattedFinal . '</td>';
                                                echo '<td style="width:20%;">' . $row['assignedUser'] . '</td>';

                                                if ($tasksStatusColorAssignment == "Completed" || $tasksStatusColorAssignment == "Completed") {
                                                    echo '<td style="width:20%;"><span class="account-status-badge green" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                } else if ($tasksStatusColorAssignment == "Overdue" || $tasksStatusColorAssignment == "overdue") {
                                                echo '<td style="width:20%;"><span class="account-status-badge red" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                } else if ($tasksStatusColorAssignment == "Pending" || $tasksStatusColorAssignment == "pending") {
                                                echo '<td style="width:20%;"><span class="account-status-badge yellow" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                } else if ($tasksStatusColorAssignment == "Stuck" || $tasksStatusColorAssignment == "stuck") {
                                                echo '<td style="width:20%;"><span class="account-status-badge red-dark" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                }

                                                echo '<td class="">
                                                        <a href="/dashboard/administration/tasks/viewTask/?task_id='.$row['id'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">View</a><a href="/dashboard/administration/tasks/deleteTask/task_id='.$row['id'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">Delete</a><a href="/dashboard/administration/tasks/editTask/task_id='.$row['id'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a>
                                                    </td>
                                                ';
                                            echo '</tr>';

                                        }

                                        echo '</table>';
                                    }
                                } else {
                                    $sql = "SELECT * FROM caliweb_tasks WHERE assignedUser = '$fullname'";
                                    $result = mysqli_query($con, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        // Output table header
                                        echo '<table style="width:100%; margin-top:-3%;">
                                                <tr>
                                                    <th style="width:20%;">Task Name</th>
                                                    <th style="width:20%;">Task Start Date</th>
                                                    <th style="width:20%;">Task Due Date</th>
                                                    <th style="width:20%;">Assigned To</th>
                                                    <th style="width:20%;">Status</th>
                                                    <th style="width:20%;">Actions</th>
                                                </tr>';

                                        // Output table rows
                                        while ($row = mysqli_fetch_assoc($result)) {

                                            $tasksStatusColorAssignment = $row['status'];

                                            $startDate = $row['taskStartDate'];
                                            $startDateFormatted = new DateTime($startDate);
                                            $startDateFormattedFinal = $startDateFormatted->format('F j, Y g:i A');

                                            $dueDate = $row['taskDueDate'];
                                            $dueDateFormatted = new DateTime($dueDate);
                                            $dueDateFormattedFinal = $dueDateFormatted->format('F j, Y g:i A');

                                            echo '<tr>';
                                                echo '<td style="width:20%;">' . $row['taskName'] . '</td>';
                                                echo '<td style="width:20%;">' . $startDateFormattedFinal . '</td>';
                                                echo '<td style="width:20%;">' . $dueDateFormattedFinal . '</td>';
                                                echo '<td style="width:20%;">' . $row['assignedUser'] . '</td>';

                                                if ($tasksStatusColorAssignment == "Completed" || $tasksStatusColorAssignment == "Completed") {
                                                    echo '<td style="width:20%;"><span class="account-status-badge green" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                } else if ($tasksStatusColorAssignment == "Overdue" || $tasksStatusColorAssignment == "overdue") {
                                                echo '<td style="width:20%;"><span class="account-status-badge red" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                } else if ($tasksStatusColorAssignment == "Pending" || $tasksStatusColorAssignment == "pending") {
                                                echo '<td style="width:20%;"><span class="account-status-badge yellow" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                } else if ($tasksStatusColorAssignment == "Stuck" || $tasksStatusColorAssignment == "stuck") {
                                                echo '<td style="width:20%;"><span class="account-status-badge red-dark" style="margin-left:0;">' . $row['status'] . '</span></td>';
                                                }
                                                
                                                echo '<td class="">
                                                        <a href="/dashboard/administration/tasks/viewTask/?task_id='.$row['id'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">View</a><a href="/dashboard/administration/tasks/deleteTask/task_id='.$row['id'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">Delete</a><a href="/dashboard/administration/tasks/editTask/task_id='.$row['id'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a>
                                                    </td>
                                                ';
                                            echo '</tr>';

                                        }

                                        echo '</table>';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>