<?php

/**
 * Meta box template.
 *
 * Available variables:
 *
 * @var     $donation                 Donation
 *
 **/
?>

<table class="form-table">
    <tr>
        <th scope="row"><label for="tablecell"><?php _e( 'Target amount', DP_PLUGIN_TEXTOMAIN); ?> <?php echo General_Settings::CURRENCY_SYMBOL[General_Settings::get( General_Settings::CURRENCY )]; ?>:</label>
        </th>
        <td>
            <input name="<?php echo Donation::$post_meta['target_amount']; ?>" type="text" placeholder="<?php _e( 'Target amount', DP_PLUGIN_TEXTOMAIN); ?>"
                   value="<?php echo $donation->target_amount; ?>" class="regular-text">
        </td>
    </tr>
</table>