<?php

    session_start();

    unset($_SESSION['pagetitle']);
    $pagetitle = $_SESSION['pagetitle'] = "Client";
    $pagesubtitle = $_SESSION['pagesubtitle'] = "Billing Center";
    $pagetype = "Client";

    $accountnumber = $_GET['account_number'];

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    $storedAccountNumber = $currentAccount->accountNumber;

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

    if ($accountnumber == $storedAccountNumber) {

?>

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <div class="display-flex align-center" style="justify-content:space-between">
                        <div>
                            <p class="no-padding" style="font-size:16px;">Overview / Billing Center</p>
                        </div>
                        <div>
                            <a href="/dashboard/customers/billingCenter/createPaymentMethod?account_number=<?php echo $accountnumber; ?>" class="caliweb-button primary">Add Payment Method</a>
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

<?php

    } else {

        echo '<script type="text/javascript">window.location = "/dashboard/customers/billingCenter/?account_number='.$storedAccountNumber.'"</script>';

    }

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>