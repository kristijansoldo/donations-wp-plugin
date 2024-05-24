<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Donation_Service
 */
class Donation_Service extends Post_Type_Service {

	/**
	 * @var Donation_Repository
	 */
	private $donation_repository;

	public function __construct() {
		$this->donation_repository = new Donation_Repository();
	}
	
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
		$columns['progress_bar_shortcode'] = 'Progress bar shortcode';
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
            echo '['.Donation_Shortcode::get_shortcode().' id="'.$post_id.'"]';
        }

		if($column === 'progress_bar_shortcode') {
			echo '['.Progress_Bar_Shortcode::get_shortcode().' id="'.$post_id.'"]';
		}
     }

	/**
	 * Get all donations.
	 *
	 * @return array|Donation[]
	 */
	 public function get_all(): array {
		return $this->donation_repository->get_all();
	 }

	/**
	 * Get all donations.
	 *
	 * @return array|Donation[]
	 */


	/**
	 * Get donation by id
	 *
	 * @param $id
	 *
	 * @return object|Donation
	 */
	public function get_by_id($id) {
		return $this->donation_repository->get_by_id($id);
	}
}