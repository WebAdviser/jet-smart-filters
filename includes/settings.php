<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Settings' ) ) {

	/**
	 * Define Jet_Smart_Filters_Settings class
	 */
	class Jet_Smart_Filters_Settings {

		/**
		 * [$key description]
		 * @var string
		 */
		public $key = 'jet-smart-filters-settings';

		/**
		 * [$builder description]
		 * @var null
		 */
		public $builder  = null;

		/**
		 * [$settings description]
		 * @var null
		 */
		public $settings = null;

		/**
		 * Avaliable Widgets array
		 *
		 * @var array
		 */
		public $avaliable_providers = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			$this->init_builder();

			add_action( 'admin_menu', array( $this, 'register_page' ), 99 );
			add_action( 'init', array( $this, 'save' ), 40 );
			add_action( 'admin_notices', array( $this, 'saved_notice' ) );

			foreach ( glob( jet_smart_filters()->plugin_path( 'includes/providers/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				if( $data['name'] ){
					$this->avaliable_providers[ $data['class'] ] = $data['name'];
				}

			}

		}

		/**
		 * Initialize page builder module if reqired
		 *
		 * @return [type] [description]
		 */
		public function init_builder() {

			if ( ! isset( $_REQUEST['page'] ) || $this->key !== $_REQUEST['page'] ) {
				return;
			}

			$data = jet_smart_filters()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			$this->builder = new CX_Interface_Builder(
				array(
					'path' => $data['path'],
					'url'  => $data['url'],
				)
			);

		}

		/**
		 * Show saved notice
		 *
		 * @return bool
		 */
		public function saved_notice() {

			if ( ! isset( $_GET['settings-saved'] ) ) {
				return false;
			}

			$message = esc_html__( 'Settings saved', 'jet-smart-filters' );

			printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message );

			return true;

		}

		/**
		 * Save settings
		 *
		 * @return void
		 */
		public function save() {

			if ( ! isset( $_REQUEST['page'] ) || $this->key !== $_REQUEST['page'] ) {
				return;
			}

			if ( ! isset( $_REQUEST['action'] ) || 'save-settings' !== $_REQUEST['action'] ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$current = get_option( $this->key, array() );
			$data    = $_REQUEST;

			unset( $data['action'] );

			foreach ( $data as $key => $value ) {
				$current[ $key ] = is_array( $value ) ? $value : esc_attr( $value );
			}

			update_option( $this->key, $current );

			$redirect = add_query_arg(
				array( 'dialog-saved' => true ),
				$this->get_settings_page_link()
			);

			wp_redirect( $redirect );
			die();

		}

		/**
		 * Return settings page URL
		 *
		 * @return string
		 */
		public function get_settings_page_link() {

			return add_query_arg(
				array(
					'page' => $this->key,
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		public function get( $setting, $default = false ) {

			if ( null === $this->settings ) {
				$this->settings = get_option( $this->key, array() );
			}

			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;

		}

		/**
		 * Register add/edit page
		 *
		 * @return void
		 */
		public function register_page() {

			add_submenu_page(
				'elementor',
				esc_html__( 'Jet Smart Filters Settings', 'jet-smart-filters' ),
				esc_html__( 'Jet Smart Filters Settings', 'jet-smart-filters' ),
				'manage_options',
				$this->key,
				array( $this, 'render_page' )
			);

		}

		/**
		 * Render settings page
		 *
		 * @return void
		 */
		public function render_page() {

			foreach ( $this->avaliable_providers as $key => $value ) {
				$default_avaliable_providers[ $key ] = 'true';
			}

			$this->builder->register_section(
				array(
					'jet_smart_filters' => array(
						'type'   => 'section',
						'scroll' => false,
						'title'  => esc_html__( 'Jet Smart Filters Settings', 'jet-smart-filters' ),
					),
				)
			);

			$this->builder->register_form(
				array(
					'jet_smart_filters_form' => array(
						'type'   => 'form',
						'parent' => 'jet_smart_filters',
						'action' => add_query_arg(
							array( 'page' => $this->key, 'action' => 'save-settings' ),
							esc_url( admin_url( 'admin.php' ) )
						),
					),
				)
			);

			$this->builder->register_settings(
				array(
					'settings_top' => array(
						'type'   => 'settings',
						'parent' => 'jet_smart_filters_form',
					),
					'settings_bottom' => array(
						'type'   => 'settings',
						'parent' => 'jet_smart_filters_form',
					),
				)
			);

			$this->builder->register_component(
				array(
					'jet_smart_filters_tab_vertical' => array(
						'type'   => 'component-tab-vertical',
						'parent' => 'settings_top',
					),
				)
			);

			$this->builder->register_settings(
				array(
					'avaliable_providers_options' => array(
						'parent'      => 'jet_smart_filters_tab_vertical',
						'title'       => esc_html__( 'Use filters for widgets', 'jet-smart-filters' ),
					),
				)
			);

			$controls = apply_filters( 'jet-smart-filters/settings-page/controls-list',
				array(
					'avaliable_providers' => array(
						'type'        => 'checkbox',
						'id'          => 'avaliable_providers',
						'name'        => 'avaliable_providers',
						'parent'      => 'avaliable_providers_options',
						'value'       => $this->get( 'avaliable_providers', $default_avaliable_providers ),
						'options'     => $this->avaliable_providers,
						'title'       => esc_html__( 'Use filters for widgets', 'jet-smart-filters' ),
						'description' => esc_html__( 'List of content widgets that available for filtering.', 'jet-smart-filters' ),
						'class'       => 'jet_smart_filters_form__checkbox-group'
					),
				)
			);

			$this->builder->register_control( $controls );

			$this->builder->register_html(
				array(
					'save_button' => array(
						'type'   => 'html',
						'parent' => 'settings_bottom',
						'class'  => 'cx-component dialog-save',
						'html'   => '<button type="submit" class="button button-primary">' . esc_html__( 'Save', 'jet-smart-filters' ) . '</button>',
					),
				)
			);

			echo '<div class="jet-smart-filters-settings-page">';
				$this->builder->render();
			echo '</div>';
		}

	}
}