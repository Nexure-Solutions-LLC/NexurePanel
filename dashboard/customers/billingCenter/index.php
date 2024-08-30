<?php

    session_start();

    unset($_SESSION['pagetitle']);
    $pagetitle = $_SESSION['pagetitle'] = "Client";
    $pagesubtitle = $_SESSION['pagesubtitle'] = "Billing Center";
    $pagetype = "Client";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    $customeremail = $currentAccount->email;


?>

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <div class="display-flex align-center" style="justify-content:space-between">
                        <div>
                            <p class="no-padding" style="font-size:16px;">Overview / Billing Center</p>
                        </div>
                        <div>
                            <a href="/dashboard/customers/billingCenter/createPaymentMethod" class="caliweb-button primary">Add Payment Method</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section caliweb-section customer-dashboard-section">
                <div class="container caliweb-container">
                    <div class="caliweb-one-grid">
                        <div class="accounts-overview">
                            <div class="caliweb-card dashboard-card" style="padding:0; background-color:transparent !important; border:0 !important;">
                                <div class="card-header no-padding no-margin customer-card-header" style="padding-top:20px; padding-bottom:20px; border:0;">
                                    <h6 class="no-padding" style="font-size:16px; font-weight:600;">
                                        Saved Payment Cards
                                    </h6>
                                </div>
                                <div class="card-body" style="padding-top:20px; padding-bottom:20px;">
                                    <?php

                                        if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

                                            if ($variableDefinitionX->paymentProcessorName == "Stripe") {

                                                require ($_SERVER["DOCUMENT_ROOT"].'/modules/paymentModule/stripe/index.php');
                                                
                                            }
                                        
                                        }

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </section>

        <div id="paymentMethodModal" class="modal">
            <div class="modal-content">
                <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Are you sure you would like to delete your payment method?</h6>
                <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">What you are about to do is permanent and can't be undone. Are you sure you would like to delete this payment method? You will no longer be able make payments to us which may result in late payments and additional fees unless you add a new card on file.</p>
                <div style="display:flex; align-items:right; justify-content:right;">
                    <a id="deleteLink" href="#" class="caliweb-button secondary red" style="margin-right:20px;">Delete Payment Method</a>
                    <button class="caliweb-button primary" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>

        <script>
            var modal = document.getElementById("paymentMethodModal");

            function openModal(customerEmail, paymentMethodId) {
                deleteLink.href = "/dashboard/customers/billingCenter/deletePaymentMethod/?email_address=" + customerEmail + "&payment_method_id=" + encodeURIComponent(paymentMethodId);
                modal.style.display = "block";
            }

            function closeModal() {
                modal.style.display = "none";
            }
        </script>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>