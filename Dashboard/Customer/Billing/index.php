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
                            <p class="margin-bottom-10px">Overview / Billing</p>
                        </div>
                        <div>
                            <a class="nexure-button primary">Add Payment Method</a>
                        </div>
                    </div>    
                </div>
                <div class="card-body margin-bottom-10px">
                    <div class="nexure-table-container no-margin">
                        <table class="nexure-table-plugin nexure-table-domains">
                            <thead>
                                <tr>
                                    <th>Cardholder Name</th>
                                    <th>Last 4 Digits</th>
                                    <th>Expires</th>
                                    <th>Type</th>
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
