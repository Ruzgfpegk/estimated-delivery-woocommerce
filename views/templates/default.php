<?php
if ( ! $this->isGlobal && $this->forcedOverride ) {
	?>
	<input type="hidden" value="1" name="_edw_overwrite"/>
	<?php
}
?>
<table class="form-table">
	<tbody style="vertical-align: top;">
<?php
if( $this->isGlobal ) {
	?>
		<tr>
			<th scope="row"><?= __( 'Position', 'estimated-delivery-for-woocommerce' ) ?></th>
			<td>
				<label>
					<select name="_edw_position">
						<?php
						foreach( EDWCore::$positions as $key => $pos ) {
							echo '<option value="' . $key . '" ' . selected( $key, $currentPosition ) . '>' . $pos . '</option>';
						}
						?>
					</select>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?= __( 'Use AJAX', 'estimated-delivery-for-woocommerce' )?>
				<p class="description"><?= __( 'If your site use cache system, active this option.', 'estimated-delivery-for-woocommerce' ) ?></p>
			</th>
			<td>
				<label>
					<input type="checkbox" value="1" name="_edw_cache" <?= $this->retrieveProperty( '_edw_cache', '0' ) == '1' ? 'checked="checked"' : '' ?>/>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?= __( 'Delivery same day', 'estimated-delivery-for-woocommerce' ) ?>
				<p class="description"><?= __( 'When you set 0 in any option the estimated delivery is disabled, activate this option to allow setting 0 and displaying the estimated date.', 'estimated-delivery-for-woocommerce' ) ?></p>
			</th>
			<td>
				<label>
					<input type="checkbox" value="1" name="_edw_same_day" <?= $this->retrieveProperty( '_edw_same_day', '0' ) == '1' ? 'checked="checked"' : '' ?>/>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?= __( 'Use Relative Dates', 'estimated-delivery-for-woocommerce') ?>
				<p class="description"><?= __( 'Only work with current and next week', 'estimated-delivery-for-woocommerce') ?></p>
			</th>
			<td>
				<label>
					<input type="checkbox" value="1" name="_edw_relative_dates" <?= $this->retrieveProperty( '_edw_relative_dates', '0' ) == '1' ? 'checked="checked"' : '' ?>/>
				</label>
			</td>
		</tr>
<?php
}
?>
<?php
if ( ! $this->isGlobal && ! $this->forcedOverride ) {
?>
		<tr>
			<th scope="row"><?= __('Overwrite general settings', 'estimated-delivery-for-woocommerce') ?>
			</th>
			<td>
				<label>
					<input type="checkbox" value="1" name="_edw_overwrite" <?= $this->retrieveProperty( '_edw_overwrite' ) === '1' ? 'checked="checked"' : '' ?>/>
				</label>
			</td>
		</tr>
<?php
}
?>
		<tr>
			<th scope="row"><?= __( 'Days for Delivery', 'estimated-delivery-for-woocommerce' ) ?>
			</th>
			<td>
				<label>
					<input type="number" min="0" max="99999" name="_edw_days" value="<?= $this->retrieveProperty( '_edw_days', '0' ) ?>"/>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?= __( 'Max Days for Delivery', 'estimated-delivery-for-woocommerce' ) ?>
			<p class="description"><?= __( 'Set 0 for disable. If this set more than 0 days, it will show a range.', 'estimated-delivery-for-woocommerce' ) ?></p>
			</th>
			<td>
				<label>
					<input type="number" min="0" max="99999" name="_edw_max_days" value="<?= $this->retrieveProperty( '_edw_max_days', '0' ) ?>"/>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?= __( 'Days for Delivery out of stock', 'estimated-delivery-for-woocommerce' ) ?>
			</th>
			<td>
				<label>
					<input type="number" min="0" max="99999" name="_edw_days_outstock" value="<?= $this->retrieveProperty( '_edw_days_outstock', '' ) ?>"/>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?= __( 'Max Days for Delivery out of stock', 'estimated-delivery-for-woocommerce' ) ?>
			<p class="description"><?= __( 'Set 0 for disable. If this set more than 0 days, it will show a range.', 'estimated-delivery-for-woocommerce' ) ?></p>
			</th>
			<td>
				<label>
					<input type="number" min="0" max="99999" name="_edw_max_days_outstock" value="<?= $this->retrieveProperty( '_edw_max_days_outstock', '' ) ?>"/>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?= __( 'Days for Delivery Backorders', 'estimated-delivery-for-woocommerce' ) ?>
			</th>
			<td>
				<label>
					<input type="number" min="0" max="99999" name="_edw_days_backorders" value="<?= $this->retrieveProperty( '_edw_days_backorders', '' ) ?>"/>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?= __( 'Max Days for Delivery Backorders', 'estimated-delivery-for-woocommerce' ) ?>
			<p class="description"><?= __( 'Set 0 for disable. If this set more than 0 days, it will show a range.', 'estimated-delivery-for-woocommerce' ) ?></p>
			</th>
			<td>
				<label>
					<input type="number" min="0" max="99999" name="_edw_max_days_backorders" value="<?= $this->retrieveProperty( '_edw_max_days_backorders', '' ) ?>"/>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?= __( 'Estimated or Guaranteed', 'estimated-delivery-for-woocommerce' ) ?>
				<p class="description"><?= __( 'The message will change.', 'estimated-delivery-for-woocommerce' ) ?></p>
			</th>
			<td>
				<label>
					<select name="_edw_mode" style="height: 34px !important;">
						<option value="1" <?php selected( '1', $this->displayMode ) ?>><?= __( 'Estimated', 'estimated-delivery-for-woocommerce' ) ?></option>
						<option value="2" <?php selected( '2', $this->displayMode ) ?>><?= __( 'Guaranteed', 'estimated-delivery-for-woocommerce' ) ?></option>
						<option value="3" <?php selected( '3', $this->displayMode ) ?>><?= __( 'Custom string', 'estimated-delivery-for-woocommerce' ) ?></option>
					</select>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?= __( 'Custom mode string (if selected above)', 'estimated-delivery-for-woocommerce' ) ?>
				<p class="description"><?= __( 'Define your own mode here', 'estimated-delivery-for-woocommerce' ) ?></p>
			</th>
			<td>
				<label>
					<input type="text" name="_edw_mode_custom" value="<?= $this->retrieveProperty( '_edw_mode_custom', '' ) ?>"/>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?= __( 'Days disabled', 'estimated-delivery-for-woocommerce' ) ?>
				<p class="description"><?= __( 'Select the days that NO shipments are made.', 'estimated-delivery-for-woocommerce' ) ?></p>
			</th>
			<td>
				<label>
					<input type="checkbox" name="_edw_disabled_days[]" value="Mon" <?= in_array( 'Mon', $this->disabledDays ) ? 'checked="checked"' : '' ?>/>
					<?= __( 'Monday', 'estimated-delivery-for-woocommerce' ) ?>
				</label>
				<br>
				<label>
					<input type="checkbox" name="_edw_disabled_days[]" value="Tue" <?= in_array( 'Tue', $this->disabledDays ) ? 'checked="checked"' : '' ?>/>
					<?= __( 'Tuesday', 'estimated-delivery-for-woocommerce' ) ?>
				</label>
				<br>
				<label>
					<input type="checkbox" name="_edw_disabled_days[]" value="Wed" <?= in_array( 'Wed', $this->disabledDays ) ? 'checked="checked"' : '' ?>/>
					<?= __( 'Wednesday', 'estimated-delivery-for-woocommerce' ) ?>
				</label>
				<br>
				<label>
					<input type="checkbox" name="_edw_disabled_days[]" value="Thu" <?= in_array( 'Thu', $this->disabledDays ) ? 'checked="checked"' : '' ?>/>
					<?= __( 'Thursday', 'estimated-delivery-for-woocommerce' ) ?>
				</label>
				<br>
				<label>
					<input type="checkbox" name="_edw_disabled_days[]" value="Fri" <?= in_array( 'Fri', $this->disabledDays ) ? 'checked="checked"' : '' ?>/>
					<?= __( 'Friday', 'estimated-delivery-for-woocommerce' ) ?>
				</label>
				<br>
				<label>
					<input type="checkbox" name="_edw_disabled_days[]" value="Sat" <?= in_array( 'Sat', $this->disabledDays ) ? 'checked="checked"' : '' ?>/>
					<?= __( 'Saturday', 'estimated-delivery-for-woocommerce' ) ?>
				</label>
				<br>
				<label>
					<input type="checkbox" name="_edw_disabled_days[]" value="Sun" <?= in_array( 'Sun', $this->disabledDays ) ? 'checked="checked"' : '' ?>/>
					<?= __( 'Sunday', 'estimated-delivery-for-woocommerce' ) ?>
				</label>
			</td>
		</tr>
	</tbody>
</table>
