<?php

    function contactsHomeListingURLs($accountNumber, $row, $actionUrls) {

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

    function contactsHomeListingHeaders($headers, $columnWidths) {

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

    function contactsHomeListingRow($con, $row, $columns, $columnWidths, $actionUrls = []) {

        try {
             
            $rowHtml = '<tr>';

            foreach ($columns as $index => $column) {

                $width = $columnWidths[$index] ?? 'auto';

                $rowHtml .= "<td style='width:{$width};'>{$row[$column]}</td>";
                
            }

            if ($actionUrls) {

                $rowHtml .= contactsHomeListingURLs(substr($row['id'], -4), $row, $actionUrls);
                
            }

            $rowHtml .= '</tr>';

            return $rowHtml;

        } catch (\Throwable $exception) {

            \Sentry\captureException($exception);

        }
    }

    function contactsHomeListingTable($con, $sql, $headers, $columns, $columnWidths, $actionUrls = [], $statusColumn = null) {

        try {

            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result) > 0) {

                echo '<table style="width:100%; margin-top: -3%;">';

                echo contactsHomeListingHeaders($headers, $columnWidths);

                while ($row = mysqli_fetch_assoc($result)) {

                    echo contactsHomeListingRow($con, $row, $columns, $columnWidths, $actionUrls, $statusColumn);

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
                            <td style="width:20%;">There are no Contacts</td>
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