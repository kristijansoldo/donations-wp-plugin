<?php

/**
 * Progress bar template
 *
 * Available variables:
 *
 * @var     $donation_payments        Donation_Payment[]
 * @var     $donation                 Donation
 *
 **/

// Default total amount
$total_amount = 0.0;

// Gets target amount
$target_amount = $donation->target_amount;

// Calculate all amounts
foreach ($donation_payments as $donation_payment) {
	$total_amount += $donation_payment->amount;
}

// Gets percentage
$percentage = $total_amount/$target_amount*100;

$currency = General_Settings::CURRENCY_SYMBOL[General_Settings::get( General_Settings::CURRENCY )];


?>

<div class="dp-funded-info">
    <h5><?php echo $total_amount.$currency; ?> <?php _e('of', DP_PLUGIN_TEXTOMAIN); ?> <?php echo $target_amount.$currency; ?> <?php _e('funded.', DP_PLUGIN_TEXTOMAIN); ?></h5>
    <div class="dp-progress-bar">
        <div class="dp-bar" style="width: <?php echo $percentage; ?>%"><?php echo $percentage; ?>%</div>
    </div>
</div>
