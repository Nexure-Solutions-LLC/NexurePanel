<?php

    $pagetitle = "Leads";
    $pagesubtitle = "List of Leads";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/tables/leadTables/index.php');
    
    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">

                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div class="display-flex align-center" style="justify-content: space-between;">
                            <div class="display-flex align-center">
                                <div class="no-padding margin-10px-right icon-size-formatted">
                                    <img src="/assets/img/systemIcons/leadsicon.png" alt="Leads Page Icon" style="background-color:#fff9dd;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">Leads</p>
                                    <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">List Leads</h4>
                                </div>
                            </div>
                            <div>
                                <a href="/dashboard/administration/leads/createLead/" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            
                            <?php

                                $sql = "SELECT * FROM caliweb_leads";

                                function displayTasks($con, $baseSql, $accessLevel, $currentUser)
                                {

                                    if ($accessLevel === "Executive" || $accessLevel === "Manager") {

                                        $sql = $baseSql;

                                    } else {

                                        $sql = $baseSql . " WHERE assignedAgent = '{$currentUser}'";
                                    }

                                    leadsHomeListingTable(
                                        $con,
                                        $sql,
                                        ['Assigned Agent', 'Customer Name', 'Account Number', 'Status', 'Actions'],
                                        ['assignedAgent', 'customerName', 'accountNumber', 'status'],
                                        ['30%', '20%', '20%', '20%', '10%'],
                                        [
                                            'View' => "/dashboard/administration/leads/viewLead/?task_id={id}",
                                            'Edit' => "/dashboard/administration/tasks/editLead/?task_id={id}",
                                            'Delete' => "openModal({id})"
                                        ]
                                    );
                                }

                                displayTasks($con, $sql, $currentAccount->accessLevel->name, $currentAccount->legalName);

                            ?>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>