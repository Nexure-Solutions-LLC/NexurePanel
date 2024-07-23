<?php

    $pagetitle = "Cases";
    $pagesubtitle = "List";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div class="display-flex align-center" style="justify-content: space-between;">
                            <div class="display-flex align-center">
                                <div class="no-padding margin-10px-right icon-size-formatted">
                                    <img src="/assets/img/systemIcons/cases.png" alt="Cases Page Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">Cases</p>
                                    <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">List Cases</h4>
                                </div>
                            </div>
                            <div>
                                <a href="/dashboard/administration/cases/createCase/" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                        <?php
                            if ($currentAccount->accessLevel->name == "Executive" || $currentAccount->accessLevel->name == "Manager") {

                                $sql = "SELECT * FROM caliweb_cases";
                                $result = mysqli_query($con, $sql);

                                if (mysqli_num_rows($result) > 0) {

                                    // Output table header
                                    echo '<table style="width:100%; margin-top:-3%;">
                                            <tr>
                                                <th style="width:20%;">Customer Name</th>
                                                <th style="width:20%;">Case Title</th>
                                                <th style="width:20%;">Case Create Date</th>
                                                <th style="width:20%;">Case Close Date</th>
                                                <th style="width:20%;">Status</th>
                                                <th style="width:20%;">Actions</th>
                                            </tr>';

                                    // Output table rows
                                    while ($row = mysqli_fetch_assoc($result)) {

                                        // This formats the date to MM/DD/YYYY HH:MM AM/PM
                                        $caseCreateDateUnformatted = new DateTime($row['caseCreateDate']);
                                        $caseCreateDateFormatted = $caseCreateDateUnformatted->format('m/d/Y g:i A');
                                        $caseCloseDateUnformatted = new DateTime($row['caseCloseDate']);
                                        $caseCloseDateFormatted = $caseCloseDateUnformatted->format('m/d/Y g:i A');

                                        $caseStatusColorAssignment = $row['caseStatus'];

                                        echo '<tr>';
                                            echo '<td style="width:20%;">' . $row['customerName'] . '</td>';
                                            echo '<td style="width:20%;">' . $row['caseTitle'] . '</td>';
                                            echo '<td style="width:20%;">' . $caseCreateDateFormatted . '</td>';
                                            echo '<td style="width:20%;">' . $caseCloseDateFormatted . '</td>';

                                            if ($caseStatusColorAssignment == "Open" || $caseStatusColorAssignment == "open") {

                                                echo '<td style="width:20%; "><span class="account-status-badge green" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';

                                            } else if ($caseStatusColorAssignment == "Closed" || $caseStatusColorAssignment == "closed") {

                                               echo '<td style="width:20%; "><span class="account-status-badge passive" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';

                                            } else if ($caseStatusColorAssignment == "Pending" || $caseStatusColorAssignment == "pending") {

                                               echo '<td style="width:20%; "><span class="account-status-badge yellow" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';

                                            } else if ($caseStatusColorAssignment == "On Hold" || $caseStatusColorAssignment == "on hold") {

                                               echo '<td style="width:20%; "><span class="account-status-badge red" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';

                                            }
                                            
                                            echo '<td class=""><a href="/dashboard/administration/cases/viewCases/?case_id=' . $row['id'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">View</a><a href="/dashboard/administration/cases/deleteCase/?case_id=' . $row['id'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">Delete</a><a href="/dashboard/administration/cases/editCase/?case_id=' . $row['id'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a></td>';
                                        
                                        echo '</tr>';

                                    }

                                    echo '</table>';

                                } else {

                                    echo '
                                        <table style="width:100%; margin-top:-3%;">
                                            <tr>
                                                <th style="width:20%;">Customer Name</th>
                                                <th style="width:20%;">Case Title</th>
                                                <th style="width:20%;">Case Create Date</th>
                                                <th style="width:20%;">Case Close Date</th>
                                                <th style="width:20%;">Status</th>
                                                <th style="width:20%;">Actions</th>
                                            </tr>
                                            <tr>
                                                <td style="width:20%; ">There are no Cases</td>
                                                <td style="width:20%; "></td>
                                                <td style="width:20%; "></td>
                                                <td style="width:20%; "></td>
                                                <td style="width:20%; "></td>
                                                <td style="width:20%; "></td>
                                            </tr>
                                        </table>
                                    ';

                                }

                            } else {

                                $sql = "SELECT * FROM caliweb_cases WHERE assignedUser = '$fullname'";
                                $result = mysqli_query($con, $sql);

                                if (mysqli_num_rows($result) > 0) {

                                    // Output table header
                                    echo '<table style="width:100%; margin-top:-3%;">
                                            <tr>
                                                <th style="width:20%;">Customer Name</th>
                                                <th style="width:20%;">Case Title</th>
                                                <th style="width:20%;">Case Create Date</th>
                                                <th style="width:20%;">Case Close Date</th>
                                                <th style="width:20%;">Status</th>
                                                <th style="width:20%;">Actions</th>
                                            </tr>';

                                    // Output table rows
                                    while ($row = mysqli_fetch_assoc($result)) {

                                        // This formats the date to MM/DD/YYYY HH:MM AM/PM
                                        $caseCreateDateUnformatted = new DateTime($row['caseCreateDate']);
                                        $caseCreateDateFormatted = $caseCreateDateUnformatted->format('m/d/Y g:i A');
                                        $caseCloseDateUnformatted = new DateTime($row['caseCloseDate']);
                                        $caseCloseDateFormatted = $caseCloseDateUnformatted->format('m/d/Y g:i A');

                                        $caseStatusColorAssignment = $row['caseStatus'];

                                        echo '<tr>';
                                            echo '<td style="width:20%;">' . $row['customerName'] . '</td>';
                                            echo '<td style="width:20%;">' . $row['caseTitle'] . '</td>';
                                            echo '<td style="width:20%;">' . $caseCreateDateFormatted . '</td>';
                                            echo '<td style="width:20%;">' . $caseCloseDateFormatted . '</td>';
                                            if ($caseStatusColorAssignment == "Open" || $caseStatusColorAssignment == "open") {

                                                echo '<td style="width:20%; "><span class="account-status-badge green" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';

                                            } else if ($caseStatusColorAssignment == "Closed" || $caseStatusColorAssignment == "closed") {

                                               echo '<td style="width:20%; "><span class="account-status-badge passive" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';

                                            } else if ($caseStatusColorAssignment == "Pending" || $caseStatusColorAssignment == "pending") {

                                               echo '<td style="width:20%; "><span class="account-status-badge yellow" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                                               
                                            } else if ($caseStatusColorAssignment == "On Hold" || $caseStatusColorAssignment == "on hold") {

                                               echo '<td style="width:20%; "><span class="account-status-badge red" style="margin-left:0;">' . $row['caseStatus'] . '</span></td>';
                                               
                                            }
                                            echo '<td class="">
                                                    <a href="/dashboard/administration/cases/viewCase/?case_id=' . $row['id'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">View</a>
                                                    <a href="/dashboard/administration/cases/deleteCase/?case_id=' . $row['id'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">Delete</a>
                                                    <a href="/dashboard/administration/cases/editCase/?case_id=' . $row['id'] . '" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a>
                                                </td>';
                                        echo '</tr>';

                                    }

                                    echo '</table>';

                                } else {

                                    echo '
                                        <table style="width:100%; margin-top:-3%;">
                                            <tr>
                                                <th style="width:20%;">Customer Name</th>
                                                <th style="width:20%;">Case Title</th>
                                                <th style="width:20%;">Case Create Date</th>
                                                <th style="width:20%;">Case Close Date</th>
                                                <th style="width:20%;">Status</th>
                                                <th style="width:20%;">Actions</th>
                                            </tr>
                                            <tr>
                                                <td style="width:20%; ">There are no Cases</td>
                                                <td style="width:20%; "></td>
                                                <td style="width:20%; "></td>
                                                <td style="width:20%; "></td>
                                                <td style="width:20%; "></td>
                                                <td style="width:20%; "></td>
                                            </tr>
                                        </table>
                                    ';
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

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>