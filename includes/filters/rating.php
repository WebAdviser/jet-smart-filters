<?php
/**
 * Rating filter class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Rating_Filter' ) ) {

	/**
	 * Define Jet_Smart_Filters_Rating_Filter class
	 */
	class Jet_Smart_Filters_Rating_Filter extends Jet_Smart_Filters_Filter_Base {

		/**
		 * Get provider name
		 *
		 * @return string
		 */
		public function get_name() {
			return __( 'Rating', 'jet-smart-filters' );
		}

		/**
		 * Get provider ID
		 *
		 * @return string
		 */
		public function get_id() {
			return 'rating';
		}

		/**
		 * Get provider wrapper selector
		 *
		 * @return string
		 */
		public function get_scripts() {
			return false;
		}

		/**
		 * Return filter value in human-readable format
		 *
		 * @param  string $input Filter value to format.
		 * @param  int $filter_id Filter ID.
		 *
		 * @return string
		 */
		public function get_verbosed_val( $input, $filter_id ) {

			if ( 'false' === $input ) {
				return;
			}

			$args = $this->prepare_args( array(
				'filter_id' => $filter_id
			) );

			if ( empty( $args['options'] ) ) {
				return;
			}

			$options = $args['options'];

			if ( ! is_string( $input ) ) {
				return;
			}

			$suffix = count( $options );

			return isset( $input ) ? esc_attr( $input ) . '/' . esc_attr( $suffix ) : false;

		}

		/**
		 * Prepare filter template argumnets
		 *
		 * @param  [type] $args [description]
		 *
		 * @return [type]       [description]
		 */
		public function prepare_args( $args ) {

			$filter_id        = $args['filter_id'];
			$widget_id        = $args['__widget_id'];
			$content_provider = isset( $args['content_provider'] ) ? $args['content_provider'] : false;
			$button_text      = isset( $args['button_text'] ) ? $args['button_text'] : false;
			$rating_icon      = isset( $args['rating_icon'] ) ? $args['rating_icon'] : false;
			$apply_type       = isset( $args['apply_type'] ) ? $args['apply_type'] : false;

			if ( ! $filter_id ) {
				return false;
			}

			$filter_label = get_post_meta( $filter_id, '_filter_label', true );

			$options      = get_post_meta( $filter_id, '_rating_options', true );
			$options      = ! empty( $options ) ? range( 1, intval( $options ) ) : array();
			$query_type   = 'meta_query';
			$query_var    = get_post_meta( $filter_id, '_query_var', true );
			$query_operand = get_post_meta( $filter_id, '_rating_compare_operand', true );

			return array(
				'options'          => $options,
				'query_type'       => $query_type,
				'query_var'        => $query_var,
				'query_var_suffix' => 'rating::' . $query_operand,
				'content_provider' => $content_provider,
				'apply_type'       => $apply_type,
				'filter_id'        => $filter_id,
				'filter_label'     => $filter_label,
				'button_text'      => $button_text,
				'rating_icon'      => $rating_icon,
				'__widget_id'      => $widget_id,
			);

		}

	}

}
