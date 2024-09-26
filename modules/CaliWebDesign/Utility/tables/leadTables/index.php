<?php

    function leadsHomeListingStatus($status)
    {

        $statusClasses = [
            "completed" => "green",
            "overdue" => "red",
            "pending" => "yellow",
            "stuck" => "red-dark",
            "unassigned" => "passive"
        ];

        $statusClass = $statusClasses[strtolower($status)] ?? "unknown";

        return "<span class='account-status-badge {$statusClass}' style='margin-left:0;'>{$status}</span>";

    }

    function leadsHomeListingURLs($row, $actionUrls)
    {

        try {

            $actionHtml = '<td>';

            foreach ($actionUrls as $action => $urlTemplate) {

                $actionUrl = $urlTemplate;

                foreach ($row as $key => $value) {

                    $actionUrl = str_replace('{' . $key . '}', $value, $actionUrl);
                }

                if (strpos($actionUrl, "openModal(") !== false) {

                    $actionHtml .= '<a onclick="' . $actionUrl . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">' . $action . '</a>';
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

    function leadsHomeListingHeaders($headers, $columnWidths)
    {

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

    function leadsHomeListingRow($con, $row, $columns, $columnWidths, $actionUrls = [])
    {

        try {
            $rowHtml = '<tr>';

            foreach ($columns as $index => $column) {

                $width = isset($columnWidths[$index]) ? $columnWidths[$index] : 'auto';

                $startDate = (new DateTime($row['leadStartDate']))->format('F j, Y g:i A');

                $dueDate = (new DateTime($row['leadDueDate']))->format('F j, Y g:i A');

                if ($column == 'status') {

                    // Render special status

                    $rowHtml .= "<td style='width:{$width};'>" . leadsHomeListingStatus($row[$column]) . "</td>";
                } else {

                    $rowHtml .= "<td style='width:{$width};'>{$row[$column]}</td>";
                }
            }

            if ($actionUrls) {

                $rowHtml .= leadsHomeListingURLs($row, $actionUrls);

            }

            $rowHtml .= '</tr>';

            return $rowHtml;

        } catch (\Throwable $exception) {

            \Sentry\captureException($exception);
        }
    }

    function leadsHomeListingTable($con, $sql, $headers, $columns, $columnWidths, $actionUrls = [], $statusColumn = null)
    {

        try {

            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result) > 0) {

                echo '<table style="width:100%; margin-top: -3%;">';

                echo leadsHomeListingHeaders($headers, $columnWidths);

                while ($row = mysqli_fetch_assoc($result)) {

                    echo leadsHomeListingRow($con, $row, $columns, $columnWidths, $actionUrls, $statusColumn);
                }

                echo '</table>';
            } else {

                echo '
                    
                        <table style="width:100%; margin-top:-3%;">
                            <tr>
                                <th style="width:20%;">Assigned To</th>
                                <th style="width:20%;">Customer Name</th>
                                <th style="width:20%;">Account Number</th>
                                <th style="width:20%;">Status</th>
                                <th style="width:20%;">Actions</th>
                            </tr>
                            <tr>
                                <td style="width:20%;">There are no Leads</td>
                                <td style="width:20%;"></td>
                                <td style="width:20%;"></td>
                                <td style="width:20%;"></td>
                                <td style="width:20%;"></td>
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