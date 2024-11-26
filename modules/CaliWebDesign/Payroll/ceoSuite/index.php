<?php
    $pagetitle = "Payroll";
    $pagesubtitle = "Employees Listing";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    if ($currentAccount->accessLevel->name == "Executive") {

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';
?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                         <div class="display-flex align-center">
                            <div class="no-padding margin-10px-right icon-size-formatted">
                                <img src="/assets/img/systemIcons/employeeicon.png" alt="Employee Page Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                            </div>
                            <div>
                                <p class="no-padding font-14px">Employees</p>
                                <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">List Employees</h4>
                            </div>
                         </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            <table style="width:100%;">
                                <?php
                                    // Fetch data from MySQL table
                                    $sql = "SELECT * FROM nexure_payroll";
                                    $result = mysqli_query($con, $sql);

                                    // Check if any rows were returned
                                    if (mysqli_num_rows($result) > 0) {
                                        // Output table header
                                        echo '<table style="width:100%;">
                                                <tr>
                                                    <th style="width:20%;">Employee Name/ID Number</th>
                                                    <th style="width:10%;">Time Allocation</th>
                                                    <th style="width:10%;">Pay Type</th>
                                                    <th style="width:10%;">Worked Hours</th>
                                                    <th style="width:10%;">Current Pay</th>
                                                    <th style="width:10%;">Department</th>
                                                    <th style="width:10%;">Status</th>
                                                    <th>Actions</th>
                                                </tr>';

                                        // Output table rows
                                        while ($row = mysqli_fetch_assoc($result)) {

                                            echo '<tr>';
                                                echo '<td style="width:20%;">' . $row['employeeName'] . '  ('. $row['employeeIDNumber'] .')</td>';
                                                echo '<td style="width:10%;">' . $row['employeeTimeType'] . '</td>';
                                                echo '<td style="width:10%;">' . $row['employeePayType'] . '</td>';
                                                echo '<td style="width:10%;">' . $row['employeeWorkedHours'] . '</td>';
                                                echo '<td style="width:10%;">$' . $row['employeeActualPay'] . '</td>';
                                                echo '<td style="width:10%;">' . $row['employeeDepartment'] . '</td>';
                                                $employeeStatusColorAssignment = $row['employeeStatus'];

                                                if ($employeeStatusColorAssignment == "Active" || $employeeStatusColorAssignment == "active") {
                                                    echo '<td style="width:10%; "><span class="account-status-badge green" style="margin-left:0;">' . $row['employeeStatus'] . '</span></td>';
                                                } else if ($employeeStatusColorAssignment == "Terminated" || $employeeStatusColorAssignment == "terminated") {
                                                   echo '<td style="width:10%; "><span class="account-status-badge passive" style="margin-left:0;">' . $row['employeeStatus'] . '</span></td>';
                                                } else if ($employeeStatusColorAssignment == "New Applicant" || $employeeStatusColorAssignment == "new applicant") {
                                                   echo '<td style="width:10%; "><span class="account-status-badge yellow" style="margin-left:0;">' . $row['employeeStatus'] . '</span></td>';
                                                } else if ($employeeStatusColorAssignment == "Suspended" || $employeeStatusColorAssignment == "Suspended") {
                                                   echo '<td style="width:10%; "><span class="account-status-badge red" style="margin-left:0;">' . $row['employeeStatus'] . '</span></td>';
                                                }

                                                echo '<td class="">
                                                                <a href="/modules/CaliWebDesign/Payroll/ceoSuite/manageEmployees/?employee_number='.$row['employeeIDNumber'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">View</a><a href="/modules/CaliWebDesign/Payroll/ceoSuite/deleteEmployees/?employee_number='.$row['employeeIDNumber'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px; margin-right:10px;">Delete</a><a href="/modules/CaliWebDesign/Payroll/ceoSuite/editEmployees/?employee_number='.$row['employeeIDNumber'].'" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a>
                                                            </td>
                                                     ';
                                            echo '<tr>';

                                        }

                                        echo '</table>'; // Close the table
                                    } else {
                                        echo '
                                            <table style="width:100%; margin-top:1%;">
                                                <tr>
                                                    <th style="width:20%;">Employee Name/ID Number</th>
                                                    <th style="width:10%;">Time Allocation</th>
                                                    <th style="width:10%;">Pay Type</th>
                                                    <th style="width:10%;">Worked Hours</th>
                                                    <th style="width:10%;">Current Pay</th>
                                                    <th style="width:10%;">Department</th>
                                                    <th style="width:10%;">Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                                <tr>
                                                    <td style="width:20%; ">There are no Employees</td>
                                                    <td style="width:10%; "></td>
                                                    <td style="width:10%; "></td>
                                                    <td style="width:10%; "></td>
                                                    <td style="width:10%; "></td>
                                                    <td style="width:10%; "></td>
                                                    <td style="width:10%; "></td>
                                                </tr>
                                            </table>
                                        ';
                                    }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

    } else {
        header("location:/modules/CaliWebDesign/Payroll/employeeView");
    }

?>