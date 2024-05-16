<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://soldokristijan.com
 * @since      1.0.0
 *
 * @package    Donations_Plugin
 * @subpackage Donations_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Donations_Plugin
 * @subpackage Donations_Plugin/includes
 * @author     Kristijan Soldo <soldokris@gmail.com>
 */
class Donations_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Donations_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'DONATIONS_PLUGIN_VERSION' ) ) {
			$this->version = DONATIONS_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'donations-plugin';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->init_post_types();
		$this->init_shortcodes();
		$this->init_pages();
		$this->load_controllers();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Donations_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Donations_Plugin_i18n. Defines internationalization functionality.
	 * - Donations_Plugin_Admin. Defines all hooks for the admin area.
	 * - Donations_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-donations-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-donations-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-donations-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-donations-plugin-public.php';

		/**
		 * The class responsible for defining all service actions meta data
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/service/class-meta-data-service.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/service/class-annotation-service.php';

		/**
		 * The classes responsible for defining shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/shortcode/class-shortcode.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/shortcode/class-donation-shortcode.php';

		/**
		 * The classes responsible for defining all actions about cpts.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/entity/class-post-type.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/service/class-post-type-service.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/repository/class-post-type-repository.php';

		/**
		 * The classes responsible for defining all actions about donations.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/entity/class-donation.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/service/class-donation-service.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/repository/class-donation-repository.php';

		/**
		 * The classes responsible for defining all actions about donation settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/service/class-settings-service.php';

		/**
		 * Load base controller class and controllers
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/controller/class-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/controller/class-paypal-controller.php';

		$this->loader = new Donations_Plugin_Loader();

	}

	/**
	 * Defines post types.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function init_post_types() {
		Donation_Service::load_post_type( $this->loader );
	}

	/**
	 * Defines shortcodes
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function init_shortcodes() {
		// Initialize shortcode
		$donation_shortcode = new Donation_Shortcode();
		$donation_shortcode->register_shortcode();
	}

	/**
	 * Defines pages.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function init_pages() {
		Settings_Service::load_page($this->loader);
	}

	/**
	 * Initializes controllers.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_controllers() {
		// Initialize paypal controller
		$paypal_controller = new PayPal_Controller();

		// Load paypal controller
		$paypal_controller->load($this->loader);
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Donations_Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Donations_Plugin_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Donations_Plugin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Donations_Plugin_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Donations_Plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
