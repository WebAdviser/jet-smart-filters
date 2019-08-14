<?php
/**
 * Color/Image filter class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Color_Image_Filter' ) ) {

	/**
	 * Define Jet_Smart_Filters_Color_Image_Filter class
	 */
	class Jet_Smart_Filters_Color_Image_Filter extends Jet_Smart_Filters_Filter_Base {

		/**
		 * Get provider name
		 *
		 * @return string
		 */
		public function get_name() {
			return __( 'Visual', 'jet-smart-filters' );
		}

		/**
		 * Get provider ID
		 *
		 * @return string
		 */
		public function get_id() {
			return 'color-image';
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
		 * @param  string $input     Filter value to format.
		 * @param  int    $filter_id Filter ID.
		 * @return string
		 */
		public function get_verbosed_val( $input, $filter_id ) {

			if ( 'false' === $input ) {
				return;
			}

			$args = $this->prepare_args( array(
				'filter_id'      => $filter_id,
				'ignore_parents' => true,
			) );

			if ( empty( $args['options'] ) ) {
				return;
			}

			$options   = $args['options'];
			$result    = '';
			$delimiter = '';

			if ( ! is_array( $input ) ) {
				return isset( $options[ $input ] ) ? $options[ $input ] : false;
			}

			foreach ( $input as $value ) {

				if ( isset( $options[ $value ] ) ) {
					$result    .= $delimiter . $options[ $value ]['label'];
					$delimiter = ', ';
				}

			}

			return $result;

		}

		public function prepare_options( $options, $source ) {

			$_options = array();

			foreach ( $options as $key => $option ) {

				if ( 'taxonomies' === $source || 'posts' === $source ) {
					$value = $option['selected_value'];
				} else {
					$value = $option['value'];
				}

				$_options[ $value ] = array(
					'image' => $option['source_image'],
					'color' => $option['source_color'],
					'label' => $option['label'],
				);

			}

			return $_options;

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

			$source             = get_post_meta( $filter_id, '_data_source', true );
			$is_custom_checkbox = get_post_meta( $filter_id, '_is_custom_checkbox', true );
			$type               = get_post_meta( $filter_id, '_color_image_type', true );
			$behavior           = get_post_meta( $filter_id, '_color_image_behavior', true );
			$options            = get_post_meta( $filter_id, '_source_color_image_input', true );
			$filter_label       = get_post_meta( $filter_id, '_filter_label', true );

			$options = $this->prepare_options( $options, $source );

			switch ( $source ) {
				case 'taxonomies':
					$tax        = get_post_meta( $filter_id, '_source_taxonomy', true );

					$only_child = get_post_meta( $filter_id, '_only_child', true );
					$only_child = filter_var( $only_child, FILTER_VALIDATE_BOOLEAN );

					$current_options = jet_smart_filters()->data->get_terms_for_options( $tax, $only_child );
					$options = array_intersect_key( $options, $current_options );

					$query_type = 'tax_query';
					$query_var  = $tax;
					break;

				case 'posts':
					$query_type = 'meta_query';
					$query_var  = get_post_meta( $filter_id, '_query_var', true );
					break;

				case 'custom_fields':
					$custom_field    = get_post_meta( $filter_id, '_source_custom_field', true );
					$current_options = get_post_meta( get_the_ID(), $custom_field, true );
					$current_options = jet_smart_filters()->data->maybe_parse_repeater_options( $current_options );
					$query_type      = 'meta_query';
					$query_var       = get_post_meta( $filter_id, '_query_var', true );

					$options = array_intersect_key( $options, $current_options );
					break;

				case 'manual_input':
					$query_type = 'meta_query';
					$query_var  = get_post_meta( $filter_id, '_query_var', true );
					break;
			}

			return array(
				'options'          => $options,
				'query_type'       => $query_type,
				'query_var'        => $query_var,
				'query_var_suffix' => ( 'true' === $is_custom_checkbox ) ? 'is_custom_checkbox' : false,
				'content_provider' => $content_provider,
				'apply_type'       => $apply_type,
				'filter_id'        => $filter_id,
				'filter_label'     => $filter_label,
				'type'             => $type,
				'behavior'         => $behavior,
			);

		}

	}

}
