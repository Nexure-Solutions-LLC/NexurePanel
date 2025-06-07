<?php

    $PageTitle = "Dashboard";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");

?>

    <title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | <?php echo $PageTitle; ?></title>


    <section class="section dashboard">
        <div class="container nexure-container">
            <div class="nexure-grid nexure-three-grid gap-row-spacing-30">
                <div class="nexure-card">
                    <h4 class="font-18px text-bold no-padding">Engage with your Customers</h4>
                </div>
                <div class="nexure-card">
                    <h4 class="font-18px text-bold no-padding">Manage and Close Deals</h4>
                </div>
                <div class="nexure-card">
                    <h4 class="font-18px text-bold no-padding">Build your Pipeline</h4>
                </div>
            </div>
            <div class="nexure-grid nexure-three-grid gap-row-spacing-30">
                <div class="nexure-card padding-10px">
                    <div class="card-header">
                        <div class="display-flex align-center padding-bottom-10px">
                            <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                <img src="/Assets/img/SystemImages/Icons/salesicon.png" alt="Sales Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                            </div>
                            <p class="no-padding"><strong>Sales Person Activity</strong></p>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                    <div class="card-footer">
                        <div class="display-flex align-center justify-content-space-between padding-top-10px">
                            <a href="" class="brand-link">View Report</a>
                            <p class="no-padding">NaN UTC</p>
                        </div>
                    </div>
                </div>
                <div class="nexure-card">
                    <div class="card-header">
                        <div class="display-flex align-center padding-bottom-10px">
                            <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                <img src="/Assets/img/SystemImages/Icons/opportunityicon.png" alt="Sales Icon" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                            </div>
                            <p class="no-padding"><strong>All Opportunities</strong></p>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                    <div class="card-footer">
                        <div class="display-flex align-center justify-content-space-between padding-top-10px">
                            <a href="" class="brand-link">View Report</a>
                            <p class="no-padding">NaN UTC</p>
                        </div>
                    </div>
                </div>
                <div class="nexure-card">
                    <div class="card-header">
                        <div class="display-flex align-center padding-bottom-10px">
                            <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                <img src="/Assets/img/SystemImages/Icons/leadsicon.png" alt="Sales Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                            </div>
                            <p class="no-padding"><strong>Leads by Source</strong></p>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                    <div class="card-footer">
                        <div class="display-flex align-center justify-content-space-between padding-top-10px">
                            <a href="" class="brand-link">View Report</a>
                            <p class="no-padding">NaN UTC</p>
                        </div>
                    </div>
                </div>
                <div class="nexure-card">
                    <div class="card-header">
                        <div class="display-flex align-center padding-bottom-10px">
                            <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                <img src="/Assets/img/SystemImages/Icons/tasksicon.png" alt="Sales Icon" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                            </div>
                            <p class="no-padding"><strong>Today's Tasks</strong></p>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                    <div class="card-footer">
                        <div class="display-flex align-center justify-content-space-between padding-top-10px">
                            <a href="" class="brand-link">View Report</a>
                            <p class="no-padding">NaN UTC</p>
                        </div>
                    </div>
                </div>
                <div class="nexure-card">
                    <div class="card-header">
                        <div class="display-flex align-center padding-bottom-10px">
                            <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                <img src="/Assets/img/SystemImages/Icons/cases.png" alt="Sales Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                            </div>
                            <p class="no-padding"><strong>All Cases</strong></p>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                    <div class="card-footer">
                        <div class="display-flex align-center justify-content-space-between padding-top-10px">
                            <a href="" class="brand-link">View Report</a>
                            <p class="no-padding">NaN UTC</p>
                        </div>
                    </div>
                </div>
                <div class="nexure-card">
                    <div class="card-header">
                        <div class="display-flex align-center padding-bottom-10px">
                            <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                <img src="/Assets/img/SystemImages/Icons/leadsicon.png" alt="Sales Icon" style="background-color:#fff9dd;" class="client-business-andor-profile-logo" />
                            </div>
                            <p class="no-padding"><strong>All Leads</strong></p>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                    <div class="card-footer">
                        <div class="display-flex align-center justify-content-space-between padding-top-10px">
                            <a href="" class="brand-link">View Report</a>
                            <p class="no-padding">NaN UTC</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>