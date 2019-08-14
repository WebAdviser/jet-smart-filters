<?php
/**
 * Checkbox list item template
 */

$checked_icon = apply_filters( 'jet-smart-filters/templates/radio/checked-icon', 'fa fa-check' );

?>
<div class="jet-radio-list__row<?php echo $extra_classes; ?>">
	<label class="jet-radio-list__item">
		<input
			type="radio"
			class="jet-radio-list__input"
			autocomplete="off"
			name="<?php echo $query_var; ?>"
			value="<?php echo $value; ?>"
		<?php jet_smart_filters()->filter_types->control_data_atts( $args ); ?>
		<?php echo $checked; ?>
		>
		<span class="jet-radio-list__decorator"><i class="jet-radio-list__checked-icon <?php echo $checked_icon ?>"></i></span>
		<span class="jet-radio-list__label"><?php echo $label; ?></span>
	</label>
</div>