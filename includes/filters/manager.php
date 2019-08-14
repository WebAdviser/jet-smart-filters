<?php
/**
 * Filters manager class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Filter_Manager' ) ) {

	/**
	 * Define Jet_Smart_Filters_Filter_Manager class
	 */
	class Jet_Smart_Filters_Filter_Manager {

		private $_filter_types = array();
		private $_active_filters = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			$this->register_filter_types();
			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'filter_scripts' ) );
			add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'filter_styles' ) );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'filter_editor_styles' ) );
			add_action( 'elementor/preview/enqueue_styles', array( $this, 'filter_editor_styles' ) );
		}

		/**
		 * Enqueue filter scripts
		 */
		public function filter_scripts() {

			$dependencies = array( 'jquery' );

			foreach ( $this->get_filter_types() as $filter ) {

				$assets = $filter->get_scripts();

				if ( $assets ) {
					$dependencies = array_merge( $dependencies, $assets );
				}

			}

			wp_enqueue_script(
				'jet-smart-filters',
				jet_smart_filters()->plugin_url( 'assets/js/public.js' ),
				$dependencies,
				jet_smart_filters()->get_version(),
				true
			);

			$localized_data = apply_filters( 'jet-smart-filters/filters/localized-data', array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'selectors' => jet_smart_filters()->data->get_provider_selectors(),
				'queries'   => jet_smart_filters()->query->get_default_queries(),
				'settings'  => jet_smart_filters()->providers->get_provider_settings(),
				'filters'   => $this->get_active_filters(),
			) );

			wp_localize_script( 'jet-smart-filters', 'JetSmartFilterSettings', $localized_data );

		}

		/**
		 * Return active filters array
		 *
		 * @return array
		 */
		public function get_active_filters() {
			return $this->_active_filters;
		}

		/**
		 * Get active filters HTML string
		 *
		 * @return string
		 */
		public function get_active_filters_string() {

			$active_filters = jet_smart_filters()->query->get_active_filters_array();

			if ( ! $active_filters ) {
				return null;
			}

			$this->get_active_filters_string_content( $active_filters, false );

		}

		/**
		 * Renders active filters string from passed array
		 *
		 * @param  array $active_filters [description]
		 * @param  mixed $title [description]
		 *
		 * @return [type]                  [description]
		 */
		public function get_active_filters_string_content( $active_filters = array(), $title = false ) {

			ob_start();

			foreach ( $active_filters as $active_filter ) {
				$filter = $this->get_filter_types( $active_filter['type'] );
				include jet_smart_filters()->get_template( 'common/active-filter.php' );
			}

			$filters = ob_get_clean();

			if ( ! $filters ) {
				return;
			}

			include jet_smart_filters()->get_template( 'common/active-filters-title.php' );

			include jet_smart_filters()->get_template( 'common/active-filters-loop-start.php' );

			echo $filters;

			include jet_smart_filters()->get_template( 'common/active-filters-loop-end.php' );

		}

		/**
		 * Enqueue filter styles
		 */
		public function filter_styles() {

			wp_enqueue_style(
				'jet-smart-filters',
				jet_smart_filters()->plugin_url( 'assets/css/public.css' ),
				array(),
				jet_smart_filters()->get_version()
			);

		}

		/**
		 * Enqueue editor filter styles
		 */
		public function filter_editor_styles() {

			wp_enqueue_style(
				'jet-smart-filters-icons-font',
				jet_smart_filters()->plugin_url( 'assets/css/lib/jet-smart-filters-icons/jet-smart-filters-icons.css' ),
				array(),
				jet_smart_filters()->get_version()
			);

		}

		/**
		 * Register all providers.
		 *
		 * @return void
		 */
		public function register_filter_types() {

			$base_path = jet_smart_filters()->plugin_path( 'includes/filters/' );

			$default_filter_types = array(
				'Jet_Smart_Filters_Checkboxes_Filter'  => $base_path . 'checkboxes.php',
				'Jet_Smart_Filters_Select_Filter'      => $base_path . 'select.php',
				'Jet_Smart_Filters_Range_Filter'       => $base_path . 'range.php',
				'Jet_Smart_Filters_Check_Range_Filter' => $base_path . 'check-range.php',
				'Jet_Smart_Filters_Date_Range_Filter'  => $base_path . 'date-range.php',
				'Jet_Smart_Filters_Radio_Filter'       => $base_path . 'radio.php',
				'Jet_Smart_Filters_Rating_Filter'      => $base_path . 'rating.php',
				'Jet_Smart_Filters_Search_Filter'      => $base_path . 'search.php',
				'Jet_Smart_Filters_Color_Image_Filter' => $base_path . 'color-image.php',
			);

			require $base_path . 'base.php';

			foreach ( $default_filter_types as $filter_class => $filter_file ) {
				$this->register_filter_type( $filter_class, $filter_file );
			}

			/**
			 * Register custom filter types on this hook
			 */
			do_action( 'jet-smart-filters/filter-types/register', $this );

		}

		/**
		 * Register new filter.
		 *
		 * @param  string $filter_class Filter class name.
		 * @param  string $filter_file Path to file with filter class.
		 *
		 * @return void
		 */
		public function register_filter_type( $filter_class, $filter_file ) {

			if ( ! file_exists( $filter_file ) ) {
				return;
			}

			require $filter_file;

			if ( class_exists( $filter_class ) ) {
				$instance                                   = new $filter_class();
				$this->_filter_types[ $instance->get_id() ] = $instance;
			}

		}

		/**
		 * Return all filter types list or specific filter by ID
		 *
		 * @param  string $filter optional, filter ID.
		 *
		 * @return array|filter object|false
		 */
		public function get_filter_types( $filter = null ) {

			if ( $filter ) {
				return isset( $this->_filter_types[ $filter ] ) ? $this->_filter_types[ $filter ] : false;
			}

			return $this->_filter_types;

		}

		/**
		 * Return current filter value from request by filter arguments
		 *
		 * @param  array $args [description]
		 *
		 * @return [type]       [description]
		 */
		public function get_current_filter_value( $args = array() ) {

			$query_var = sprintf( '_%s_%s', $args['query_type'], $args['query_var'] );

			if ( false !== $args['query_var_suffix'] ) {
				$query_var .= '|' . $args['query_var_suffix'];
			}

			return isset( $_REQUEST[ $query_var ] ) ? $_REQUEST[ $query_var ] : false;

		}

		/**
		 * Print required data-attributes for filter container
		 *
		 * @param  array $args All argumnets.
		 * @param  object $filter Filter instance.
		 *
		 * @return void
		 */
		public function filter_data_atts( $args, $filter ) {

			$atts = array(
				'data-query-type'       => $args['query_type'],
				'data-query-var'        => $args['query_var'],
				'data-smart-filter'     => $filter->get_id(),
				'data-filter-id'        => $args['filter_id'],
				'data-content-provider' => $args['content_provider'],
				'data-query-id'         => $args['query_id'],
			);

			$query_id = $args['query_id'];

			if ( ! isset( $this->_active_filters[ $args['content_provider'] ] ) ) {
				$this->_active_filters[ $args['content_provider'] ] = array();
			}

			if ( ! isset( $this->_active_filters[ $args['content_provider'] ][ $query_id ] ) ) {
				$this->_active_filters[ $query_id ] = array();
			}

			$request_key = '_' . $args['query_type'] . '_' . $args['query_var'];

			$this->_active_filters[ $args['content_provider'] ][ $query_id ][ $args['filter_id'] ] = array(
				'filter'    => $filter->get_id(),
				'query_var' => $args['query_var'],
				'value'     => isset( $_REQUEST[ $request_key ] ) ? $_REQUEST[ $request_key ] : false,
			);

			if ( isset( $args['query_var_suffix'] ) ) {
				$atts['data-query-var-suffix'] = $args['query_var_suffix'];
			}

			echo $this->get_atts_string( $atts );

		}

		/**
		 * Print required data-attributes for filter control
		 *
		 * @param  array $args All argumnets.
		 * @param  object $filter Filter instance.
		 *
		 * @return void
		 */
		public function control_data_atts( $args ) {

			$atts = array(
				'data-apply-provider' => $args['content_provider'],
				'data-apply-type'     => $args['apply_type'],
				'data-query-id'       => $args['query_id'],
			);

			echo $this->get_atts_string( $atts );

		}

		/**
		 * Return HTML attributes string from key=>value array
		 *
		 * @param  array $atts Attributes array.
		 *
		 * @return string
		 */
		public function get_atts_string( $atts ) {

			$result = array();

			foreach ( $atts as $key => $value ) {
				$result[] = sprintf( '%1$s="%2$s"', $key, $value );
			}

			return implode( ' ', $result );

		}

		/**
		 * Render fiter type template
		 *
		 * @param  int $filter_id filter ID.
		 * @param  array $args arguments.
		 *
		 * @return void
		 */
		public function render_filter_template( $filter_id, $args = array() ) {

			$filter = $this->get_filter_types( $filter_id );

			if ( ! empty( $filter->get_template() ) && file_exists( $filter->get_template() ) ) {

				$query_id   = isset( $args['query_id'] ) ? $args['query_id'] : 'default';
				$show_label = isset( $args['show_label'] ) ? $args['show_label'] : false;
				$display_options = isset( $args['display_options'] ) ? $args['display_options'] : array();

				if ( is_callable( array( $filter, 'prepare_args' ) ) ) {

					$args = $filter->prepare_args( $args );
				}

				$args['query_id']   = $query_id;
				$args['show_label'] = $show_label;
				$args['display_options'] = $display_options;

				include $filter->get_template();

			}
		}

	}

}