<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Shortcode
 */
abstract class Shortcode {

	/**
	 * Registers shortcode
	 *
	 * @since    1.0.0
	 */
	public function register_shortcode() {
		add_shortcode(static::get_shortcode(), [$this, 'render']);
	}

	public abstract function render($atts);

	public static abstract function get_shortcode();
}