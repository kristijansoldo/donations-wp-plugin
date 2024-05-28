document.addEventListener("DOMContentLoaded", function () {

    if (!paypal) return;

    if (document.getElementById('paypal-button-containermodal') == null) return;

    // Fetch amount input
    const amountInput = document.getElementById('amountmodal');
    const donationIdInput = document.getElementById('donation_idmodal');
    const thankYouMessageInput = document.getElementById('thank_you_messagemodal');
    const cardNumberEl = document.getElementById('e_card_numbermodal');
    const mmyyEl = document.getElementById('e_mmyymodal');
    const cardHolderInput = document.getElementById('card-holdermodal');
    const submitButton = document.getElementById('submit-buttonmodal');

    // Select all buttons with the class 'js-select-amount'
    const buttons = document.querySelectorAll('.js-select-amountmodal');

    // Add click event listener to each button
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            amountInput.value = this.getAttribute('data-value');
        });
    });

    paypal.Buttons({
        createOrder: async function () {
            if (!validateAmountInput()) {
                resultErrorMessage('Amount is required.');
                throw new Error('Validation failed: Amount is required.');
            }

            const donationId = donationIdInput.value;
            const amount = amountInput.value;
            try {
                const response = await fetch('/wp-json/dp/v1/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({amount: amount}),
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
                    body: JSON.stringify({donationId: donationIdInput.value}),
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
                    console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                }
            } catch (error) {
                console.error(error);
                resultErrorMessage(`Sorry, your transaction could not be processed...<br><br>${error}`);
            }
        },
        onError: function (err) {
            console.error(err);
            resultErrorMessage(`An error occurred during the transaction: ${err.message}`);
        }
    }).render('#paypal-button-containermodal');

    if (paypal.HostedFields.isEligible()) {
        paypal.HostedFields.render({
            createOrder: function () {
                if (!validateAmountInput()) {
                    resultErrorMessage('Amount is required.');
                    throw new Error('Validation failed: Amount is required.');
                }
                return fetch('/wp-json/dp/v1/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        amount: amountInput.value,
                    }),
                }).then(res => res.json()).then(orderData => orderData.id);
            },
            styles: {
                '.valid': {
                    'color': 'green'
                },
                '.invalid': {
                    'color': 'red'
                }
            },
            fields: {
                number: {
                    selector: '#card-numbermodal',
                    placeholder: cardNumberEl.value
                },
                cvv: {
                    selector: '#cvvmodal',
                    placeholder: 'CVV'
                },
                expirationDate: {
                    selector: '#expiration-datemodal',
                    placeholder: mmyyEl.value
                }
            }
        }).then(function (hostedFields) {
            document.querySelector('#submit-buttonmodal').addEventListener('click', function (event) {
                event.preventDefault();
                submitButton.classList.add('dp-loading');
                submitButton.disabled = true;
                hostedFields.submit({
                    cardholderName: cardHolderInput.value
                }).then(function (payload) {
                    fetch(`/wp-json/dp/v1/orders/${payload.orderID}/capture`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({donationId: donationIdInput.value})
                    }).then(res => res.json()).then(orderData => {
                        const transaction = orderData.purchase_units[0].payments.captures[0];
                        resultMessage(thankYouMessageInput.value);
                        console.log('Transaction completed', transaction);
                        submitButton.classList.remove('dp-loading');
                        submitButton.disabled = false;
                    }).catch(err => {
                        console.error(err);
                        resultErrorMessage(`Transaction failed: ${err.message}`);
                        submitButton.classList.remove('dp-loading');
                        submitButton.disabled = false;
                    });
                }).catch(err => {
                    console.error(err);
                    resultErrorMessage(`Error: ${err.message}`);
                    submitButton.classList.remove('dp-loading');
                    submitButton.disabled = false;
                });
            });
        });
    } else {
        document.getElementById('card-numbermodal').parentElement.classList.add('dp-none')
        document.getElementById('cvvmodal').parentElement.classList.add('dp-none')
        document.getElementById('expiration-datemodal').parentElement.classList.add('dp-none')
        cardHolderInput.parentElement.classList.add('dp-none');
        submitButton.classList.add('dp-none');
    }

    function resultMessage(message) {
        const container = document.querySelector('#result-messagemodal');
        container.innerHTML = `${message} <span id="dp-countdownmodal">5s</span>`;
        container.classList.remove("dp-none")
        startCountdown(container);
    }

    function resultErrorMessage(message) {
        const container = document.querySelector('#result-error-messagemodal');
        container.innerHTML = `${message} <span id="dp-countdownmodal">5s</span>`;
        container.classList.remove("dp-none")
        startCountdown(container);
    }

    function startCountdown(container) {
        let countdownElement = container.querySelector('#dp-countdownmodal');
        let countdown = 5;
        const interval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown + 's';
            if (countdown <= 0) {
                clearInterval(interval);
                container.classList.add("dp-none");
            }
        }, 1000);
    }

    function validateAmountInput() {
        if (!amountInput.value) {
            amountInput.classList.add('dp-error');
            return false;
        } else {
            amountInput.classList.remove('dp-error');
            return true;
        }
    }
});


