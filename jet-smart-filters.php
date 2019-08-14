<?php
/**
 * Plugin Name: JetSmartFilters
 * Plugin URI: https://crocoblock.com/woocommerce/jetsmartfilters/
 * Description: Adds easy-to-use AJAX filters to the pages built with Elementor which contain the dynamic listings.
 * Version:     1.4.1
 * Author:      Zemez
 * Author URI:  https://zemez.io/zemezjet/
 * Text Domain: jet-smart-filters
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Jet_Smart_Filters` doesn't exists yet.
if ( ! class_exists( 'Jet_Smart_Filters' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Jet_Smart_Filters {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Holder for base plugin URL
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_url = null;

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		private $version = '1.4.1';

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Plugin base name
		 *
		 * @var string
		 */
		public $plugin_name = null;

		/**
		 * Components
		 */
		public $framework;
		public $post_type;
		public $data;
		public $filter_types;
		public $providers;
		public $widgets;
		public $query;
		public $render;
		public $settings;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			$this->plugin_name = plugin_basename( __FILE__ );

			// Load framework
			add_action( 'after_setup_theme', array( $this, 'framework_loader' ), -20 );

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );
			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );

		}

		/**
		 * Returns plugin version
		 *
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Load framework modules
		 *
		 * @return [type] [description]
		 */
		public function framework_loader() {

			require $this->plugin_path( 'framework/loader.php' );

			$this->framework = new Jet_Smart_Filters_CX_Loader(
				array(
					$this->plugin_path( 'framework/interface-builder/cherry-x-interface-builder.php' ),
					$this->plugin_path( 'framework/post-meta/cherry-x-post-meta.php' ),
					$this->plugin_path( 'framework/term-meta/cherry-x-term-meta.php' ),
				)
			);

		}

		/**
		 * Manually init required modules.
		 *
		 * @return void
		 */
		public function init() {

			$this->load_files();

			$this->settings     = new Jet_Smart_Filters_Settings();
			$this->post_type    = new Jet_Smart_Filters_Post_Type();
			$this->query        = new Jet_Smart_Filters_Query_Manager();
			$this->render       = new Jet_Smart_Filters_Render();
			$this->data         = new Jet_Smart_Filters_Data();
			$this->filter_types = new Jet_Smart_Filters_Filter_Manager();
			$this->providers    = new Jet_Smart_Filters_Providers_Manager();
			$this->widgets      = new Jet_Smart_Filters_Widgets_Manager();

			if ( is_admin() ) {

				require $this->plugin_path( 'includes/admin.php' );

				new Jet_Smart_Filters_Admin();

				require $this->plugin_path( 'includes/updater/plugin-update.php' );

				new Jet_Smart_Filters_Plugin_Update( array(
					'version' => $this->get_version(),
					'slug'    => 'jet-smart-filters',
				) );

				// Init plugin changelog
				require $this->plugin_path( 'includes/updater/plugin-changelog.php' );

				jet_smart_filters_plugin_changelog()->init( array(
					'name'     => 'JetSmartFilters',
					'slug'     => 'jet-smart-filters',
					'version'  => $this->get_version(),
					'author'   => '<a href="https://zemez.io/zemezjet/">Zemez</a>',
					'homepage' => 'https://crocoblock.com/woocommerce/jetsmartfilters/',
					'banners'  => array(
						'high' => $this->plugin_url( 'assets/images/banner.png' ),
						'low'  => $this->plugin_url( 'assets/images/banner.png' ),
					),
				) );

			}

			do_action( 'jet-smart-filters/init', $this );

		}

		/**
		 * Load required files
		 *
		 * @return void
		 */
		public function load_files() {
			require $this->plugin_path( 'includes/post-type.php' );
			require $this->plugin_path( 'includes/functions.php' );
			require $this->plugin_path( 'includes/data.php' );
			require $this->plugin_path( 'includes/widgets.php' );
			require $this->plugin_path( 'includes/query.php' );
			require $this->plugin_path( 'includes/render.php' );
			require $this->plugin_path( 'includes/filters/manager.php' );
			require $this->plugin_path( 'includes/providers/manager.php' );
			require $this->plugin_path( 'includes/settings.php' );
			require $this->plugin_path( 'includes/compatibility.php' );
		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor() {
			return defined( 'ELEMENTOR_VERSION' );
		}

		/**
		 * Returns path to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;
		}
		/**
		 * Returns url to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function lang() {
			load_plugin_textdomain(
				'jet-smart-filters',
				false,
				dirname( plugin_basename( __FILE__ ) ) . '/languages'
			);
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'jet-smart-filters/template-path', 'jet-smart-filters/' );
		}

		/**
		 * Returns path to template file.
		 *
		 * @return string|bool
		 */
		public function get_template( $name = null ) {

			$template = locate_template( $this->template_path() . $name );

			if ( ! $template ) {
				$template = $this->plugin_path( 'templates/' . $name );
			}

			if ( file_exists( $template ) ) {
				return $template;
			} else {
				return false;
			}
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

if ( ! function_exists( 'jet_smart_filters' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function jet_smart_filters() {
		return Jet_Smart_Filters::get_instance();
	}
}

jet_smart_filters();
