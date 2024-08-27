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

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>