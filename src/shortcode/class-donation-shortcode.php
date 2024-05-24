<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class DonationShortcode
 */
class Donation_Shortcode extends Shortcode {

	/**
	 * Defines shortcode string
	 *
	 * @return string
	 */
	public static function get_shortcode() {
		return 'dp_donation';
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
		$id_suffix = '';

		// Gets template
		$template_path = dirname( __FILE__ ) . '/../../public/partials/donation-form-template.php';

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