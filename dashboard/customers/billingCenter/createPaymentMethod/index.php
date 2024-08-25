<?php

    session_start();

    unset($_SESSION['pagetitle']);
    $pagetitle = $_SESSION['pagetitle'] = "Client";
    $pagesubtitle = $_SESSION['pagesubtitle'] = "Billing Center";
    $pagetype = "Client";

    $accountnumber = $_GET['account_number'];

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    $storedAccountNumber = $currentAccount->accountNumber;

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    if ($accountnumber == $storedAccountNumber) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check the payment proccessor to see if its stripe. When form is submitted

            if ($variableDefinitionX->paymentProcessorName === "Stripe") {

                require($_SERVER["DOCUMENT_ROOT"] . '/modules/paymentModule/stripe/internalPayments/index.php');

            }

        }

?>

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <div class="display-flex align-center" style="justify-content:space-between">
                        <div>
                            <p class="no-padding" style="font-size:16px;">Overview / Billing Center/ Create Payment Method</p>
                        </div>
                        <div>
                            <a href="/dashboard/customers/billingCenter/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button primary">Return to Billing Center</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section caliweb-section customer-dashboard-section">
                <div class="container caliweb-container">
                    <div class="caliweb-one-grid">
                        <div class="accounts-overview">
                            <div class="caliweb-card dashboard-card" style="padding:0; background-color:transparent !important; border:0 !important;">
                                <div class="card-body" style="padding-top:20px; padding-bottom:20px;">
                                    <?php

                                        if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

                                            if ($variableDefinitionX->paymentProcessorName == "Stripe") {

                                                echo '

                                                    <div id="card-element" style="padding:10px; width:40%; background-color:#F8F8F8; border-radius:8px; box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px; border:1px solid #ddd; margin-top:0%; margin-bottom:2%;"></div>

                                                ';

                                            } else {

                                                header ("location: /error/genericSystemError");

                                            }

                                        } else {

                                            echo 'There are no payment modules available to service this request.';

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

    include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/clientside.php");
    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>