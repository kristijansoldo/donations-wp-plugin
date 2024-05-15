<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Donation
 *
 * @plural_label Donations
 * @singular_label Donation
 * @post_type donation
 * @icon dashicons-heart
 */
class Donation extends Post_Type {

	/**
	 * Post type meta keys.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $post_meta = [
		'target_amount'  => '_dp_target_amount',
	];

	/**
	 * @var string
	 */
	public $target_amount;


	/**
	 * Event constructor.
	 *
	 * @param WP_Post|null $wp_post
	 */
	public function __construct( WP_Post $wp_post = null ) {
		// Calls parent constructor
		parent::__construct( $wp_post );

		// Sets the data
		$this->target_amount  = get_post_meta( $this->id, static::$post_meta['target_amount'], true );
	}
}