<?php
    $pagetitle = "Payroll";
    $pagesubtitle = "Employees Listing";

    unset($_SESSION['verification_code']);

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
                                <img src="/assets/img/systemIcons/accountsicon.png" alt="Employee Page Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
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
                                    $sql = "SELECT * FROM caliweb_payroll";
                                    $result = mysqli_query($con, $sql);

                                    // Check if any rows were returned
                                    if (mysqli_num_rows($result) > 0) {
                                        // Output table header
                                        echo '<table style="width:100%;">
                                                <tr>
                                                    <th style="width:20%;">Company/Account Number</th>
                                                    <th style="width:20%;">Owner</th>
                                                    <th style="width:20%;">Phone</th>
                                                    <th style="width:20%;">Type</th>
                                                    <th style="width:10%;">Status</th>
                                                    <th>Actions</th>
                                                </tr>';

                                        // Output table rows
                                        while ($row = mysqli_fetch_assoc($result)) {

                                            

                                        }

                                        echo '</table>'; // Close the table
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

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>