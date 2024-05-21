document.addEventListener("DOMContentLoaded", function () {
  // Fetch amount input
  const amountInput = document.getElementById('amount');

  // Fetch donation id input
  const donationIdInput = document.getElementById('donation_id');
  const thankYouMessageInput = document.getElementById('thank_you_message');

  // Select all buttons with the class 'js-select-amount'
  const buttons = document.querySelectorAll('.js-select-amount');

  // Add click event listener to each button
  buttons.forEach(button => {
    button.addEventListener('click', function() {
      // Trigger your desired function or action here
      amountInput.value = this.getAttribute('data-value');
    });
  });

  window.paypal.Buttons({
    createOrder: async function () {
      const donationId = donationIdInput.value;
      const amount = amountInput.value;
      try {
        const response = await fetch('/wp-json/dp/v1/orders', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            amount: amount,
          }),
        });

        const orderData = await response.json();

        if (orderData.id) {
          return orderData.id;
        } else {
          const errorDetail = orderData?.details?.[0];
          const errorMessage = errorDetail
              ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
              : JSON.stringify(orderData);

          throw new Error(errorMessage);
        }
      } catch (error) {
        console.error(error);
        resultErrorMessage(`Could not initiate PayPal Checkout...<br><br>${error}`);
      }
    },
    onApprove: async function (data, actions) {
      try {
        const response = await fetch(`/wp-json/dp/v1/orders/${data.orderID}/capture`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
        });

        const orderData = await response.json();
        const errorDetail = orderData?.details?.[0];

        if (errorDetail?.issue === 'INSTRUMENT_DECLINED') {
          return actions.restart();
        } else if (errorDetail) {
          throw new Error(`${errorDetail.description} (${orderData.debug_id})`);
        } else if (!orderData.purchase_units) {
          throw new Error(JSON.stringify(orderData));
        } else {
          const transaction =
              orderData?.purchase_units?.[0]?.payments?.captures?.[0] ||
              orderData?.purchase_units?.[0]?.payments?.authorizations?.[0];
          resultMessage(thankYouMessageInput.value);
          console.log(
              'Capture result',
              orderData,
              JSON.stringify(orderData, null, 2),
          );
        }
      } catch (error) {
        console.error(error);
        resultErrorMessage(`Sorry, your transaction could not be processed...<br><br>${error}`);
      }
    },
  }).render('#paypal-button-container');

  function resultMessage(message) {
    const container = document.querySelector('#result-message');
    container.innerHTML = `${message} <span id="dp-countdown">5s</span>`;
    container.classList.remove("dp-none")
    startCountdown(container);
  }

  function resultErrorMessage(message) {
    const container = document.querySelector('#result-error-message');
    container.innerHTML = `${message} <span id="dp-countdown">5s</span>`;
    container.classList.remove("dp-none")
    startCountdown(container);
  }

  function startCountdown(container) {
    let countdownElement = container.querySelector('#dp-countdown');
    let countdown = 5;
    const interval = setInterval(() => {
      countdown--;
      countdownElement.textContent = countdown+'s';
      if (countdown <= 0) {
        clearInterval(interval);
        container.classList.add("dp-none");
      }
    }, 1000);
  }
});
