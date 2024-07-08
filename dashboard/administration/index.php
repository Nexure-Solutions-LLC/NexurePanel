<?php
    session_start();
    $pagetitle = "Administration Dashboard";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    if ($userrole == "Customer" || $userrole == "customer") {
        header("location:/dashboard/customers");
    } else if ($userrole == "Authorized User" || $userrole == "authorized user") {
        header("location:/dashboard/customers/authorizedUserView");
    } else if ($userrole == "Partner" || $userrole == "partner") {
        header("location:/dashboard/partnerships");
    }

    echo '<title>'.$pagetitle.'</title>';
?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-three-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card">
                    <div class="card-body">                
                        <h4 class="text-bold font-size-20 no-padding">Engage with your Customers</h4>
                    </div>
                </div>
                <div class="caliweb-card dashboard-card">
                    <div class="card-body">                
                        <h4 class="text-bold font-size-20 no-padding">Manage and Close Deals</h4>
                    </div>
                </div>
                <div class="caliweb-card dashboard-card">
                    <div class="card-body">                
                        <h4 class="text-bold font-size-20 no-padding">Build your Pipeline</h4>
                    </div>
                </div>

                <?php if ($employeeAccessLevel == "Executive") { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">Sales Person Activity</p>
                        </div>
                        <div class="card-body">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Sales Person Activity";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/graphSQL/barGraphs/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } else if ($employeeAccessLevel == "Manager") { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">Sales Person Activity</p>
                        </div>
                        <div class="card-body">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Sales Person Activity";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/graphSQL/barGraphs/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } else { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">Your Sales Activity</p>
                        </div>
                        <div class="card-body">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Employee Only Sales Activity";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/graphSQL/barGraphs/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } ?>

                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <p class="no-padding">Team Pipeline</p>
                    </div>
                    <div class="card-body">                
                        <h4 class="text-bold font-size-20 no-padding">2</h4>
                    </div>
                    <div class="card-footer">
                        <a href="" class="careers-link">View Report</a>
                        <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                    </div>
                </div>
                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <p class="no-padding">Leads by Source</p>
                    </div>
                    <div class="card-body">
                             <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Deals by Segment";
                                $_SESSION['graphCallType'] = $graphCallType;

                                include($_SERVER["DOCUMENT_ROOT"].'/modules/graphSQL/pieCharts/index.php');
                             ?>
                    </div>
                    <div class="card-footer">
                        <a href="" class="careers-link">View Report</a>
                        <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                    </div>
                </div>

                <?php if ($employeeAccessLevel == "Executive") { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">Today's Tasks</p>
                        </div>
                        <div class="card-body">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Tasks Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/caliTables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Tasks</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } else if ($employeeAccessLevel == "Manager") { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">Today's Tasks</p>
                        </div>
                        <div class="card-body">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Tasks Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/caliTables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Tasks</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } else { ?> 
                    
                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">Today's Tasks</p>
                        </div>
                        <div class="card-body">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Tasks Table Employee Only";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/caliTables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Tasks</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } ?>
                <?php if ($employeeAccessLevel == "Executive") { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">All Cases</p>
                        </div>
                        <div class="card-body">   

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Cases Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/caliTables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Cases</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } else if ($employeeAccessLevel == "Manager") { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">All Cases</p>
                        </div>
                        <div class="card-body">      

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Cases Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/caliTables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Cases</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } else { ?>  

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">My Cases</p>
                        </div>
                        <div class="card-body">   

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Cases Table Employee Only";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/caliTables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Cases</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } ?>
                <?php if ($employeeAccessLevel == "Executive") { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">All Leads</p>
                        </div>
                        <div class="card-body">                
                            
                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Leads Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/caliTables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } else if ($employeeAccessLevel == "Manager") { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">All Leads</p>
                        </div>
                        <div class="card-body">     

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Leads Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/caliTables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } else { ?>  

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <p class="no-padding">My Leads</p>
                        </div>
                        <div class="card-body">    
                                        
                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Leads Table Employee Only";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"].'/modules/caliTables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } ?>
                
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>