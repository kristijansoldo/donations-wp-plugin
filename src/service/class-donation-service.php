<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Donation_Service
 */
class Donation_Service extends Post_Type_Service {

    // Defines donations shortcode
    const SHORTCODE = 'simple_donation';

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

    /**
	 * @param array $columns
	 *
	 * @return array
	 */
	public static function add_custom_columns( array $columns ) {
        $columns['donation_shortcode'] = 'Donation shortcode';
		return $columns;
	}

    /**
	 * @param string $column
	 * @param int $post_id
	 *
	 * @return void
	 */
	public static function custom_column_content($column, $post_id) { 
        if($column === 'donation_shortcode') {
            echo '['.Donation_Service::SHORTCODE.' id="'.$post_id.'"]';
        }
     }
}