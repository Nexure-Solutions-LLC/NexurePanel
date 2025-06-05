<?php

    $PageTitle = "Customer Dashboard";
    $PageSubtitle = "Customer Service";

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
                            <a href="javascript:void(0)" onclick="openSupportCaseModal()" class="nexure-button primary">Create Case</a>
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
                                <tr>
                                    <td colspan="6">No support information available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="modalSupportCase" class="modal">
    <div class="modal-content" style="margin-top:10%;">
        <form method="POST" action="/Dashboard/Customer/SupportCenter/CreateCase">
            <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Create a support case</h6>
            <div style="font-size:14px; padding-top:30px; padding-bottom:30px;">
                <div class="form-control">
                    <input class="nexure-textbox grey-400" id="caseTitle" type="text" maxlenghth="10" name="caseTitle" placeholder="I need help with..." />
                </div>
                <div class="form-control">
                    <textarea class="nexure-textbox grey-400" id="caseDescription" type="text" style="height:250px" name="caseDescription" placeholder="Please describe your issue in detail so that our team can effectively help you."></textarea>
                </div>
            </div>
            <div style="display:flex; align-items:right; justify-content:right;">
                <button class="nexure-button primary" type="submit" name="submit">Submit Case</button>
                <a class="nexure-button secondary" href="javascript:void(0)" onclick="closeSupportCaseModal()">Close</a>
            </div>
        </form>
    </div>
</div>

<script>
    var modalSupportCase = document.getElementById("modalSupportCase");

    function openSupportCaseModal() {
        modalSupportCase.style.display = "block";
    }

    function closeSupportCaseModal() {
        modalSupportCase.style.display = "none";
    }
</script>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>
