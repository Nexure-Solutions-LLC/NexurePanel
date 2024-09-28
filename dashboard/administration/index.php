<?php
    session_start();

    $pagetitle = "Home";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    echo '<title>' . $pagetitle . ' | ' . $variableDefinitionX->orgShortName . '</title>';

    try {
?>

    <section class="section first-dashboard-area-cards dashboard-admin-cards-withtoolbar">
        <div class="container width-98" style="margin-bottom:1%;">
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


                <?php if ($currentAccount->accessLevel->name == "Executive") { ?>


                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/salesicon.png" alt="Sales Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>Sales Person Activity</strong></p>
                            </div>
                        </div>
                        <div class="card-body">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Sales Person Activity";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/graphSQL/barGraphs/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>


                <?php } else if ($currentAccount->accessLevel->name == "Manager") { ?>


                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/salesicon.png" alt="Sales Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>Sales Person Activity</strong></p>
                            </div>
                        </div>
                        <div class="card-body">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Sales Person Activity";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/graphSQL/barGraphs/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>


                <?php } else { ?>


                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/salesicon.png" alt="Sales Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>Your Sales Activity</strong></p>
                            </div>
                        </div>
                        <div class="card-body">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Employee Only Sales Activity";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/graphSQL/barGraphs/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>


                <?php } ?>


                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div class="display-flex align-center">
                            <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                <img src="/assets/img/systemIcons/opportunityicon.png" alt="Opportunities Icon" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                            </div>
                            <p class="no-padding"><strong>All Opportunities</strong></p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="" style="margin-left:auto; margin-right:auto; text-align:center; padding-bottom:4%;">
                            <img src="/assets/img/graphicsVectorDrawings/opportunitiesNoContent.svg" style="width:40%; height:20vh; margin-top:2%;" alt="Pie Chart Not Found Graphic Vector">
                            <p style="margin-top:4%; font-size:14px;">Track progress as you find opportunities.</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="" class="careers-link">View Report</a>
                        <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                    </div>
                </div>


                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div class="display-flex align-center">
                            <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                <img src="/assets/img/systemIcons/leadsbysourceicon.png" alt="Leads by Source Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                            </div>
                            <p class="no-padding"><strong>Leads by Source</strong></p>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                            unset($_SESSION['graphCallType']);
                            $graphCallType = "Deals by Segment";
                            $_SESSION['graphCallType'] = $graphCallType;

                            include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/graphSQL/pieCharts/index.php');
                        ?>
                    </div>
                    <div class="card-footer">
                        <a href="" class="careers-link">View Report</a>
                        <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                    </div>
                </div>

                <?php if ($currentAccount->accessLevel->name == "Executive") { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/tasksicon.png" alt="Tasks Icon" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>Today's Tasks</strong></p>
                            </div>
                        </div>
                        <div class="card-body" style="height:260px; overflow-y:scroll;">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Tasks Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/tables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Tasks</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } else if ($currentAccount->accessLevel->name == "Manager") { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/tasksicon.png" alt="Tasks Icon" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>Today's Tasks</strong></p>
                            </div>
                        </div>
                        <div class="card-body" style="height:260px; overflow-y:scroll;">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Tasks Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/tables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Tasks</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } else { ?>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/tasksicon.png" alt="Tasks Icon" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>Today's Tasks</strong></p>
                            </div>
                        </div>
                        <div class="card-body" style="height:260px; overflow-y:scroll;">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Tasks Table Employee Only";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/tables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Tasks</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>

                <?php } ?>


                <?php if ($currentAccount->accessLevel->name == "Executive") { ?>


                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/cases.png" alt="Cases Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>All Cases</strong></p>
                            </div>
                        </div>
                        <div class="card-body" style="height:260px; overflow-y:scroll;">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Cases Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/tables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Cases</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>


                <?php } else if ($currentAccount->accessLevel->name == "Manager") { ?>


                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/cases.png" alt="Cases Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>All Cases</strong></p>
                            </div>
                        </div>
                        <div class="card-body" style="height:260px; overflow-y:scroll;">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Cases Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/tables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Cases</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>


                <?php } else { ?>


                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/cases.png" alt="Cases Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>My Cases</strong></p>
                            </div>
                        </div>
                        <div class="card-body" style="height:260px; overflow-y:scroll;">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Cases Table Employee Only";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/tables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Cases</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>


                <?php } ?>


                <?php if ($currentAccount->accessLevel->name == "Executive") { ?>


                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/leadsicon.png" alt="Leads Icon" style="background-color:#fff9dd;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>All Leads</strong></p>
                            </div>
                        </div>
                        <div class="card-body" style="height:260px; overflow-y:scroll;">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Leads Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/tables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>


                <?php } else if ($currentAccount->accessLevel->name == "Manager") { ?>


                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/leadsicon.png" alt="Leads Icon" style="background-color:#fff9dd;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>All Leads</strong></p>
                            </div>
                        </div>
                        <div class="card-body" style="height:260px; overflow-y:scroll;">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Leads Table";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/tables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>


                <?php } else { ?>


                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center">
                                <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                    <img src="/assets/img/systemIcons/leadsicon.png" alt="Leads Icon" style="background-color:#fff9dd;" class="client-business-andor-profile-logo" />
                                </div>
                                <p class="no-padding"><strong>My Leads</strong></p>
                            </div>
                        </div>
                        <div class="card-body" style="height:260px; overflow-y:scroll;">

                            <?php
                                unset($_SESSION['graphCallType']);
                                $graphCallType = "Dashboard Leads Table Employee Only";
                                $_SESSION['graphCallType'] = $graphCallType;
                                include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/tables/index.php');
                            ?>

                        </div>
                        <div class="card-footer">
                            <a href="" class="careers-link">View Report</a>
                            <p class="no-padding"><?php echo $variableDefinitionX->datedataOutput; ?> UTC</p>
                        </div>
                    </div>


                <?php } ?>


            </div>
        </div>

        <section class="caliweb-pricing-bar">
            <div class="container" style="width:100%; max-width:98%;">
                <div class="caliweb-two-grid" style="align-items:center; -ms-grid-columns: 1fr 1fr; grid-template-columns: 1fr 1fr;">
                    <div class="pricing-content-new">
                        <p style="font-size:14px;">Licensed to: <?php echo $variableDefinitionX->orglegalName; ?></p>
                    </div>
                    <div class="pricing-button-content-new">
                        <p style="font-size:14px; float:right;">Version: <?php echo $variableDefinitionX->panelVersionName; ?></p>
                    </div>
                </div>
            </div>
        </section>

    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const lightModeIcon = document.getElementById("lightModeIcon");
            const darkModeIcon = document.getElementById("darkModeIcon");
            const graphImg = document.getElementById("graph");

            function updateTheme(theme) {

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "/modules/CaliWebDesign/Utility/graphSQL/themeingRequirement/index.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onload = function() {

                    if (xhr.status === 200) {

                        var response = JSON.parse(xhr.responseText);

                        if (response.status === 'success') {

                            var currentSrc = graphImg.src.split('?')[0];
                            graphImg.src = currentSrc + '?theme=' + response.theme + '&t=' + new Date().getTime();

                        } else {

                            console.error('Error setting theme:', response.message);

                        }

                    }

                };

                xhr.send("theme=" + theme);

            }

            lightModeIcon.addEventListener("click", function() {

                document.body.classList.remove("dark-mode");
                updateTheme('light-mode');

            });

            darkModeIcon.addEventListener("click", function() {

                document.body.classList.add("dark-mode");
                updateTheme('dark-mode');

            });

            // Initial theme setting on page load

            if (document.body.classList.contains("dark-mode")) {

                updateTheme('dark-mode');

            } else {

                updateTheme('light-mode');

            }
        });
    </script>

<?php

        include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');
        
    } catch (\Throwable $exception) {

        \Sentry\captureException($exception);
    }

?>