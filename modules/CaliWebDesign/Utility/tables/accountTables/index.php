<?php

    function accountsManageListingStatus($status) {

        $statusClasses = [
            "active" => "green",
            "suspended" => "red",
            "cancelled" => "red",
            "terminated" => "red-dark",
            "inactive" => "passive"
        ];

        $statusClass = $statusClasses[strtolower($status)] ?? "unknown";

        return "<span class='account-status-badge {$statusClass}' style='margin-left:0;'>{$status}</span>";

    }

    function accountsManageListingTable($con, $sql, $headers, $columns, $columnWidths, $actionUrls = []) {

        try {

            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result) > 0) {

                echo '<table style="width:100%;">';
                echo accountsManageListingHeaders($headers, $columnWidths);

                while ($row = mysqli_fetch_assoc($result)) {

                    echo accountsManageListingRow($row, $columns, $columnWidths, $actionUrls);

                }

                echo '</table>';

            } else {

                echo '<p class="no-padding font-14px" style="margin-top:-2% !important; margin-bottom:25px;">No records found.</p>';

            }

            mysqli_free_result($result);

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
            
        }   

    }

    function accountsManageListingHeaders($headers, $columnWidths) {

        try {

            $headerHtml = '<tr>';

            foreach ($headers as $index => $header) {

                $width = isset($columnWidths[$index]) ? $columnWidths[$index] : 'auto';
                $headerHtml .= "<th style='width:{$width};'>{$header}</th>";

            }

            $headerHtml .= '</tr>';
            return $headerHtml;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
            
        }

    }

    function accountsManageListingRow($row, $columns, $columnWidths, $actionUrls) {

        try {

            $rowHtml = '<tr>';

            foreach ($columns as $index => $column) {

                $width = isset($columnWidths[$index]) ? $columnWidths[$index] : 'auto';

                if ($column == 'serviceStatus') {

                    $rowHtml .= "<td style='width:{$width};'>" . accountsManageListingStatus($row[$column]) . "</td>";

                } else if ($column == 'accountStatus') {

                    $rowHtml .= "<td style='width:{$width};'>" . accountsHomeListingStatus($row[$column]) . "</td>";

                } else {

                    $rowHtml .= "<td style='width:{$width};'>{$row[$column]}</td>";

                }
                
            }

            if ($actionUrls) {

                $rowHtml .= accountsManageListingURLs($row, $actionUrls);

            }

            $rowHtml .= '</tr>';
            return $rowHtml;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
            
        }

    }

    function accountsManageListingURLs($row, $actionUrls) {

        try {

            $actionHtml = '<td>';

            foreach ($actionUrls as $action => $urlTemplate) {

                $actionUrl = $urlTemplate;

                foreach ($row as $key => $value) {

                    $actionUrl = str_replace('{' . $key . '}', $value, $actionUrl);

                }

                $actionHtml .= "<a href='{$actionUrl}' class='caliweb-button secondary no-margin margin-10px-right' style='padding:6px 24px !important; margin-right:10px;'>{$action}</a>";
                
            }

            $actionHtml .= '</td>';
            return $actionHtml;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
            
        }

    }

    function accountsHomeListingStatus($status) {

        $statusClasses = [
            "active" => "green",
            "suspended" => "red",
            "under review" => "yellow",
            "terminated" => "red-dark",
            "closed" => "passive"
        ];

        $statusClass = $statusClasses[strtolower($status)] ?? "unknown";

        return "<span class='account-status-badge {$statusClass}' style='margin-left:0;'>{$status}</span>";

    }

    function accountsHomeListingURLs($accountNumber, $row, $actionUrls) {

        try {

            $actionHtml = '<td>';

            foreach ($actionUrls as $action => $urlTemplate) {

                $actionUrl = $urlTemplate;

                foreach ($row as $key => $value) {

                    $actionUrl = str_replace('{' . $key . '}', $value, $actionUrl);

                }

                if (strpos($actionUrl, "openModal(") !== false) {
                    
                    $actionHtml .= '<a onclick="'.$actionUrl.'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">'.$action.'</a>';

                } else {

                    $actionHtml .= "<a href='{$actionUrl}' class='caliweb-button secondary no-margin margin-10px-right' style='padding:6px 24px; margin-right:10px;'>{$action}</a>";

                }

            }

            $actionHtml .= '</td>';

            return $actionHtml;

        } catch (\Throwable $exception) {

            \Sentry\captureException($exception);

        }
    }

    function accountsHomeListingHeaders($headers, $columnWidths) {

        try {

            $headerHtml = '<tr>';

            foreach ($headers as $index => $header) {

                $width = isset($columnWidths[$index]) ? $columnWidths[$index] : 'auto';

                $headerHtml .= "<th style='width:{$width};'>{$header}</th>";
            }

            $headerHtml .= '</tr>';

            return $headerHtml;

        } catch (\Throwable $exception) {

            \Sentry\captureException($exception);

        }
    }

    function accountsHomeListingRow($con, $row, $columns, $columnWidths, $actionUrls = []) {

        try {
             
            $rowHtml = '<tr>';

            $email = $row['email'];

            $businessQuery = "
                SELECT 
                    (SELECT businessName FROM caliweb_businesses WHERE email = '{$email}') AS businessName, 
                    (SELECT employeeAccessLevel FROM caliweb_users WHERE email = '{$email}') AS employeeAccessLevel
            ";

            $businessInfo = mysqli_fetch_assoc(mysqli_query($con, $businessQuery));

            $businessname = $businessInfo['businessName'] ?? $row['legalName'];

            $usertype = $businessInfo['employeeAccessLevel'];

            $formattingMap = [
                'accountNumber' => function($value) use ($businessname) {
                    return "{$businessname} (•••• " . substr($value, -4) . ")";
                },
                'userrole' => function($value) use ($usertype) {
                    return "{$value} - {$usertype}";
                },
                'accountDBPrefix' => function($value) {
                    return strtoupper($value);
                },
                'accountStatus' => function($value) {
                    return accountsHomeListingStatus($value);
                }
            ];

            foreach ($columns as $index => $column) {

                $width = $columnWidths[$index] ?? 'auto';

                if (isset($formattingMap[$column])) {


                    $formattedValue = $formattingMap[$column]($row[$column]);

                    $rowHtml .= "<td style='width:{$width};'>{$formattedValue}</td>";

                } else {

                    $rowHtml .= "<td style='width:{$width};'>{$row[$column]}</td>";

                }
                
            }

            if ($actionUrls) {

                $rowHtml .= accountsHomeListingURLs(substr($row['accountNumber'], -4), $row, $actionUrls);
                
            }

            $rowHtml .= '</tr>';

            return $rowHtml;

        } catch (\Throwable $exception) {

            \Sentry\captureException($exception);

        }
    }

    function accountsHomeListingTable($con, $sql, $headers, $columns, $columnWidths, $actionUrls = [], $statusColumn = null) {

        try {

            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result) > 0) {

                echo '<table style="width:100%; margin-top: -3%;">';

                echo accountsHomeListingHeaders($headers, $columnWidths);

                while ($row = mysqli_fetch_assoc($result)) {

                    echo accountsHomeListingRow($con, $row, $columns, $columnWidths, $actionUrls, $statusColumn);

                }

                echo '</table>';

            } else {

                echo '
                
                    <table style="width:100%;">
                        <tr>
                            <th style="width:30%;">Company/Account Number</th>
                            <th style="width:20%;">Owner</th>
                            <th style="width:20%;">Phone</th>
                            <th style="width:20%;">Type</th>
                            <th style="width:10%;">Status</th>
                            <th>Actions</th>
                        </tr>
                        <tr>
                            <td style="width:20%;">There are no Accounts</td>
                            <td style="width:20%;"></td>
                            <td style="width:20%;"></td>
                            <td style="width:20%;"></td>
                            <td style="width:20%;"></td>
                            <td style="width:10%;"></td>
                        </tr>
                    </table>
                
                ';

            }

            mysqli_free_result($result);

        } catch (\Throwable $exception) {

            \Sentry\captureException($exception);

        }

    }

?>