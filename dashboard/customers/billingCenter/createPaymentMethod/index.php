<?php

    session_start();

    unset($_SESSION['pagetitle']);
    $pagetitle = $_SESSION['pagetitle'] = "Client";
    $pagesubtitle = $_SESSION['pagesubtitle'] = "Billing Center";
    $pagetype = "Client";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    $_SESSION['stripe_id'] = $currentAccount->stripe_id;

?>

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <div class="display-flex align-center" style="justify-content:space-between">
                        <div>
                            <p class="no-padding" style="font-size:16px;">Overview / Billing Center/ Create Payment Method</p>
                        </div>
                        <div>
                            <a href="/dashboard/customers/billingCenter/" class="caliweb-button secondary">Return to Billing Center</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section caliweb-section customer-dashboard-section">
                <div class="container caliweb-container">
                    <div class="caliweb-one-grid">
                        <div class="accounts-overview">
                            <div class="caliweb-card dashboard-card" style="padding:0; background-color:transparent !important; border:0 !important;">
                                <form method="POST" id="caliweb-form-plugin" action="/dashboard/customers/createPaymentMethod/addCardLogic/index.php">
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
                                    <div class="card-footer" style="margin-top:0%;">
                                        <button class="caliweb-button primary" type="submit" name="submit">Attach Payment Method</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

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

                        fetch('/dashboard/customers/billingCenter/createPaymentMethod/addCardLogic/index.php', {

                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ token: token }),

                        })

                        .then(function(response) {

                            if (response.ok) {

                                window.location.href = '/dashboard/customers/billingCenter';

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

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>