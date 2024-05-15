<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://soldokristijan.com
 * @since      1.0.0
 *
 * @package    Donations_Plugin
 * @subpackage Donations_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Donations_Plugin
 * @subpackage Donations_Plugin/public
 * @author     Kristijan Soldo <soldokris@gmail.com>
 */
class Donations_Plugin_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/donations-plugin-public.css', array(), $this->version, 'all' );
	
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'paypal-sdk', 'https://www.paypal.com/sdk/js?client-id=AeTCinCC2eyrihYvTkRX0QFjdkZLK811s4xrHV_K-xTX_gbl5y5FEOxfOmZqXZ2nXzak6sbEDgHY_EsR&currency=USD', array( ), null, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/donations-plugin-public.js', array( 'jquery' ), $this->version, false );
	
	}


	/**
	 * Register donation shortcode to show donation form
	 *
	 * @since    1.0.0
	 */
	public function register_donation_shortcode() {

		add_shortcode(Donation_Service::SHORTCODE, [$this, 'display_donation_form']);

	}

	/**
	 * Display form with shortcode
	 * 
	 * @param     array    $atts    Attributes
	 *
	 * @since    1.0.0
	 * 
	 * @return   string 
	 */
	public function display_donation_form($atts) {
		 // Attributes
		 $atts = shortcode_atts(
			array(
				'id' => null
			),
			$atts,
			Donation_Service::SHORTCODE
		);

		// Capture the output of the included template file
		ob_start();

		// Donation id
		$donation_id = $atts['id'];

		// Gets template
		$template_path = dirname( __FILE__ ) . '/partials/donation-form-template.php';

		// Check if exists template path
		if ($template_path) {
			include($template_path);
		} else {
			echo '<!-- Template file not found -->';
		}
	
		// Return html
		return ob_get_clean();
	}

}
