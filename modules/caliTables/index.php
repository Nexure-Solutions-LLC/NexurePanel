<?php
    if ($_SESSION['graphCallType'] == "Dashboard Tasks Table") {
        $sql = "SELECT * FROM caliweb_tasks";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Output table header
            echo '<table style="width:100%; margin-top:1%;">
                    <tr>
                        <th style="width:20%; ">Task Name</th>
                        <th style="width:20%; ">Task Start Date</th>
                        <th style="width:20%; ">Task Due Date</th>
                        <th style="width:20%; ">Status</th>
                    </tr>';

            // Output table rows
            while ($row = mysqli_fetch_assoc($result)) {

                // This formats the date to MM/DD/YYYY HH:MM AM/PM
                $tasksStatusColorAssignment = $row['status'];
                $taskStartDateUnformattedData = $row['taskStartDate'];
                $taskDueDateUnformattedData = $row['taskDueDate'];
                $taskStartDateUnformatted = new DateTime($taskStartDateUnformattedData);
                $taskDueDateUnformatted = new DateTime($taskDueDateUnformattedData);
                $taskStartDateFormatted = $taskStartDateUnformatted->format('m/d/Y g:i A');
                $taskDueDateFormatted = $taskDueDateUnformatted->format('m/d/Y g:i A');

                echo '<tr>';
                    echo '<td style="width:20%; ">' . $row['taskName'] . '</td>';
                    echo '<td style="width:20%; ">' . $taskStartDateFormatted . '</td>';
                    echo '<td style="width:20%; ">' . $taskDueDateFormatted . '</td>';
                    if ($tasksStatusColorAssignment == "Completed" || $tasksStatusColorAssignment == "Completed") {
                        echo '<td style="width:20%; "><span class="account-status-badge green" style="margin-left:0;">' . $row['status'] . '</span></td>';
                    } else if ($tasksStatusColorAssignment == "Overdue" || $tasksStatusColorAssignment == "overdue") {
                       echo '<td style="width:20%; "><span class="account-status-badge red" style="margin-left:0;">' . $row['status'] . '</span></td>';
                    } else if ($tasksStatusColorAssignment == "Pending" || $tasksStatusColorAssignment == "pending") {
                       echo '<td style="width:20%; "><span class="account-status-badge yellow" style="margin-left:0;">' . $row['status'] . '</span></td>';
                    } else if ($tasksStatusColorAssignment == "Stuck" || $tasksStatusColorAssignment == "stuck") {
                       echo '<td style="width:20%; "><span class="account-status-badge red-dark" style="margin-left:0;">' . $row['status'] . '</span></td>';
                    }
                echo '</tr>';

            }

            echo '</table>';
        }
    } else if ($_SESSION['graphCallType'] == "Dashboard Cases Table") {
        $sql = "SELECT * FROM caliweb_cases";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Output table header
            echo '<table style="width:100%; margin-top:1%;">
                    <tr>
                        <th style="width:20%; ">Customer Name</th>
                        <th style="width:20%; ">Case Title</th>
                        <th style="width:20%; ">Case Created</th>
                        <th style="width:20%; ">Case Closed</th>
                        <th style="width:20%; ">Status</th>
                    </tr>';

            // Output table rows
            while ($row = mysqli_fetch_assoc($result)) {

                // This formats the date to MM/DD/YYYY HH:MM AM/PM
                $caseStatusColorAssignment = $row['caseStatus'];
                $caseCreateDateUnformattedData = $row['caseCreateDate'];
                $caseCloseDateUnformattedData = $row['caseCloseDate'];
                $caseCreateDateUnformatted = new DateTime($caseCreateDateUnformattedData);
                $caseCloseDateUnformatted = new DateTime($caseCloseDateUnformattedData);
                $caseCreateDateFormatted = $caseCreateDateUnformatted->format('m/d/Y g:i A');
                $caseCloseDateFormatted = $caseCloseDateUnformatted->format('m/d/Y g:i A');

                echo '<tr>';
                    echo '<td style="width:20%; ">' . $row['customerName'] . '</td>';
                    echo '<td style="width:20%; ">' . $caseCreateDateFormatted . '</td>';
                    echo '<td style="width:20%; ">' . $caseCloseDateFormatted . '</td>';
                    if ($caseStatusColorAssignment == "Open" || $caseStatusColorAssignment == "open") {
                        echo '<td style="width:20%; "><span class="account-status-badge green" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                    } else if ($caseStatusColorAssignment == "Closed" || $caseStatusColorAssignment == "closed") {
                       echo '<td style="width:20%; "><span class="account-status-badge passive" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                    } else if ($caseStatusColorAssignment == "Pending" || $caseStatusColorAssignment == "pending") {
                       echo '<td style="width:20%; "><span class="account-status-badge yellow" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                    } else if ($caseStatusColorAssignment == "On Hold" || $caseStatusColorAssignment == "on hold") {
                       echo '<td style="width:20%; "><span class="account-status-badge red" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                    }
                echo '</tr>';

            }

            echo '</table>';
        }
    } else if ($_SESSION['graphCallType'] == "Dashboard Tasks Table Employee Only") {
        $sql = "SELECT * FROM caliweb_tasks WHERE assignedUser = '$fullname'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Output table header
            echo '<table style="width:100%; margin-top:1%;">
                    <tr>
                        <th style="width:20%; ">Task Name</th>
                        <th style="width:20%; ">Task Start Date</th>
                        <th style="width:20%; ">Task Due Date</th>
                        <th style="width:20%; ">Status</th>
                    </tr>';

            // Output table rows
            while ($row = mysqli_fetch_assoc($result)) {

                // This formats the date to MM/DD/YYYY HH:MM AM/PM
                $tasksStatusColorAssignment = $row['status'];
                $taskStartDateUnformattedData = $row['taskStartDate'];
                $taskDueDateUnformattedData = $row['taskDueDate'];
                $taskStartDateUnformatted = new DateTime($taskStartDateUnformattedData);
                $taskDueDateUnformatted = new DateTime($taskDueDateUnformattedData);
                $taskStartDateFormatted = $taskStartDateUnformatted->format('m/d/Y g:i A');
                $taskDueDateFormatted = $taskDueDateUnformatted->format('m/d/Y g:i A');

                echo '<tr>';
                    echo '<td style="width:20%; ">' . $row['taskName'] . '</td>';
                    echo '<td style="width:20%; ">' . $taskStartDateFormatted . '</td>';
                    echo '<td style="width:20%; ">' . $taskDueDateFormatted . '</td>';
                    if ($tasksStatusColorAssignment == "Completed" || $tasksStatusColorAssignment == "Completed") {
                        echo '<td style="width:20%; "><span class="account-status-badge green" style="margin-left:0;">' . $row['status'] . '</span></td>';
                    } else if ($tasksStatusColorAssignment == "Overdue" || $tasksStatusColorAssignment == "overdue") {
                       echo '<td style="width:20%; "><span class="account-status-badge red" style="margin-left:0;">' . $row['status'] . '</span></td>';
                    } else if ($tasksStatusColorAssignment == "Pending" || $tasksStatusColorAssignment == "pending") {
                       echo '<td style="width:20%; "><span class="account-status-badge yellow" style="margin-left:0;">' . $row['status'] . '</span></td>';
                    } else if ($tasksStatusColorAssignment == "Stuck" || $tasksStatusColorAssignment == "stuck") {
                       echo '<td style="width:20%; "><span class="account-status-badge red-dark" style="margin-left:0;">' . $row['status'] . '</span></td>';
                    }
                echo '</tr>';

            }

            echo '</table>';
        }
    } else if ($_SESSION['graphCallType'] == "Dashboard Cases Table") {
        $sql = "SELECT * FROM caliweb_cases WHERE assignedAgent = '$fullname'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Output table header
            echo '<table style="width:100%; margin-top:1%;">
                    <tr>
                        <th style="width:20%; ">Customer Name</th>
                        <th style="width:20%; ">Case Title</th>
                        <th style="width:20%; ">Case Created</th>
                        <th style="width:20%; ">Case Closed</th>
                        <th style="width:20%; ">Status</th>
                    </tr>';

            // Output table rows
            while ($row = mysqli_fetch_assoc($result)) {

                // This formats the date to MM/DD/YYYY HH:MM AM/PM
                $caseStatusColorAssignment = $row['caseStatus'];
                $caseCreateDateUnformattedData = $row['caseCreateDate'];
                $caseCloseDateUnformattedData = $row['caseCloseDate'];
                $caseCreateDateUnformatted = new DateTime($caseCreateDateUnformattedData);
                $caseCloseDateUnformatted = new DateTime($caseCloseDateUnformattedData);
                $caseCreateDateFormatted = $caseCreateDateUnformatted->format('m/d/Y g:i A');
                $caseCloseDateFormatted = $caseCloseDateUnformatted->format('m/d/Y g:i A');

                echo '<tr>';
                    echo '<td style="width:20%; ">' . $row['customerName'] . '</td>';
                    echo '<td style="width:20%; ">' . $row['caseTitle'] . '</td>';
                    echo '<td style="width:20%; ">' . $caseCreateDateFormatted . '</td>';
                    echo '<td style="width:20%; ">' . $caseCloseDateFormatted . '</td>';
                    if ($caseStatusColorAssignment == "Open" || $caseStatusColorAssignment == "open") {
                        echo '<td style="width:20%; "><span class="account-status-badge green" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                    } else if ($caseStatusColorAssignment == "Closed" || $caseStatusColorAssignment == "closed") {
                       echo '<td style="width:20%; "><span class="account-status-badge passive" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                    } else if ($caseStatusColorAssignment == "Pending" || $caseStatusColorAssignment == "pending") {
                       echo '<td style="width:20%; "><span class="account-status-badge yellow" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                    } else if ($caseStatusColorAssignment == "On Hold" || $caseStatusColorAssignment == "on hold") {
                       echo '<td style="width:20%; "><span class="account-status-badge red" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                    }
                echo '</tr>';

            }

            echo '</table>';
        }
    }
?>