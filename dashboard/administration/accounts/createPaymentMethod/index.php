<?php

    // Initialize page variables

    unset($_SESSION['pagetitle']);
    $_SESSION['pagetitle'] = "Payment Methods";
    $pagetitle = "Payment Methods";
    $pagesubtitle = "Create Payment Method";
    $pagetype = "Administration";

    require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');

    // Retrieve account number from query parameters and check the account number and if its present

    $accountnumber = $_GET['account_number'] ?? '';

    if (empty($accountnumber)) {

        header("Location: /dashboard/administration/accounts");
        exit;

    }

    // Fetch customer account information

    $accountnumberEscaped = mysqli_real_escape_string($con, $accountnumber);

    $query = "SELECT * FROM caliweb_users WHERE accountNumber = '$accountnumberEscaped'";
    $result = mysqli_query($con, $query);
    $customerAccountInfo = mysqli_fetch_array($result);
    mysqli_free_result($result);

    // Check the customer information value to ensure its not empty or null

    if (!$customerAccountInfo) {

        echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';
        exit;

    }

    // Handle form submission

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Check the payment proccessor to see if its stripe. When form is submitted

        if ($variableDefinitionX->paymentProcessorName === "Stripe") {

            require($_SERVER["DOCUMENT_ROOT"] . '/modules/paymentModule/stripe/internalPayments/index.php');

        }

    } else {

        // Check the payment proccessor to see if its stripe. When page is loaded.

        if ($variableDefinitionX->paymentProcessorName === "Stripe") {

            require($_SERVER["DOCUMENT_ROOT"] . '/modules/paymentModule/stripe/index.php');

        }

        include($_SERVER["DOCUMENT_ROOT"] . '/components/CaliHeaders/Dashboard.php');

        echo '<title>' . htmlspecialchars($pagetitle) . ' | ' . htmlspecialchars($pagesubtitle) . '</title>';

?>

        <style>
            input[type=number] {
                -moz-appearance: textfield;
            }
            input[type=number]::-webkit-outer-spin-button,
            input[type=number]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
        </style>
        <section class="section first-dashboard-area-cards">
            <div class="container width-98">
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <div class="caliweb-card dashboard-card">
                        <form method="POST" action="">
                            <div class="card-header">
                                <div class="display-flex align-center" style="justify-content: space-between;">
                                    <div class="display-flex align-center">
                                        <div class="no-padding margin-10px-right icon-size-formatted">
                                            <img src="/assets/img/systemIcons/payment-methods-add.png" alt="Create Services Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                        </div>
                                        <div>
                                            <p class="no-padding font-14px">Payment Services</p>
                                            <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0; padding-top:5px;">Attach Payment Method</h4>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" type="submit" name="submit">Save</button>
                                        <a href="/dashboard/administration/accounts/createPaymentMethod/?account_number=<?php echo urlencode($accountnumber); ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                        <a href="/dashboard/administration/accounts/manageAccount/?account_number=<?php echo urlencode($accountnumber); ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="card-element" style="padding:10px; width:40%; background-color:#F8F8F8; border-radius:8px; box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px; border:1px solid #ddd; margin-top:-2%; margin-bottom:2%;">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

<?php

        include($_SERVER["DOCUMENT_ROOT"] . "/modules/paymentModule/stripe/internalPayments/clientside.php");
        include($_SERVER["DOCUMENT_ROOT"] . '/components/CaliFooters/Dashboard.php');

    }

?>
