<?php

    // Initialize page variables

    unset($_SESSION['pagetitle']);
    $_SESSION['pagetitle'] = "Payment Methods";
    $pagetitle = "Payment Methods";
    $pagesubtitle = "Create Payment Method";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    // Retrieve account number from query parameters and check the account number and if its present

    $accountnumber = $_GET['account_number'] ?? '';

    if (empty($accountnumber)) {

        header("Location: /dashboard/administration/accounts");
        exit;

    }

    $manageAccountDefinitionR = new \CaliWebDesign\Generic\VariableDefinitions();
    $manageAccountDefinitionR->manageAccount($con, $accountnumber);

    $_SESSION['ACCOUNTNUMBERCUST'] = $accountnumber;

    $_SESSION['stripe_id'] = $manageAccountDefinitionR->customerStripeID;

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
                    <form method="POST" id="caliweb-form-plugin" action="/dashboard/administration/accounts/createPaymentMethod/addCardLogic">
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

                            <?php

                                if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

                                    if ($variableDefinitionX->paymentProcessorName == "Stripe") {

                                        echo '

                                            <div id="card-element" style="padding:10px; width:40%; background-color:#F8F8F8; border-radius:8px; box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px; border:1px solid #ddd; margin-top:-2%; margin-bottom:2%;"></div>

                                        ';

                                    } else {

                                        header ("location: /error/genericSystemError");

                                    }

                                } else {

                                    echo 'There are no payment modules available to service this request.';

                                }

                            ?>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        var stripe = Stripe('<?php echo $variableDefinitionX->apiKeypublic; ?>');

        var elements = stripe.elements();

        var style = {
            base: {
                color: '#32325d',
                fontFamily: 'Arial, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                },
                backgroundColor: '#f8f8f8',
                padding: '10px',
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        
        var cardElement = elements.create('card', { style: style });

        cardElement.mount('#card-element');

        var form = document.getElementById('caliweb-form-plugin');

        form.addEventListener('submit', function(event) {
            
            event.preventDefault();

            stripe.createToken(cardElement).then(function(result) {

                if (result.error) {

                    console.error(result.error.message);

                } else {

                    var token = result.token.id;

                    fetch('/dashboard/administration/accounts/createPaymentMethod/addCardLogic', {

                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ token: token }),

                    })

                    .then(function(response) {

                        if (response.ok) {

                            window.location.href = '/dashboard/administration/accounts/manageAccount/paymentMethods/?account_number=<?php echo $accountnumber; ?>';

                        } else {

                            throw new Error('Network response was not ok.');

                        }

                    })

                    .catch(function(error) {

                        console.error('Error:', error);
                        window.location.href = '/error/genericSystemError/';

                    });

                }

            });

        });

    </script>

<?php

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>
