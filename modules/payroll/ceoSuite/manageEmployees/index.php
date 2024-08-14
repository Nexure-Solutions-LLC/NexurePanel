<?php

    $pagetitle = "Payroll";
    $pagesubtitle = "Employee Management";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <?php include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Employee.php'); ?>
                <div class="caliweb-two-grid special-caliweb-spacing account-grid-modified">
                    <div>
                        <div class="caliweb-card dashboard-card">
                            <?php include($_SERVER["DOCUMENT_ROOT"].'/components/CaliMenus/Employee.php'); ?>
                            <div class="caliweb-card dashboard-card">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <div>
                                            <p class="no-padding">Contracts</p>
                                        </div>
                                        <div class="display-flex align-center">
                                            <a href="" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">View Contracts</a>

                                            <a href="" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Create Contract</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <table style="width:100%;">
                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card" style="margin-top:10px;">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <div>
                                            <p class="no-padding">Diciplin Documents</p>
                                        </div>
                                        <div class="display-flex align-center">
                                            <a href="" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">View Diciplin Documents</a>

                                            <a href="" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Create Diciplin Document</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <table style="width:100%;">
                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="caliweb-card dashboard-card" style="margin-top:10px; margin-bottom:2%;">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <div>
                                            <p class="no-padding">Time Keeping</p>
                                        </div>
                                        <div class="display-flex align-center">
                                            <a href="" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">View Time Keeping</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <table style="width:100%;">
                                         
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>