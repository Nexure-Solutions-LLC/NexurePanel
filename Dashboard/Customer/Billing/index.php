<?php

    $PageTitle = "Customer Dashboard";
    $PageSubtitle = "Billing Center";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");

?>

<script src="https://js.stripe.com/v3/"></script>

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
                            <a href="javascript:void(0)" onclick="openModal()" class="nexure-button primary">Add Payment Method</a>
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
                                <tr>
                                    <td colspan="6">No billing information available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="paymentMethodModal" class="modal">
    <div class="modal-content">
        <form method="POST" action="/Modules/Stripe/Payments/Backend/AddCard/index.php" id="nexure-form-plugin">
            <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Attach payment method</h6>
            <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">Submitting your card information in this form will attach your payment card to your <?php echo $VariableDefinitionHandler->organizationShortName; ?> account.</p>
            
            <div class="width-60">
                <div id="card-element" class="nexure-textbox cardinfo"></div>
            </div>
            
            <div style="display:flex; align-items:right; justify-content:right;">
                <button id="submit" class="nexure-button primary">Add payment card</button>
                <a class="nexure-button secondary" href="javascript:void(0)" onclick="closeModal()">Close</a>
            </div>
        </form>
    </div>
</div>

<script>
    var modal = document.getElementById("paymentMethodModal");

    function openModal() {
        modal.style.display = "block";
    }

    function closeModal() {
        modal.style.display = "none";
    }

</script>


<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/Stripe/Payments/Frontend/index.php");

?>
