<?php
/**
 * Class: Jet_Smart_Filters_Provider_Jet_Woo_List
 * Name: JetWooBuilder Products List
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Provider_Jet_Woo_List' ) ) {

	/**
	 * Define Jet_Smart_Filters_Provider_Jet_Woo_List class
	 */
	class Jet_Smart_Filters_Provider_Jet_Woo_List extends Jet_Smart_Filters_Provider_Base {

		/**
		 * Watch for default query
		 */
		public function __construct() {

			if ( ! jet_smart_filters()->query->is_ajax_filter() ) {

				add_filter( 'shortcode_atts_jet-woo-products-list', array( $this, 'store_default_atts' ), 0, 2 );

				// Add provider and query ID to query
				add_filter(
					'jet-woo-builder/shortcodes/jet-woo-products-list/query-args',
					array( $this, 'filters_trigger' ),
					10, 2
				);

				add_action(
					'elementor/widget/before_render_content',
					array( $this, 'store_default_settings' ),
					0
				);

			}

		}

		/**
		 * Returns widget name
		 * @return [type] [description]
		 */
		public function widget_name() {
			return 'jet-woo-products-list';
		}

		/**
		 * Store default query args
		 *
		 * @param  array  $args Query arguments.
		 * @return array
		 */
		public function store_default_atts( $atts = array() ) {

			if ( empty( $atts['_element_id'] ) ) {
				$query_id = 'default';
			} else {
				$query_id = $atts['_element_id'];
			}

			jet_smart_filters()->providers->add_provider_settings( $this->get_id(), $atts, $query_id );
			return $atts;
		}

		/**
		 * Save default widget settings
		 *
		 * @param  [type] $widget [description]
		 * @return [type]         [description]
		 */
		public function store_default_settings( $widget ) {

			if ( $this->widget_name() !== $widget->get_name() ) {
				return;
			}

			$settings         = $widget->get_settings();
			$store_settings   = $this->settings_to_store();
			$default_settings = array();

			if ( ! empty( $settings['_element_id'] ) ) {
				$query_id = $settings['_element_id'];
			} else {
				$query_id = 'default';
			}

			foreach ( $store_settings as $key ) {
				$default_settings[ $key ] = isset( $settings[ $key ] ) ? $settings[ $key ] : '';
			}

			$default_settings['_el_widget_id'] = $widget->get_id();

			// Compatibility with compare and wishlist plugin.
			$default_settings['_widget_id'] = $widget->get_id();

			jet_smart_filters()->providers->store_provider_settings( $this->get_id(), $default_settings, $query_id );

		}

		/**
		 * Returns settings to store list
		 * @return [type] [description]
		 */
		public function settings_to_store(){
			return array(
				'show_compare',
				'compare_button_order',
				'compare_button_order_tablet',
				'compare_button_order_mobile',
				'compare_button_icon_normal',
				'compare_button_label_normal',
				'compare_button_icon_added',
				'compare_button_label_added',
				'compare_use_button_icon',
				'compare_button_icon_position',
				'show_wishlist',
				'wishlist_button_order',
				'wishlist_button_order_tablet',
				'wishlist_button_order_mobile',
				'wishlist_button_icon_normal',
				'wishlist_button_label_normal',
				'wishlist_button_icon_added',
				'wishlist_button_label_added',
				'wishlist_use_button_icon',
				'wishlist_button_icon_position',
				'show_quickview',
				'quickview_button_order',
				'quickview_button_icon_normal',
				'quickview_button_label_normal',
				'quickview_use_button_icon',
				'quickview_button_icon_position',
				'jet_woo_builder_qv',
				'jet_woo_builder_qv_template',
			);
		}

		/**
		 * Get provider name
		 *
		 * @return string
		 */
		public function get_name() {
			return __( 'JetWooBuilder Products List', 'jet-smart-filters' );
		}

		/**
		 * Get provider ID
		 *
		 * @return string
		 */
		public function get_id() {
			return 'jet-woo-products-list';
		}

		public function filters_trigger( $args = array(), $shortcode ) {

			$query_id = $shortcode->get_attr( '_element_id' );

			if ( ! $query_id ) {
				$query_id = 'default';
			}

			$args['no_found_rows']     = false;
			$args['jet_smart_filters'] = jet_smart_filters()->query->encode_provider_data(
				$this->get_id(),
				$query_id
			);

			return $args;
		}

		/**
		 * Get filtered provider content
		 *
		 * @return string
		 */
		public function ajax_get_content() {

			if ( ! function_exists( 'wc' ) || ! function_exists( 'jet_woo_builder' ) ) {
				return;
			}

			add_filter(
				'jet-woo-builder/shortcodes/jet-woo-products-list/query-args',
				array( $this, 'filters_trigger' ),
				10, 2
			);

			add_filter( 'pre_get_posts', array( $this, 'add_query_args' ), 10 );

			$attributes = jet_smart_filters()->query->get_query_settings();

			$shortcode = jet_woo_builder_shortocdes()->get_shortcode( 'jet-woo-products-list' );

			$shortcode->set_settings( $attributes );

			echo $shortcode->do_shortcode( $attributes );

		}

		/**
		 * Get provider wrapper selector
		 *
		 * @return string
		 */
		public function get_wrapper_selector() {
			return '.elementor-jet-woo-products-list.jet-woo-builder';
		}

		/**
		 * If added unique ID this paramter will determine - search selector inside this ID, or is the same element
		 *
		 * @return bool
		 */
		public function in_depth() {
			return true;
		}

		/**
		 * Pass args from reuest to provider
		 */
		public function apply_filters_in_request() {

			$args = jet_smart_filters()->query->get_query_args();

			if ( ! $args ) {
				return;
			}

			add_filter(
				'jet-woo-builder/shortcodes/jet-woo-products-list/query-args',
				array( $this, 'filters_trigger' ),
				10, 2
			);

			add_filter( 'pre_get_posts', array( $this, 'add_query_args' ), 10 );

		}

		/**
		 * Add custom query arguments
		 *
		 * @param array $args [description]
		 */
		public function add_query_args( $query ) {

			if ( ! $query->get( 'jet_smart_filters' ) ) {
				return;
			}

			if ( $query->get( 'jet_smart_filters' ) !== jet_smart_filters()->render->request_provider( 'raw' ) ) {
				return;
			}

			foreach ( jet_smart_filters()->query->get_query_args() as $query_var => $value ) {
				$query->set( $query_var, $value );
			}

		}
	}

}
