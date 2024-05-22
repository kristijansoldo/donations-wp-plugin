<?php

/**
 * Donation form template
 *
 * Available variables:
 *
 * @var     $donation_id                 int
 *
 **/

// Gets perefined amounts
$predefined_amounts_string = General_Settings::get( General_Settings::PREDEFINED_AMOUNTS );

// Initialize paypal controller
$paypal_controller = new PayPal_Controller();

// Gets client token
$client_token = $paypal_controller->generate_client_token();

// Get client id and currency
$client_id = PayPal_Settings::get(PayPal_Settings::CLIENT_ID);
$currency = General_Settings::get( General_Settings::CURRENCY );

if ( ! empty( $predefined_amounts_string ) ) {
	$predefined_amounts = explode( ',', $predefined_amounts_string );
}
?>

<div class="<?php echo General_Settings::get( General_Settings::CUSTOM_CSS_CLASS ); ?>">
	<?php if ( empty( PayPal_Settings::get( PayPal_Settings::CLIENT_ID ) ) || empty( PayPal_Settings::get( PayPal_Settings::CLIENT_SECRET ) ) ): ?>
        <div class="donations-plugin-warning">Please go to WP-Admin -> Donations -> Settings and set PayPal client id and secret.</div>
	<?php else: ?>
        <input type="hidden" name="donation_id" id="donation_id" value="<?php echo $donation_id ?>">
        <input type="hidden" name="thank_you_message" id="thank_you_message" value="<?php echo General_Settings::get( General_Settings::THANK_YOU_MESSAGE ); ?>">

        <input type="hidden" name="e_card_number" id="e_card_number" value="<?php _e('Card number', DP_PLUGIN_TEXTOMAIN) ?>">
        <input type="hidden" name="e_mmyy" id="e_mmyy" value="<?php _e('MM/YY', DP_PLUGIN_TEXTOMAIN) ?>">

        <div>
			<?php if ( ! empty( $predefined_amounts_string ) ): ?>
                <div class="dp-button-group">
					<?php foreach ( $predefined_amounts as $predefined_amount ): ?>
                        <button class="js-select-amount dp-amount-<?php echo $predefined_amount; ?>" data-value="<?php echo $predefined_amount; ?>">
							<?php echo $predefined_amount; ?> <?php echo General_Settings::CURRENCY_SYMBOL[$currency]; ?>
                        </button>
					<?php endforeach; ?>
                </div>
			<?php endif; ?>
            <div class="dp-input-group">
                <input type="number" step="0.01" min="0" lang="en" name="amount" id="amount" placeholder="<?php _e('Custom amount', DP_PLUGIN_TEXTOMAIN) ?>">
                <i><?php echo General_Settings::CURRENCY_SYMBOL[$currency]; ?></i>
            </div>
        </div>

        <div id="card-fields">
            <div class="dp-input-group">
                <input type="text" name="card-holder" id="card-holder" placeholder="<?php _e('Card holder', DP_PLUGIN_TEXTOMAIN) ?>">
            </div>
            <div class="dp-input-group">
                <div id="card-number"></div>
            </div>
            <div class="dp-input-group">
                <div id="cvv"></div>
            </div>
            <div class="dp-input-group">
                <div id="expiration-date"></div>
            </div>
            <button class="db-submit-button" id="submit-button"><?php _e('Submit Payment', DP_PLUGIN_TEXTOMAIN) ?></button>
        </div>

        <div id="paypal-button-container"></div>

        <div class="donations-plugin-success dp-none" id="result-message"></div>
        <div class="donations-plugin-error dp-none" id="result-error-message"></div>
	<?php endif; ?>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=<?php echo $client_id; ?>&components=buttons,hosted-fields&currency=<?php echo $currency; ?>" data-client-token="<?php echo $client_token; ?>" id="paypal-sdk-js" ></script>