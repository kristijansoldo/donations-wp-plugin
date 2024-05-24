<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Donation payment
 *
 * @plural_label Donation payments
 * @singular_label Donation payment
 * @post_type donation-payment
 * @icon dashicons-heart
 */
class Donation_Payment extends Post_Type {

	/**
	 * Post type meta keys.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $post_meta = [
		'amount'  => '_dp_amount',
		'donation_id' => '_dp_donation_id',
		'order_id' => '_dp_order_id'
	];

	/**
	 * @var string
	 */
	public $amount;

	/**
	 * @var int
	 */
	public $donation_id;

	/**
	 * @var int
	 */
	public $order_id;


	/**
	 * Event constructor.
	 *
	 * @param WP_Post|null $wp_post
	 */
	public function __construct( WP_Post $wp_post = null ) {
		// Calls parent constructor
		parent::__construct( $wp_post );

		// Sets the data
		$this->amount  = get_post_meta( $this->id, static::$post_meta['amount'], true );
		$this->donation_id  = get_post_meta( $this->id, static::$post_meta['donation_id'], true );
		$this->order_id  = get_post_meta( $this->id, static::$post_meta['order_id'], true );
	}
}