<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Donation_Modal_Shortcode
 */
class Donation_Modal_Shortcode extends Shortcode {

	/**
	 * @var Donation_Service
	 */
	private $donation_service;

	public function __construct() {
		$this->donation_service = new Donation_Service();
	}

	/**
	 * Defines shortcode string
	 *
	 * @return string
	 */
	public static function get_shortcode() {
		return 'dp_donation_modal';
	}

	/**
	 * Render from shortcode.
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function render($atts) {
		// Capture the output of the included template file
		ob_start();

		// Gets donations
		$donations = $this->donation_service->get_all();

		// Gets template
		$template_path = dirname( __FILE__ ) . '/../../public/partials/donation-modal-template.php';

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