<script>

  const stripe = Stripe('pk_test_51QHZSREuWjBqSzAHpbNMV10ScPfauB9HSmpDjDPiWbVtr0gkwSCOQ2nd40UTfsq8AIJOWN9neafYufGoK1KB0g9u0031fKrfMM');

  let elements;
  
  let cardElement;

  function isDarkMode() {

    return document.body.classList.contains('dark-mode');

  }

  function getStripeStyle() {

    const dark = isDarkMode();

    return {
      base: {
        color: dark ? '#ffffff' : '#32325d',
        fontFamily: 'Arial, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: dark ? '#cccccc' : '#aab7c4',
        }
      },
      invalid: {
        color: '#ff4d4f',
        iconColor: '#ff4d4f'
      }
    };

  }

  function createCardElement() {

    if (cardElement) {

      cardElement.unmount();

    }

    elements = stripe.elements();

    cardElement = elements.create('card', { style: getStripeStyle() });

    cardElement.mount('#card-element');

  }

  document.addEventListener('DOMContentLoaded', () => {

    createCardElement();

    const form = document.getElementById('nexure-form-plugin');

    form.addEventListener('submit', function (event) {

      event.preventDefault();

      stripe.createToken(cardElement).then(function (result) {

        if (result.error) {

          console.error(result.error.message);

        } else {

          const token = result.token.id;

          fetch('/Modules/Stripe/Payments/Backend/AddCard/index.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ token }),
          })

          .then(response => {

            if (response.ok) {

              window.location.href = '/Dashboard/Customer/Billing/';

            } else {

              throw new Error('Network error.');

            }

          })

          .catch(err => {

            console.error(err);

            window.location.href = '/ErrorHandling/ErrorPages/GenericError/';

          });

        }

      });

    });

    const observer = new MutationObserver(() => {

      createCardElement();

    });

    observer.observe(document.body, {

      attributes: true,

      attributeFilter: ['class'],
      
    });

  });

</script>