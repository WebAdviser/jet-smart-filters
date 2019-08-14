<?php
/**
 * Range filter class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Date_Range_Filter' ) ) {

	/**
	 * Define Jet_Smart_Filters_Date_Range_Filter class
	 */
	class Jet_Smart_Filters_Date_Range_Filter extends Jet_Smart_Filters_Filter_Base {

		/**
		 * Get provider name
		 *
		 * @return string
		 */
		public function get_name() {
			return __( 'Date Range', 'jet-smart-filters' );
		}

		/**
		 * Get provider ID
		 *
		 * @return string
		 */
		public function get_id() {
			return 'date-range';
		}

		/**
		 * Get provider wrapper selector
		 *
		 * @return string
		 */
		public function get_scripts() {
			return array( 'jquery-ui-datepicker' );
		}

		/**
		 * Return filter value in human-readable format
		 *
		 * @param  string $input     Filter value to format.
		 * @param  int    $filter_id Filter ID.
		 * @return string
		 */
		public function get_verbosed_val( $input, $filter_id ) {

			if ( 'false' === $input ) {
				return;
			}

			$values = explode( ':', $input );
			$from   = isset( $values[0] ) ? $values[0] : false;
			$to     = isset( $values[1] ) ? $values[1] : false;

			if ( ! $from && ! $to ) {
				return;
			}

			if ( $from && ! $to ) {
				return _x( 'After', 'After date', 'jet-smart-filters' ) . ' ' . esc_attr( $from );
			}

			if ( ! $from && $to ) {
				return _x( 'Before', 'Before date', 'jet-smart-filters' ) . ' ' . esc_attr( $to );
			}

			return $from . ' — ' . $to;

		}

		/**
		 * Prepare filter template argumnets
		 *
		 * @param  [type] $args [description]
		 * @return [type]       [description]
		 */
		public function prepare_args( $args ) {

			$filter_id        = $args['filter_id'];
			$content_provider = isset( $args['content_provider'] ) ? $args['content_provider'] : false;
			$apply_type       = isset( $args['apply_type'] ) ? $args['apply_type'] : false;
			$button_text      = isset( $args['button_text'] ) ? $args['button_text'] : false;
			$button_icon      = isset( $args['button_icon'] ) ? $args['button_icon'] : false;
			$button_icon_position = isset( $args['button_icon_position'] ) ? $args['button_icon_position'] : 'left';

			if ( ! $filter_id ) {
				return false;
			}

			$query_type   = get_post_meta( $filter_id, '_date_source', true );
			$query_var    = get_post_meta( $filter_id, '_query_var', true );
			$filter_label = get_post_meta( $filter_id, '_filter_label', true );
			$from         = get_post_meta( $filter_id, '_date_from_placeholder', true );
			$to           = get_post_meta( $filter_id, '_date_to_placeholder', true );

			return array(
				'options'              => false,
				'query_type'           => $query_type,
				'query_var'            => $query_var,
				'query_var_suffix'     => ( 'meta_query' === $query_type ) ? 'date_range' : false,
				'content_provider'     => $content_provider,
				'apply_type'           => $apply_type,
				'filter_id'            => $filter_id,
				'button_text'          => $button_text,
				'button_icon'          => $button_icon,
				'button_icon_position' => $button_icon_position,
				'filter_label'         => $filter_label,
				'from_placeholder'     => $from,
				'to_placeholder'       => $to,
			);

		}

	}

}
