<?php

if ( empty( $args ) ) {
	return;
}

$options         = $args['options'];
$display_options = $args['display_options'];
$type            = $args['type'];
$filter_type     = ! empty( $args['behavior'] ) ? $args['behavior'] : 'checkbox';
$query_var       = $args['query_var'];
$extra_classes   = '';

if ( ! $options ) {
	return;
}

$current = $this->get_current_filter_value( $args );

?>
<div class="jet-color-image-list" <?php $this->filter_data_atts( $args, $filter ); ?>><?php

	include jet_smart_filters()->get_template( 'common/filter-label.php' );

	echo '<div class="jet-color-image-list-wrapper">';

	foreach ( $options as $value => $option ) {

		$checked = '';

		if ( $current ) {

			if ( is_array( $current ) && in_array( $value, $current ) ) {
				$checked = 'checked';
			}

			if ( ! is_array( $current ) && $value == $current ) {
				$checked = 'checked';
			}

		}

		if( '' !== $value ){
			include jet_smart_filters()->get_template( 'filters/color-image-item.php' );
		}

	}

	echo '</div>';

	?></div>
