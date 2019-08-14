<?php
/**
 * Search filter class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Search_Filter' ) ) {

	/**
	 * Define Jet_Smart_Filters_Search_Filter class
	 */
	class Jet_Smart_Filters_Search_Filter extends Jet_Smart_Filters_Filter_Base {

		/**
		 * Get provider name
		 *
		 * @return string
		 */
		public function get_name() {
			return __( 'Search', 'jet-smart-filters' );
		}

		/**
		 * Get provider ID
		 *
		 * @return string
		 */
		public function get_id() {
			return 'search';
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

			return esc_attr( $input );
		}

		/**
		 * Prepare filter template argumnets
		 *
		 * @param  [type] $args [description]
		 *
		 * @return [type]       [description]
		 */
		public function prepare_args( $args ) {

			$filter_id            = $args['filter_id'];
			$content_provider     = isset( $args['content_provider'] ) ? $args['content_provider'] : false;
			$apply_type           = isset( $args['apply_type'] ) ? $args['apply_type'] : false;
			$button_text          = isset( $args['button_text'] ) ? $args['button_text'] : false;
			$button_icon          = isset( $args['button_icon'] ) ? $args['button_icon'] : false;
			$button_icon_position = isset( $args['button_icon_position'] ) ? $args['button_icon_position'] : 'left';

			if ( ! $filter_id ) {
				return false;
			}

			$placeholder  = get_post_meta( $filter_id, '_s_placeholder', true );
			$search_by    = get_post_meta( $filter_id, '_s_by', true );
			$filter_label = get_post_meta( $filter_id, '_filter_label', true );

			if ( ! $search_by ) {
				$search_by = 'default';
			}

			if ( 'default' === $search_by ) {
				$query_type = '_s';
				$query_var  = 'query';
			} else {
				$query_type = 'meta_query';
				$query_var  = get_post_meta( $filter_id, '_query_var', true );
			}

			return array(
				'options'              => false,
				'query_type'           => $query_type,
				'query_var'            => $query_var,
				'query_var_suffix'     => 'search',
				'placeholder'          => $placeholder,
				'content_provider'     => $content_provider,
				'apply_type'           => $apply_type,
				'filter_id'            => $filter_id,
				'button_text'          => $button_text,
				'button_icon'          => $button_icon,
				'button_icon_position' => $button_icon_position,
				'filter_label'         => $filter_label,
			);

		}

	}

}
