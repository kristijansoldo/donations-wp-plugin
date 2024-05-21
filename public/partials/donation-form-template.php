<?php

/**
 * Donation form template
 *
 * Available variables:
 *
 * @var     $donation_id                 int
 *
 **/


$predefined_amounts_string = General_Settings::get( General_Settings::PREDEFINED_AMOUNTS );

if ( ! empty( $predefined_amounts_string ) ) {
	$predefined_amounts = explode( ',', $predefined_amounts_string );
}
?>

<div class="<?php echo General_Settings::get( General_Settings::CUSTOM_CSS_CLASS ); ?>">
	<?php if ( empty( PayPal_Settings::get( PayPal_Settings::CLIENT_ID ) ) || empty( PayPal_Settings::get( PayPal_Settings::CLIENT_SECRET ) ) ): ?>
        <div class="donations-plugin-warning">Please go to WP-Admin -> Donations -> Settings and set PayPal client id
            and
            secret.
        </div>
	<?php else: ?>
        <input type="hidden" name="donation_id" id="donation_id" value="<?php echo $donation_id ?>">
        <input type="hidden" name="thank_you_message" id="thank_you_message"
               value="<?php echo General_Settings::get( General_Settings::THANK_YOU_MESSAGE ); ?>">

        <div>
			<?php if ( ! empty( $predefined_amounts_string ) ): ?>
                <div class="dp-button-group">
					<?php foreach ( $predefined_amounts as $predefined_amount ): ?>
                        <button class="js-select-amount dp-amount-<?php echo $predefined_amount; ?>"
                                data-value="<?php echo $predefined_amount; ?>"><?php echo $predefined_amount; ?> <?php echo General_Settings::get( General_Settings::CURRENCY ); ?></button>
					<?php endforeach; ?>
                </div>
			<?php endif; ?>
            <div class="dp-input-group">
                <input type="number" step="0.01" min="0" lang="en" value="20.00" name="amount" id="amount"
                       placeholder="Custom amount">
                <i><?php echo General_Settings::get( General_Settings::CURRENCY ); ?></i>
            </div>
        </div>
        <div id="paypal-button-container"></div>
        <div class="donations-plugin-success dp-none" id="result-message"></div>
        <div class="donations-plugin-error dp-none" id="result-error-message"></div>
	<?php endif; ?>
</div>
