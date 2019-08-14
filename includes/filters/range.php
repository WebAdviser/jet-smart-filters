<?php
/**
 * Range filter class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Range_Filter' ) ) {

	/**
	 * Define Jet_Smart_Filters_Range_Filter class
	 */
	class Jet_Smart_Filters_Range_Filter extends Jet_Smart_Filters_Filter_Base {

		/**
		 * Get provider name
		 *
		 * @return string
		 */
		public function get_name() {
			return __( 'Range', 'jet-smart-filters' );
		}

		/**
		 * Get provider ID
		 *
		 * @return string
		 */
		public function get_id() {
			return 'range';
		}

		/**
		 * Get provider wrapper selector
		 *
		 * @return string
		 */
		public function get_scripts() {
			return array( 'jquery-ui-slider' );
		}

		/**
		 * Return filter value in human-readable format
		 *
		 * @param  string $input     Filter value to format.
		 * @param  int    $filter_id Filter ID.
		 * @return string
		 */
		public function get_verbosed_val( $input, $filter_id ) {

			if ( is_array( $input ) || 'false' === $input ) {
				return;
			}

			$values = explode( ':', $input );
			$args   = $this->prepare_args( array(
				'filter_id' => $filter_id,
			) );

			$min = isset( $values[0] ) ? absint( $values[0] ) : $args['min'];
			$max = isset( $values[1] ) ? absint( $values[1] ) : $args['max'];

			if ( $min === $args['min'] && $max === $args['max'] ) {
				return;
			}

			$prefix = $args['prefix'];

			return esc_attr( $prefix ) . esc_attr( $min ) . ' — ' . esc_attr( $prefix ) . esc_attr( $max );
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

			if ( ! $filter_id ) {
				return false;
			}

			$query_type   = 'meta_query';
			$query_var    = get_post_meta( $filter_id, '_query_var', true );
			$prefix       = get_post_meta( $filter_id, '_values_prefix', true );
			$suffix       = get_post_meta( $filter_id, '_values_suffix', true );
			$source_cb    = get_post_meta( $filter_id, '_source_callback', true );
			$filter_label = get_post_meta( $filter_id, '_filter_label', true );
			$min          = false;
			$max          = false;
			$step         = get_post_meta( $filter_id, '_source_step', true );
			$format       = array();

			if ( ! $step ) {
				$step = 1;
			}

			$format['thousands_sep'] = get_post_meta( $filter_id, '_values_thousand_sep', true );
			$format['decimal_sep']   = get_post_meta( $filter_id, '_values_decimal_sep', true );
			$format['decimal_num']   = get_post_meta( $filter_id, '_values_decimal_num', true );
			$format['decimal_num']   = absint( $format['decimal_num'] );

			if ( is_callable( $source_cb ) ) {
				$data = call_user_func( $source_cb );
				$min  = isset( $data['min'] ) ? $data['min'] : false;
				$max  = isset( $data['max'] ) ? $data['max'] : false;
			}

			if ( ! $min ) {
				$min = get_post_meta( $filter_id, '_source_min', true );
			}

			if ( ! $max ) {
				$max = get_post_meta( $filter_id, '_source_max', true );
			}

			return array(
				'options'          => false,
				'min'              => absint( $min ),
				'max'              => absint( $max ),
				'step'             => $step,
				'format'           => $format,
				'query_type'       => $query_type,
				'query_var'        => $query_var,
				'query_var_suffix' => 'range',
				'content_provider' => $content_provider,
				'apply_type'       => $apply_type,
				'prefix'           => jet_smart_filters_macros( $prefix ),
				'suffix'           => jet_smart_filters_macros( $suffix ),
				'filter_id'        => $filter_id,
				'filter_label'     => $filter_label,
			);

		}

	}

}
