<?php
/**
 * Compatibility filters and actions
 */

// WPML and Woo compatibility
add_filter( 'wcml_multi_currency_ajax_actions', 'jet_smart_filters_add_action_to_multi_currency_ajax', 10, 1 );

function jet_smart_filters_add_action_to_multi_currency_ajax( $ajax_actions = array() ) {

	$ajax_actions[] = 'jet_smart_filters';

	return $ajax_actions;
}

add_filter( 'jet-smart-filters/render_filter_template/filter_id', 'jet_smart_filters_modify_filter_id' );

function jet_smart_filters_modify_filter_id( $filter_id ) {

	// WPML String Translation plugin exist check
	if ( defined( 'WPML_ST_VERSION' ) ) {
		return apply_filters( 'wpml_object_id', $filter_id, jet_smart_filters()->post_type->post_type, true );
	}

	return $filter_id;
}
add_filter( 'jet-smart-filters/filters/localized-data', 'jet_smart_filters_datepicker_texts' );

function jet_smart_filters_datepicker_texts( $args ) {

	 $args['datePickerData'] = array(
		'closeText'       => esc_html__( 'Done', 'jet-smart-filters' ),
		'prevText'        => esc_html__( 'Prev', 'jet-smart-filters' ),
		'nextText'        => esc_html__( 'Next', 'jet-smart-filters' ),
		'currentText'     => esc_html__( 'Today', 'jet-smart-filters' ),
		'monthNames'      => array(
			esc_html__( 'January', 'jet-smart-filters' ),
			esc_html__( 'February', 'jet-smart-filters' ),
			esc_html__( 'March', 'jet-smart-filters' ),
			esc_html__( 'April', 'jet-smart-filters' ),
			esc_html__( 'May', 'jet-smart-filters' ),
			esc_html__( 'June', 'jet-smart-filters' ),
			esc_html__( 'July', 'jet-smart-filters' ),
			esc_html__( 'August', 'jet-smart-filters' ),
			esc_html__( 'September', 'jet-smart-filters' ),
			esc_html__( 'October', 'jet-smart-filters' ),
			esc_html__( 'November', 'jet-smart-filters' ),
			esc_html__( 'December', 'jet-smart-filters' ),
		),
		'monthNamesShort' => array(
			esc_html__( 'Jan', 'jet-smart-filters' ),
			esc_html__( 'Feb', 'jet-smart-filters' ),
			esc_html__( 'Mar', 'jet-smart-filters' ),
			esc_html__( 'Apr', 'jet-smart-filters' ),
			esc_html__( 'May', 'jet-smart-filters' ),
			esc_html__( 'Jun', 'jet-smart-filters' ),
			esc_html__( 'Jul', 'jet-smart-filters' ),
			esc_html__( 'Aug', 'jet-smart-filters' ),
			esc_html__( 'Sep', 'jet-smart-filters' ),
			esc_html__( 'Oct', 'jet-smart-filters' ),
			esc_html__( 'Nov', 'jet-smart-filters' ),
			esc_html__( 'Dec', 'jet-smart-filters' ),
		),
		'dayNames'        => array(
			esc_html__( 'Sunday', 'jet-smart-filters' ),
			esc_html__( 'Monday', 'jet-smart-filters' ),
			esc_html__( 'Tuesday', 'jet-smart-filters' ),
			esc_html__( 'Wednesday', 'jet-smart-filters' ),
			esc_html__( 'Thursday', 'jet-smart-filters' ),
			esc_html__( 'Friday', 'jet-smart-filters' ),
			esc_html__( 'Saturday', 'jet-smart-filters' )
		),
		'dayNamesShort'   => array( "Sun",
			esc_html__( 'Mon', 'jet-smart-filters' ),
			esc_html__( 'Tue', 'jet-smart-filters' ),
			esc_html__( 'Wed', 'jet-smart-filters' ),
			esc_html__( 'Thu', 'jet-smart-filters' ),
			esc_html__( 'Fri', 'jet-smart-filters' ),
			esc_html__( 'Sat', 'jet-smart-filters' )
		),
		'dayNamesMin'     => array(
			esc_html__( 'Su', 'jet-smart-filters' ),
			esc_html__( 'Mo', 'jet-smart-filters' ),
			esc_html__( 'Tu', 'jet-smart-filters' ),
			esc_html__( 'We', 'jet-smart-filters' ),
			esc_html__( 'Th', 'jet-smart-filters' ),
			esc_html__( 'Fr', 'jet-smart-filters' ),
			esc_html__( 'Sa', 'jet-smart-filters' ),
		),
		'weekHeader'      => esc_html__( 'Wk', 'jet-smart-filters' ),
	);

	 return $args;
}