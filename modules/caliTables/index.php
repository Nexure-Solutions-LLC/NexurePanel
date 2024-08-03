<?php

    if (!function_exists('renderTableHeader')) {

        function renderTableHeader($headers) {

            try {

                echo '<table style="width:100%; margin-top:1%;">
                        <tr>';

                foreach ($headers as $header) {

                    echo "<th style='width:20%; '>{$header}</th>";

                }

                echo '</tr>';

            } catch (\Throwable $exception) {
            
                \Sentry\captureException($exception);
                
            }

        }

    }

    if (!function_exists('renderTableRow')) {

        function renderTableRow($rowData) {

            try {

                echo '<tr>';

                foreach ($rowData as $data) {

                    echo "<td style='width:20%; '>{$data}</td>";

                }

                echo '</tr>';

            } catch (\Throwable $exception) {
            
                \Sentry\captureException($exception);
                
            }

        }

    }

    if (!function_exists('formatDate')) {
        
        function formatDate($date) {

            try {

                $dateObj = new DateTime($date);
                return $dateObj->format('m/d/Y g:i A');

            } catch (\Throwable $exception) {
            
                \Sentry\captureException($exception);
                
            }

        }
        
    }

    if (!function_exists('getStatusBadge')) {

        function getStatusBadge($status) {

            try {

                $status = $status;

                $statusClasses = [
                    "Completed" => "green",
                    "Overdue" => "red",
                    "Pending" => "yellow",
                    "Stuck" => "red-dark",
                    "Open" => "green",
                    "Closed" => "passive",
                    "On hold" => "red"
                ];

                $class = $statusClasses[$status] ?? 'default';
                return "<span class='account-status-badge {$class}' style='margin-left:0;'>{$status}</span>";

            } catch (\Throwable $exception) {
            
                \Sentry\captureException($exception);
                
            }

        }

    }

    if (!function_exists('renderTable')) {

        function renderTable($result, $headers, $rowCallback, $emptyMessage) {

            try {

                if (mysqli_num_rows($result) > 0) {

                    renderTableHeader($headers);

                    while ($row = mysqli_fetch_assoc($result)) {

                        $rowCallback($row);

                    }

                    echo '</table>';

                } else {

                    echo "<p class='font-14px'>{$emptyMessage}</p>";

                }

            } catch (\Throwable $exception) {
            
                \Sentry\captureException($exception);
                
            }

        }
        
    }

    if ($_SESSION['graphCallType'] == "Dashboard Tasks Table") {

        $sql = "SELECT * FROM caliweb_tasks";
        $result = mysqli_query($con, $sql);
        $headers = ["Task Name", "Task Start Date", "Task Due Date", "Status"];

        renderTable($result, $headers, function($row) {

            $rowData = [
                $row['taskName'],
                formatDate($row['taskStartDate']),
                formatDate($row['taskDueDate']),
                getStatusBadge($row['status'])
            ];

            renderTableRow($rowData);

        }, "No Tasks found.");

    } elseif ($_SESSION['graphCallType'] == "Dashboard Cases Table") {

        $sql = "SELECT * FROM caliweb_cases";
        $result = mysqli_query($con, $sql);
        $headers = ["Customer Name", "Case Created", "Case Closed", "Status"];

        renderTable($result, $headers, function($row) {

            $rowData = [
                $row['customerName'],
                formatDate($row['caseCreateDate']),
                formatDate($row['caseCloseDate']),
                getStatusBadge($row['caseStatus'])
            ];

            renderTableRow($rowData);

        }, "No Cases found.");

    } elseif ($_SESSION['graphCallType'] == "Dashboard Tasks Table Employee Only") {

        $sql = "SELECT * FROM caliweb_tasks WHERE assignedUser = '$fullname'";
        $result = mysqli_query($con, $sql);
        $headers = ["Task Name", "Task Start Date", "Task Due Date", "Status"];

        renderTable($result, $headers, function($row) {

            $rowData = [
                $row['taskName'],
                formatDate($row['taskStartDate']),
                formatDate($row['taskDueDate']),
                getStatusBadge($row['status'])
            ];
            renderTableRow($rowData);

        }, "No Tasks found.");

    } elseif ($_SESSION['graphCallType'] == "Dashboard Cases Table Employee Only") {

        $sql = "SELECT * FROM caliweb_cases WHERE assignedAgent = '$fullname'";
        $result = mysqli_query($con, $sql);
        $headers = ["Customer Name", "Case Title", "Case Created", "Case Closed", "Status"];

        renderTable($result, $headers, function($row) {

            $rowData = [
                $row['customerName'],
                $row['caseTitle'],
                formatDate($row['caseCreateDate']),
                formatDate($row['caseCloseDate']),
                getStatusBadge($row['caseStatus'])
            ];

            renderTableRow($rowData);

        }, "No Cases found.");

    } elseif ($_SESSION['graphCallType'] == "Dashboard Leads Table") {

        $sql = "SELECT * FROM caliweb_leads";
        $result = mysqli_query($con, $sql);
        $headers = ["Assigned To", "Customer Name", "Account Number", "Status"];

        renderTable($result, $headers, function($row) {

            $rowData = [
                $row['assignedAgent'],
                $row['customerName'],
                $row['accountNumber'],
                $row['status']
            ];

            renderTableRow($rowData);

        }, "No Leads found.");

    } elseif ($_SESSION['graphCallType'] == "Dashboard Leads Table Employee Only") {

        $sql = "SELECT * FROM caliweb_leads WHERE assignedAgent = '$fullname'";
        $result = mysqli_query($con, $sql);
        $headers = ["Assigned To", "Customer Name", "Account Number", "Status"];

        renderTable($result, $headers, function($row) {

            $rowData = [
                $row['assignedAgent'],
                $row['customerName'],
                $row['accountNumber'],
                $row['status']
            ];

            renderTableRow($rowData);

        }, "No Leads found.");

    }

?>
