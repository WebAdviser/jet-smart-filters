<?php
/**
 * Data class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Render' ) ) {

	/**
	 * Define Jet_Smart_Filters_Render class
	 */
	class Jet_Smart_Filters_Render {

		private $_rendered_providers = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'maybe_apply_filters' ) );
			add_action( 'jet-smart-filters/filters/localized-data', array( $this, 'hook_refresh_controls' ) );

			add_action( 'wp_ajax_jet_smart_filters', array( $this, 'ajax_apply_filters' ) );
			add_action( 'wp_ajax_nopriv_jet_smart_filters', array( $this, 'ajax_apply_filters' ) );

			add_action( 'wp_ajax_jet_smart_filters_refresh_controls', array( $this, 'ajax_refresh_controls' ) );
			add_action( 'wp_ajax_nopriv_jet_smart_filters_refresh_controls', array( $this, 'ajax_refresh_controls' ) );

			add_action( 'wp_ajax_jet_smart_filters_refresh_controls_reload', array( $this, 'ajax_refresh_controls' ) );
			add_action( 'wp_ajax_nopriv_jet_smart_filters_refresh_controls_reload', array( $this, 'ajax_refresh_controls' ) );

		}

		/**
		 * Returns requested provider ID
		 *
		 * @return string
		 */
		public function request_provider( $return = null ) {
			return jet_smart_filters()->query->get_current_provider( $return );
		}

		/**
		 * Maybe apply filters in request.
		 */
		public function maybe_apply_filters() {

			if ( empty( $_REQUEST['jet-smart-filters'] ) ) {
				return;
			}

			$provider_id = $this->request_provider( 'provider' );
			$provider    = jet_smart_filters()->providers->get_providers( $provider_id );

			if ( ! $provider ) {
				return;
			}

			if ( is_callable( array( $provider, 'apply_filters_in_request' ) ) ) {
				jet_smart_filters()->query->get_query_from_request();
				$provider->apply_filters_in_request();
			}

		}

		/**
		 * Add refresh active filters and pagination triggers
		 *
		 * @param  [type] $data [description]
		 * @return [type]       [description]
		 */
		public function hook_refresh_controls( $data ) {

			$providers = $this->_rendered_providers;

			if ( empty( $providers ) ) {
				return $data;
			}

			$data['refresh_controls'] = true;
			$data['props']            = jet_smart_filters()->query->get_query_props();
			$data['refresh_provider'] = $providers;

			return $data;
		}

		/**
		 * Apply filters in AJAX request
		 *
		 * @return [type] [description]
		 */
		public function ajax_apply_filters() {

			$provider_id = $this->request_provider( 'provider' );
			$query_id    = $this->request_provider( 'query_id' );
			$provider    = jet_smart_filters()->providers->get_providers( $provider_id );

			if ( ! $provider ) {
				return;
			}

			jet_smart_filters()->query->get_query_from_request();

			if ( ! empty( $_REQUEST['props'] ) ) {

				jet_smart_filters()->query->set_props(
					$provider_id,
					$_REQUEST['props'],
					$query_id
				);

			}

			wp_send_json( array(
				'content'       => $this->render_content( $provider ),
				'activeFilters' => $this->render_active_filters(),
				'pagination'    => $this->render_pagination(),
			) );

		}

		/**
		 * Refresh controls with ajax
		 *
		 * @return void
		 */
		public function ajax_refresh_controls() {

			$data        = $this->request_provider();
			$provider_id = $data['provider'];
			$query_id    = $data['query_id'];
			$apply_type  = 'ajax';
			$provider    = jet_smart_filters()->providers->get_providers( $provider_id );

			if ( ! $provider ) {
				return;
			}

			if ( ! empty( $_REQUEST['props'] ) ) {

				jet_smart_filters()->query->set_props(
					$provider_id,
					$_REQUEST['props'],
					$query_id
				);

			}

			jet_smart_filters()->query->get_query_from_request();

			$controls = $this->get_controls_from_request();

			if ( 'wp_ajax_jet_smart_filters_refresh_controls_reload' === current_action() ){
				$apply_type = 'reload';
			}

			wp_send_json( array(
				'activeFilters' => $this->render_active_filters(),
				'pagination'    => $this->render_pagination( $provider_id, $apply_type, $query_id, $controls ),
			) );

		}

		/**
		 * Render content
		 *
		 * @return string
		 */
		public function render_content( $provider ) {

			ob_start();

			if ( is_callable( array( $provider, 'ajax_get_content' ) ) ) {
				$provider->ajax_get_content();
			} else {
				_e( 'Incorrect input data', 'jet-smart-filters' );
			}

			return ob_get_clean();

		}

		/**
		 * Render content
		 *
		 * @return string
		 */
		public function render_active_filters() {

			$data        = $this->request_provider();
			$provider_id = $data['provider'];
			$query_id    = $data['query_id'];

			$this->set_rendered( $provider_id, $query_id );
			ob_start();
			jet_smart_filters()->filter_types->get_active_filters_string();
			return ob_get_clean();

		}

		/**
		 * Get info about pagination controls from request
		 *
		 * @return array|bool
		 */
		public function get_controls_from_request() {
			return isset( $_REQUEST['controls'] ) && is_array( $_REQUEST['controls'] ) ? $_REQUEST['controls'] : false;
		}

		/**
		 * Render pagination
		 *
		 * @return string
		 */
		public function render_pagination( $provider = null, $apply_type = 'ajax', $query_id = 'default', $controls = false ) {

			$this->set_rendered( $provider, $query_id );

			if ( $provider ) {
				$props = jet_smart_filters()->query->get_query_props( $provider, $query_id );
			} else {
				$data     = jet_smart_filters()->query->get_current_provider();
				$provider = $data['provider'];
				$query_id = $data['query_id'];
				$props    = jet_smart_filters()->query->get_current_query_props();
			}

			if ( ! $controls ) {
				$controls = $this->get_controls_from_request();
			}

			if ( ! empty( $_REQUEST['paged'] ) && 'false' !== $_REQUEST['paged'] ) {
				$props['page'] = $_REQUEST['paged'];
			} else {
				if ( isset( $_REQUEST['props']['page'] ) ) {
					$props['page'] = $_REQUEST['props']['page'];
				}
			}

			ob_start();

			include jet_smart_filters()->get_template( 'common/pagination.php' );

			return ob_get_clean();

		}

		/**
		 * Store provider as rendered
		 *
		 * @param [type] $provider [description
		 */
		public function set_rendered( $provider, $query_id = 'default' ) {

			if ( ! $provider ) {
				return;
			}

			if ( ! in_array( $provider, $this->_rendered_providers ) ) {
				$this->_rendered_providers[ $provider ] = array();
			}

			if ( ! in_array( $provider, $this->_rendered_providers[ $provider ] ) ) {
				$this->_rendered_providers[ $provider ][] = $query_id;
			}

		}

		/**
		 * Pager data attributes
		 *
		 * @param  array  $atts [description]
		 * @return [type]       [description]
		 */
		public function pager_data_atts( $atts = array() ) {

			$data_atts = array(
				'data-apply-provider' => isset( $atts[0] ) ? $atts[0] : '',
				'data-apply-type'     => isset( $atts[1] ) ? $atts[1] : 'ajax',
				'data-page'           => isset( $atts[2] ) ? $atts[2] : 1,
				'data-query-id'       => isset( $atts[3] ) ? $atts[3] : 'default',
			);

			foreach ( $data_atts as $key => $value ) {
				printf( ' %1$s="%2$s"', $key, $value );
			}

		}

	}

}
