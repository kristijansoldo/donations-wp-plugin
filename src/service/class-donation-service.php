<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Donation_Service
 */
class Donation_Service extends Post_Type_Service {
	/**
	 * @inheritDoc
	 */
	public static function get_class_name(): string {
		return Donation::class;
	}

	/**
	 * @return string
	 */
	public static function get_slug(): string {
		return 'donations';
	}

	/**
	 * @return string
	 */
	public static function get_post_type(): string {
		return 'donation';
	}
}