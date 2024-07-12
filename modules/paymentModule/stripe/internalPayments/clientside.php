<script>
    var stripe = Stripe('<?php echo $apikeypublic; ?>');

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

    var form = document.getElementById('caliweb-ix-onboarding-form-plugin');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(cardElement).then(function(result) {
            if (result.error) {
                console.error(result.error.message);
            } else {
                var token = result.token.id;

                fetch('/onboarding/requiredLogic/index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ token: token }),
                })
                .then(function(response) {
                    window.location.href = '/onboarding/completeOnboarding';
                    return response.json();
                })
                .then(function(data) {
                    window.location.href = '/onboarding/completeOnboarding/';
                    console.log(data);
                })
                .catch(function(error) {
                    window.location.href = '/onboarding/completeOnboarding';
                    console.error('Error:', error);
                });
            }
        });
    });
</script>
