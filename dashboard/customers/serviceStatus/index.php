<?php
    session_start();
    unset($_SESSION['pagetitle']);
    $pagetitle = $_SESSION['pagetitle'] = "Client";
    $pagesubtitle = $_SESSION['pagesubtitle'] = "Service Status";
    $pagetype = "Client";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php'); 
    
    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';
?>

    <section class="first-dashboard-area-cards">
        <section class="section caliweb-section customer-dashboard-section">
            <div class="container caliweb-container">
                <div class="caliweb-one-grid no-grid-row-bottom">
                    <div class="width-100" style="margin-top:3%;">
                        <div style="margin-left:auto; margin-right:auto; text-align:center;">
                        <h3 class="caliweb-login-heading " style="font-size:25px;">Cali Web Design <span style="font-size:25px;font-weight:700">Service Status</span></h3>
                        <p class="width-50 font-14px" style="margin-left:auto; max-width: 650px; margin-right:auto;">Cali Web Design offers stellar world-class uptime and network availability. Listed below are all our services and their status. If there is an outage it would be reported here.</p>
                        </div>
                    </div>
                    <div class="service-status-bar width-75" style=" font-size: 18px; font-weight: 400;!important; margin-left:auto; margin-right:auto;">
                        <div class="circle"></div>All Services Currently Online
                        <span class="tooltip-text">
                            Operational For 69420 Days
                        </span>
                    </div>
                </div>
            </div>
        </section>
        <section class="section caliweb-section customer-dashboard-section" style="padding-bottom:6%;">
            <div class="container caliweb-container" style="padding-top:4%;">
                <div class="caliweb-one-grid no-grid-row-bottom width-75" style="margin-left:auto; margin-right:auto;">
                <div class="status-data-bar-box" style="position:relative;">
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500;">API</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight:500; text-align:right;">69% Uptime</p>
                            </div>
                        </div>
                        <div class="status-data-bar">
                            <?php
                                $starting_string = '
                                <div class="status-data-small-div">
                                    <span class="tooltip-text">
                                        Operational
                                        <div class="border-line"></div>
                                        July 1st, 2024
                                    </span>
                                </div>';
                                echo str_repeat($starting_string, 150);
                            ?>
                        </div>
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px;">150 Days</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px; text-align:right;">Today</p>
                            </div>
                        </div>
                    </div>
                    <div class="status-data-bar-box" style="position:relative;">
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500;">API</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight:500; text-align:right;">69% Uptime</p>
                            </div>
                        </div>
                        <div class="status-data-bar">
                            <?php
                                $starting_string = '
                                <div class="status-data-small-div">
                                    <span class="tooltip-text">
                                        Operational
                                        <div class="border-line"></div>
                                        July 1st, 2024
                                    </span>
                                </div>';
                                echo str_repeat($starting_string, 150);
                            ?>
                        </div>
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px;">150 Days</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px; text-align:right;">Today</p>
                            </div>
                        </div>
                    </div>
                    <div class="status-data-bar-box" style="position:relative;">
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500;">API</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight:500; text-align:right;">69% Uptime</p>
                            </div>
                        </div>
                        <div class="status-data-bar">
                            <?php
                                $starting_string = '
                                <div class="status-data-small-div">
                                    <span class="tooltip-text">
                                        Operational
                                        <div class="border-line"></div>
                                        July 1st, 2024
                                    </span>
                                </div>';
                                echo str_repeat($starting_string, 150);
                            ?>
                        </div>
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px;">150 Days</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px; text-align:right;">Today</p>
                            </div>
                        </div>
                    </div>
                    <div class="status-data-bar-box" style="position:relative;">
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500;">API</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight:500; text-align:right;">69% Uptime</p>
                            </div>
                        </div>
                        <div class="status-data-bar">
                            <?php
                                $starting_string = '
                                <div class="status-data-small-div">
                                    <span class="tooltip-text">
                                        Operational
                                        <div class="border-line"></div>
                                        July 1st, 2024
                                    </span>
                                </div>';
                                echo str_repeat($starting_string, 150);
                            ?>
                        </div>
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px;">150 Days</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px; text-align:right;">Today</p>
                            </div>
                        </div>
                    </div>
                    <div class="status-data-bar-box" style="position:relative;">
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500;">API</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight:500; text-align:right;">69% Uptime</p>
                            </div>
                        </div>
                        <div class="status-data-bar">
                            <?php
                                $starting_string = '
                                <div class="status-data-small-div">
                                    <span class="tooltip-text">
                                        Operational
                                        <div class="border-line"></div>
                                        July 1st, 2024
                                    </span>
                                </div>';
                                echo str_repeat($starting_string, 150);
                            ?>
                        </div>
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px;">150 Days</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px; text-align:right;">Today</p>
                            </div>
                        </div>
                    </div>
                    <div class="status-data-bar-box" style="position:relative;">
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500;">API</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight: 500; text-align:right; ">69% Uptime</p>
                            </div>
                        </div>
                        <div class="status-data-bar">
                            <?php
                                $starting_string = '
                                <div class="status-data-small-div">
                                    <span class="tooltip-text">
                                        Operational
                                        <div class="border-line"></div>
                                        July 1st, 2024
                                    </span>
                                </div>';
                                echo str_repeat($starting_string, 150);
                            ?>
                        </div>
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px; ">150 Days</p>
                            </div>
                            <div>
                                <p style="font-size:16px; font-weight:500; margin-bottom:18px; text-align:right;">Today</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <section class="first-dashboard-area-cards">
        <section class="section caliweb-section customer-dashboard-section">
            <div class="container caliweb-container">

            </div>
        </section>
    </section>
    




<?php
    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');
?>
