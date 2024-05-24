<?php

/**
 * Donation form template
 *
 * Available variables:
 *
 * @var     $donation_id               int
 * @var     $id_suffix                 string
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

        <input type="hidden" name="thank_you_message" id="thank_you_message<?php echo $id_suffix; ?>" value="<?php echo General_Settings::get( General_Settings::THANK_YOU_MESSAGE ); ?>">

        <input type="hidden" name="e_card_number" id="e_card_number<?php echo $id_suffix; ?>" value="<?php _e('Card number', DP_PLUGIN_TEXTOMAIN) ?>">
        <input type="hidden" name="e_mmyy" id="e_mmyy<?php echo $id_suffix; ?>" value="<?php _e('MM/YY', DP_PLUGIN_TEXTOMAIN) ?>">

        <div>
			<?php if ( ! empty( $predefined_amounts_string ) ): ?>
                <div class="dp-button-group">
					<?php foreach ( $predefined_amounts as $predefined_amount ): ?>
                        <button class="js-select-amount<?php echo $id_suffix; ?> dp-amount-<?php echo $predefined_amount; ?>" data-value="<?php echo $predefined_amount; ?>">
							<?php echo $predefined_amount; ?> <?php echo General_Settings::CURRENCY_SYMBOL[$currency]; ?>
                        </button>
					<?php endforeach; ?>
                </div>
			<?php endif; ?>
            <div class="dp-input-group">
                <input type="number" step="0.01" min="0" lang="en" name="amount" id="amount<?php echo $id_suffix; ?>" placeholder="<?php _e('Custom amount', DP_PLUGIN_TEXTOMAIN) ?>">
                <i><?php echo General_Settings::CURRENCY_SYMBOL[$currency]; ?></i>
            </div>

            <?php if(isset($donations)): ?>
                <div class="dp-input-group">
                    <select name="<?php echo Donation_Payment::$post_meta['donation_id']; ?>"
                            id="donation_id<?php echo $id_suffix; ?>">
		                <?php foreach ( $donations as $donation ): ?>
                            <option value="<?php echo $donation->id ?>"><?php echo $donation->title; ?></option>
		                <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
        </div>

        <div id="card-fields<?php echo $id_suffix; ?>">
            <div class="dp-input-group">
                <input type="text" name="card-holder" id="card-holder<?php echo $id_suffix; ?>" placeholder="<?php _e('Card holder', DP_PLUGIN_TEXTOMAIN) ?>">
            </div>
            <div class="dp-input-group">
                <div id="card-number<?php echo $id_suffix; ?>"></div>
            </div>
            <div class="dp-input-group">
                <div id="cvv<?php echo $id_suffix; ?>"></div>
            </div>
            <div class="dp-input-group">
                <div id="expiration-date<?php echo $id_suffix; ?>"></div>
            </div>
            <button class="dp-submit-button" id="submit-button<?php echo $id_suffix; ?>"><?php _e('Submit Payment', DP_PLUGIN_TEXTOMAIN) ?></button>
        </div>

        <div id="paypal-button-container<?php echo $id_suffix; ?>"></div>

        <div class="donations-plugin-success dp-none" id="result-message<?php echo $id_suffix; ?>"></div>
        <div class="donations-plugin-error dp-none" id="result-error-message<?php echo $id_suffix; ?>"></div>
	<?php endif; ?>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=<?php echo $client_id; ?>&components=buttons,hosted-fields&currency=<?php echo $currency; ?>" data-client-token="<?php echo $client_token; ?>" id="paypal-sdk-js<?php echo $id_suffix; ?>" ></script>