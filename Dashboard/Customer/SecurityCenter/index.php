<?php

    $PageTitle = "Customer Dashboard";
    $PageSubtitle = "Access and Security";

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
                            <p class="margin-bottom-10px">Overview / Access & Security Center</p>
                        </div>
                        <div>
                            <a class="nexure-button primary">Create Authorized User</a>
                        </div>
                    </div>    
                </div>
                <div class="card-body margin-bottom-10px">
                    <p class="margin-top-20px padding-bottom-10px primary-font"><strong>System Administrators</strong></p>
                    <div class="nexure-table-container no-margin">
                        <table class="nexure-table-plugin nexure-table-domains">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Last Signed On</th>
                                    <th>Suspended On</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                               <tr>
                                    <td colspan="6">No system users available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="margin-top-80px padding-bottom-10px primary-font"><strong>Authorized Users</strong></p>
                    <div class="nexure-table-container no-margin">
                        <table class="nexure-table-plugin nexure-table-domains">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Last Signed On</th>
                                    <th>Suspended On</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                               <tr>
                                    <td colspan="6">No authorized users available</td>
                                </tr>
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
