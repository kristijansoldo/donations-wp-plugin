<?php

/**
 * Donation form template
 *
 * Available variables:
 *
 * @var     $donation_id                 int
 *
 **/

$predefined_amounts = [
	'10.00',
	'25.00',
	'50.00',
	'100.000',
	'250.000'
];
?>


<?php if ( empty( PayPal_Settings::get(PayPal_Settings::CLIENT_ID) ) || empty( PayPal_Settings::get(PayPal_Settings::CLIENT_SECRET) ) ): ?>
    <div class="donations-plugin-warning">Please go to WP-Admin -> Donations -> Settings and set PayPal client id and
        secret.
    </div>
<?php else: ?>
    <input type="hidden" name="donation_id" id="donation_id" value="<?php echo $donation_id ?>">

    <div>
        <div class="dp-button-group">
			<?php foreach ( $predefined_amounts as $predefined_amount ): ?>
                <button class="js-select-amount" data-value="<?php echo $predefined_amount; ?>"><?php echo $predefined_amount; ?> €</button>
			<?php endforeach; ?>
        </div>
        <div class="dp-input-group">
            <input type="number" step="0.01" min="0" lang="en" value="20.00" name="amount" id="amount"
                   placeholder="Custom amount">
            <i>€</i>
        </div>
    </div>
    <div id="paypal-button-container"></div>
    <p id="result-message"></p>
<?php endif; ?>