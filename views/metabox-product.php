<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$product_id = $post->ID;

$disabledDays = get_post_meta( $product_id, '_edw_disabled_days', true );
$mode         = get_post_meta( $product_id, '_edw_mode', true );

if ( $disabledDays === '' ) {
	$disabledDays = [];
}
?>
<table class="form-table">
<tr valign="top">
    <th scope="row"><?= __('Overwrite general settings', 'estimated-delivery-for-woocommerce') ?>
    </th>
    <td>
        <label>
        <input type="checkbox" value="1" name="_edw_overwrite" <?= get_post_meta( $product_id, '_edw_overwrite', true ) === '1' ? 'checked="checked"' : '' ?>/></label>
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?= __( 'Days for Delivery', 'estimated-delivery-for-woocommerce' ) ?>
    </th>
    <td>
        <label>
        <input type="number" min="0" max="99999" name="_edw_days" value="<?= get_post_meta( $product_id, '_edw_days', true ) ?>"/></label>
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?= __( 'Max Days for Delivery', 'estimated-delivery-for-woocommerce' ) ?>
    <p class="description"><?= __( 'Set 0 for disable. If this set more than 0 days, it will show a range.', 'estimated-delivery-for-woocommerce' ) ?></p>
    </th>
    <td>
        <label>
        <input type="number" min="0" max="99999" name="_edw_max_days" value="<?= get_post_meta( $product_id, '_edw_max_days', true ) ?>"/></label>
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?= __( 'Days for Delivery out of stock', 'estimated-delivery-for-woocommerce' ) ?>
    </th>
    <td>
        <label>
        <input type="number" min="0" max="99999" name="_edw_days_outstock" value="<?= get_post_meta( $product_id, '_edw_days_outstock', true ) ?>"/></label>
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?= __( 'Max Days for Delivery out of stock', 'estimated-delivery-for-woocommerce' ) ?>
    <p class="description"><?= __( 'Set 0 for disable. If this set more than 0 days, it will show a range.', 'estimated-delivery-for-woocommerce' ) ?></p>
    </th>
    <td>
        <label>
        <input type="number" min="0" max="99999" name="_edw_max_days_outstock" value="<?= get_post_meta( $product_id, '_edw_max_days_outstock', true ) ?>"/></label>
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?= __( 'Days for Delivery Backorders', 'estimated-delivery-for-woocommerce' ) ?>
    </th>
    <td>
        <label>
        <input type="number" min="0" max="99999" name="_edw_days_backorders" value="<?= get_post_meta( $product_id, '_edw_days_backorders', true ) ?>"/></label>
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?= __( 'Max Days for Delivery Backorders', 'estimated-delivery-for-woocommerce' ) ?>
    <p class="description"><?= __( 'Set 0 for disable. If this set more than 0 days, it will show a range.', 'estimated-delivery-for-woocommerce' ) ?></p>
    </th>
    <td>
        <label>
        <input type="number" min="0" max="99999" name="_edw_max_days_backorders" value="<?= get_post_meta( $product_id, '_edw_max_days_backorders', true ) ?>"/></label>
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?= __( 'Estimated or Guaranteed', 'estimated-delivery-for-woocommerce' ) ?>
        <p class="description"><?= __( 'The message will change.', 'estimated-delivery-for-woocommerce' ) ?></p>
    </th>
    <td>
        <label>
        <select name="_edw_mode">
            <option value="1" <?php selected( '1', $mode ) ?>><?= __( 'Estimated', 'estimated-delivery-for-woocommerce' ) ?></option>
            <option value="2" <?php selected( '2', $mode ) ?>><?= __( 'Guaranteed', 'estimated-delivery-for-woocommerce' ) ?></option>
            <option value="3" <?php selected( '3', $mode ) ?>><?= __( 'Custom string', 'estimated-delivery-for-woocommerce' ) ?></option>
        </select>
        </label>
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?= __( 'Custom mode string (if selected above)', 'estimated-delivery-for-woocommerce' ) ?>
        <p class="description"><?= __( 'Define your own mode here', 'estimated-delivery-for-woocommerce' ) ?></p>
    </th>
    <td>
        <label>
        <input type="text" name="_edw_mode_custom" value="<?= get_post_meta( $product_id, '_edw_mode_custom', true ) ?>"/></label>
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?= __( 'Days disabled', 'estimated-delivery-for-woocommerce' ) ?>
        <p class="description"><?= __( 'Select the days that NO shipments are made.', 'estimated-delivery-for-woocommerce' ) ?></p>
    </th>
    <td>
        <label>
            <input type="checkbox" name="_edw_disabled_days[]" value="Mon" <?= in_array( 'Mon', $disabledDays ) ? 'checked="checked"' : '' ?>/>
            <?= __( 'Monday', 'estimated-delivery-for-woocommerce' ) ?>
        </label>
        <br>
        <label>
            <input type="checkbox" name="_edw_disabled_days[]" value="Tue" <?= in_array( 'Tue', $disabledDays ) ? 'checked="checked"' : '' ?>/>
            <?= __( 'Tuesday', 'estimated-delivery-for-woocommerce' ) ?>
        </label>
        <br>
        <label>
            <input type="checkbox" name="_edw_disabled_days[]" value="Wed" <?= in_array( 'Wed', $disabledDays ) ? 'checked="checked"' : '' ?>/>
            <?= __( 'Wednesday', 'estimated-delivery-for-woocommerce' ) ?>
        </label>
        <br>
        <label>
            <input type="checkbox" name="_edw_disabled_days[]" value="Thu" <?= in_array( 'Thu', $disabledDays ) ? 'checked="checked"' : '' ?>/>
            <?= __( 'Thursday', 'estimated-delivery-for-woocommerce' ) ?>
        </label>
        <br>
        <label>
            <input type="checkbox" name="_edw_disabled_days[]" value="Fri" <?= in_array( 'Fri', $disabledDays ) ? 'checked="checked"' : '' ?> />
            <?= __( 'Friday', 'estimated-delivery-for-woocommerce' ) ?>
        </label>
        <br>
        <label>
            <input type="checkbox" name="_edw_disabled_days[]" value="Sat" <?= in_array( 'Sat', $disabledDays ) ? 'checked="checked"' : '' ?>/>
            <?= __( 'Saturday', 'estimated-delivery-for-woocommerce') ?>
        </label>
        <br>
        <label>
            <input type="checkbox" name="_edw_disabled_days[]" value="Sun" <?= in_array( 'Sun', $disabledDays ) ? 'checked="checked"' : '' ?>/>
            <?= __( 'Sunday', 'estimated-delivery-for-woocommerce') ?>
        </label>
    </td>
</tr>
</table>
