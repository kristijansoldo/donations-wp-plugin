<?php

/**
 * Meta box template.
 *
 * Available variables:
 *
 * @var     $donation_payment                 Donation_Payment
 *
 **/

// Initialize donation service
$donation_service = new Donation_Service();

// Get all donations
$donations = $donation_service->get_all();

?>

<table class="form-table">
    <tr>
        <th scope="row"><label
                    for="tablecell"><?php _e( 'Amount', DP_PLUGIN_TEXTOMAIN ); ?> <?php echo General_Settings::CURRENCY_SYMBOL[ General_Settings::get( General_Settings::CURRENCY ) ]; ?>
                :</label>
        </th>
        <td>
            <input name="<?php echo Donation_Payment::$post_meta['amount']; ?>" type="text"
                   placeholder="<?php _e( 'Paid amount', DP_PLUGIN_TEXTOMAIN ); ?>"
                   value="<?php echo $donation_payment->amount; ?>" class="regular-text">
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="tablecell"><?php _e( 'Donation', 'donations-plugin' ); ?>:</label>
        </th>
        <td>
            <select name="<?php echo Donation_Payment::$post_meta['donation_id']; ?>"
                    id="<?php echo Donation_Payment::$post_meta['donation_id']; ?>">
		        <?php foreach ( $donations as $donation ): ?>
                    <option <?php selected( $donation->id, $donation_payment->donation_id); ?> value="<?php echo $donation->id ?>"><?php echo $donation->title; ?></option>
		        <?php endforeach; ?>
            </select>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="tablecell"><?php _e( 'PayPal Order id', 'donations-plugin' ); ?>:</label>
        </th>
        <td>
            <input name="<?php echo Donation_Payment::$post_meta['order_id']; ?>" type="text"
                   value="<?php echo $donation_payment->order_id; ?>" class="regular-text">
        </td>
    </tr>
</table>