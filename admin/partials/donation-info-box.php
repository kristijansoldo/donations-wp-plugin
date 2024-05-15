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
        <th scope="row"><label for="tablecell"><?php _e( 'Target amount €', 'donations-plugin'); ?>:</label>
        </td>
        <td>
            <input name="<?php echo Donation::$post_meta['target_amount']; ?>" type="text" placeholder="<?php _e( 'Target amount in €', 'donations-plugin'); ?>"
                   value="<?php echo $donation->target_amount; ?>" class="regular-text">
        </td>
    </tr>
</table>