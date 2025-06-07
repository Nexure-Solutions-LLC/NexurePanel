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

            <?php

                $accessLevel = $CurrentOnlineAccessAccount->accessLevel;

                $cardsByAccessLevel = [
                    'Executive' => [
                        ['title' => 'Sales Person Activity', 'icon' => 'salesicon.png', 'bg' => '#f5e6fe'],
                        ['title' => 'All Opportunities', 'icon' => 'opportunityicon.png', 'bg' => '#e3f8fa'],
                        ['title' => 'Leads by Source', 'icon' => 'leadsicon.png', 'bg' => '#ffe6e2'],
                        ['title' => "Today's Tasks", 'icon' => 'tasksicon.png', 'bg' => '#e3f8fa'],
                        ['title' => 'All Cases', 'icon' => 'cases.png', 'bg' => '#ffe6e2'],
                        ['title' => 'All Leads', 'icon' => 'leadsicon.png', 'bg' => '#fff9dd'],
                    ],
                    'Manager' => [
                        ['title' => 'Team Sales Activity', 'icon' => 'salesicon.png', 'bg' => '#f5e6fe'],
                        ['title' => 'Managed Opportunities', 'icon' => 'opportunityicon.png', 'bg' => '#e3f8fa'],
                        ['title' => 'Lead Sources Overview', 'icon' => 'leadsicon.png', 'bg' => '#ffe6e2'],
                        ['title' => "Today's Team Tasks", 'icon' => 'tasksicon.png', 'bg' => '#e3f8fa'],
                        ['title' => 'All Open Cases', 'icon' => 'cases.png', 'bg' => '#ffe6e2'],
                        ['title' => 'Managed Leads', 'icon' => 'leadsicon.png', 'bg' => '#fff9dd'],
                    ],
                    'Default' => [
                        ['title' => 'Your Sales Activity', 'icon' => 'salesicon.png', 'bg' => '#f5e6fe'],
                        ['title' => 'Your Opportunities', 'icon' => 'opportunityicon.png', 'bg' => '#e3f8fa'],
                        ['title' => 'Your Lead Sources', 'icon' => 'leadsicon.png', 'bg' => '#ffe6e2'],
                        ['title' => "Today's Tasks", 'icon' => 'tasksicon.png', 'bg' => '#e3f8fa'],
                        ['title' => 'Assigned Cases', 'icon' => 'cases.png', 'bg' => '#ffe6e2'],
                        ['title' => 'Your Leads', 'icon' => 'leadsicon.png', 'bg' => '#fff9dd'],
                    ],
                ];

                $cards = $cardsByAccessLevel[$accessLevel] ?? $cardsByAccessLevel['Default'];
                
                ?>

                <div class="nexure-grid nexure-three-grid gap-row-spacing-30">
                    <?php foreach ($cards as $card): ?>
                        <div class="nexure-card padding-10px">
                            <div class="card-header">
                                <div class="display-flex align-center padding-bottom-10px">
                                    <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                        <img src="/Assets/img/SystemImages/Icons/<?= $card['icon'] ?>" alt="Icon" style="background-color:<?= $card['bg'] ?>;" class="client-business-andor-profile-logo" />
                                    </div>
                                    <p class="no-padding"><strong><?= htmlspecialchars($card['title']) ?></strong></p>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Optional content -->
                            </div>
                            <div class="card-footer">
                                <div class="display-flex align-center justify-content-space-between padding-top-10px">
                                    <a href="" class="brand-link">View Report</a>
                                    <p class="no-padding">NaN UTC</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

        </div>
    </section>

    <section class="nexure-pricing-bar">
        <div class="container nexure-container catalog-container">
            <div class="width-100 display-flex align-center justify-content-space-between">
                <p class="font-14px no-padding no-margin">Licensed To: <?php echo $VariableDefinitionHandler->organizationLegalName; ?></p>
                <p class="font-14px no-padding no-margin">Version 25.0.5</p>
            </div>
        </div>
    </section>


<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>