<?php
    $pagetitle = "Client";
    $pagesubtitle = "Billing Center";

    $accountnumber = $_GET['account_number'];

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    if ($userrole == "Authorized User" || $userrole == "authorized user") {

        header("location:/dashboard/customers/authorizedUserView");

    } else if ($userrole == "Partner" || $userrole == "partner") {

        header("location:/dashboard/partnerships");

    } else if ($userrole == "Administrator" || $userrole == "administrator") {

        header("location:/dashboard/administration");

    }

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

?>

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <div class="display-flex align-center" style="justify-content:space-between">
                        <div>
                            <p class="no-padding" style="font-size:16px;">Overview / Billing Center</h4>
                        </div>
                        <div>
                            <a href="" class="caliweb-button primary">Add Payment Method</a>
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
                                        Credit or Debit Cards on file
                                    </h6>
                                </div>
                                <div class="card-body" style="padding-top:20px; padding-bottom:20px;">
                                    <?php

                                        $proccessorResult = mysqli_query($con, "SELECT * FROM caliweb_paymentconfig");
                                        $proccessorInfo = mysqli_fetch_array($proccessorResult);
                                        mysqli_free_result($proccessorResult);

                                        $paymentProccessorName = $proccessorInfo['processorName'];

                                        if ($paymentProccessorName == "Stripe") {

                                            require ($_SERVER["DOCUMENT_ROOT"].'/modules/paymentModule/stripe/index.php');
                                            
                                        }

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </section>

<?php include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php'); ?>