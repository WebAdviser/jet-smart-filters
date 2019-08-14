<?php

if ( empty( $args ) ) {
	return;
}

$options   = $args['options'];
$query_var = $args['query_var'];

if ( ! $options ) {
	return;
}

$current = $this->get_current_filter_value( $args );

?>
<div class="jet-select" <?php $this->filter_data_atts( $args, $filter ); ?>>
	<?php include jet_smart_filters()->get_template( 'common/filter-label.php' ); ?>
	<select
		class="jet-select__control"
		name="<?php echo $query_var; ?>"
		<?php $this->control_data_atts( $args ); ?>
	><?php

	foreach ( $options as $value => $label ) {

		$selected = '';

		if ( $current ) {

			if ( is_array( $current ) && in_array( $value, $current ) ) {
				$selected = ' selected';
			}

			if ( ! is_array( $current ) && $value == $current ) {
				$selected = ' selected';
			}

		}

		?>
		<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $label; ?></option>
		<?php

	}

	?></select>
</div>
