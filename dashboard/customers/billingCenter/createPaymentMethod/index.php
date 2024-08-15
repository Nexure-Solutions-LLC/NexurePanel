<?php

    session_start();

    unset($_SESSION['pagetitle']);
    $pagetitle = $_SESSION['pagetitle'] = "Client";
    $pagesubtitle = $_SESSION['pagesubtitle'] = "Billing Center";
    $pagetype = "Client";

    $accountnumber = $_GET['account_number'];

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    $storedAccountNumber = $currentAccount->accountNumber;

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    if ($accountnumber == $storedAccountNumber) {

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

                                    $result = mysqli_query($con, "SELECT * FROM caliweb_paymentconfig WHERE id = '1'");
                                    $paymentgateway = mysqli_fetch_array($result);

                                    // Free payment processor check result set

                                    mysqli_free_result($result);

                                    $variableDefinitionX->apiKeysecret = $paymentgateway['secretKey'];
                                    $variableDefinitionX->apiKeypublic = $paymentgateway['publicKey'];
                                    $paymentgatewaystatus = $paymentgateway['status'];
                                    $paymentProcessorName = $paymentgateway['processorName'];

                                    // Checks type of payment processor.

                                    if ($variableDefinitionX->apiKeysecret != "" && $paymentgatewaystatus == "Active" || $paymentgatewaystatus == "active") {

                                        if ($paymentProcessorName == "Stripe") {

                                            require ($_SERVER["DOCUMENT_ROOT"].'/modules/paymentModule/stripe/internalPayments/index.php');
                                        
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

    } else {

        echo '<script type="text/javascript">window.location = "/dashboard/customers/billingCenter/?account_number='.$storedAccountNumber.'"</script>';

    }

    include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/clientside.php");
    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>