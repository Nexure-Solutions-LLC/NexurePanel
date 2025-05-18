<?php

    $PageTitle = "Customer Dashboard";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");

?>

<title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | <?php echo $PageTitle; ?></title>

<section class="section dashboard">
    <div class="container nexure-container">
        <div class="nexure-grid nexure-one-grid no-row-gap">
            <div class="nexure-card">
                <div class="card-header">
                    <div class="display-flex justify-content-space-between">
                        <div>
                            <p class="margin-bottom-10px">Overview / Support Center</p>
                        </div>
                        <div>
                            <a class="nexure-button primary">Create Case</a>
                        </div>
                    </div>    
                </div>
                <div class="card-body margin-bottom-10px">
                    <div class="nexure-table-container no-margin">
                        <table class="nexure-table-plugin nexure-table-domains">
                            <thead>
                                <tr>
                                    <th>Case Title</th>
                                    <th>Case Description</th>
                                    <th>Opened</th>
                                    <th>Last Updated</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>
