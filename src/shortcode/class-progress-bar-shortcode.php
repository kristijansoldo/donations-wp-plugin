<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Progress_Bar_Shortcode
 */
class Progress_Bar_Shortcode extends Shortcode {

	/**
	 * @var Donation_Payment_Service
	 */
	private $donation_payment_service;

	/**
	 * @var Donation_Service
	 */
	private $donation_service;

	public function __construct() {
		$this->donation_payment_service = new Donation_Payment_Service();
		$this->donation_service = new Donation_Service();
	}

	/**
	 * Defines shortcode string
	 *
	 * @return string
	 */
	public static function get_shortcode() {
		return 'dp_progress_bar';
	}

	/**
	 * Render from shortcode.
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function render($atts) {
		// Attributes
		$atts = shortcode_atts(
			array(
				'id' => null
			),
			$atts,
			static::get_shortcode()
		);

		// Capture the output of the included template file
		ob_start();

		// Donation id
		$donation_id = $atts['id'];

		// Gets donation by id
		$donation = $this->donation_service->get_by_id($donation_id);

		// Gets all payments by donation id
		$donation_payments = $this->donation_payment_service->get_all_by_donation_id($donation_id);

		// Gets template
		$template_path = dirname( __FILE__ ) . '/../../public/partials/progress-bar-template.php';

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