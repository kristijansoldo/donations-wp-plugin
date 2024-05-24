<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Donation_Payment_Service
 */
class Donation_Payment_Service extends Post_Type_Service {

	/**
	 * @var Donation_Payment_Repository
	 */
	private $donation_payment_repository;

	public function __construct() {
		$this->donation_payment_repository = new Donation_Payment_Repository();
	}

	/**
	 * @inheritDoc
	 */
	public static function get_class_name(): string {
		return Donation_Payment::class;
	}

	/**
	 * @return string
	 */
	public static function get_slug(): string {
		return 'donation-payments';
	}

	/**
	 * @return string
	 */
	public static function get_post_type(): string {
		return 'donation-payment';
	}

	public static function get_plural_label(): string {
		return 'Donation payments';
	}

	public static function get_singular_label(): string {
		return 'Donation payment';
	}

	/**
	 * @return array
	 */
	public static function get_labels() {
		// Get labels
		$labels = parent::get_labels();

		// Set in submenu
		$labels['all_items'] = __( static::get_plural_label(), DP_PLUGIN_TEXTOMAIN );

		// Returns labels
		return $labels;

	}

	/**
	 * @param array $labels
	 *
	 * @return array
	 */
	public static function get_args( array $labels ) {
		// Get args
		$args = parent::get_args( $labels );

		// Set in submenu
		$args['show_in_menu'] = 'edit.php?post_type=' . Donation_Service::get_post_type();

		// Returns args
		return $args;
	}

	/**
	 * @param array $columns
	 *
	 * @return array
	 */
	public static function add_custom_columns( array $columns ) {
		$columns['amount']   = 'Amount';
		$columns['donation'] = 'Donation';

		return $columns;
	}

	/**
	 * @param string $column
	 * @param int $post_id
	 *
	 * @return void
	 */
	public static function custom_column_content( $column, $post_id ) {
		// Initialize donation service
		$donation_service = new Donation_Service();

		// Gets donation payment
		$donation_payment = new Donation_Payment( get_post( $post_id ) );

		// Gets donation by id
		$donation = $donation_service->get_by_id( $donation_payment->donation_id );

		// Print amount
		if ( $column === 'amount' ) {
			echo $donation_payment->amount . ' ' . General_Settings::CURRENCY_SYMBOL[ General_Settings::get( General_Settings::CURRENCY ) ];
		}

		// Print donation
		if ( $column === 'donation' ) {
			echo '<a href="' . get_edit_post_link( $donation->post ) . '">' . $donation->title . '</a>';
		}
	}

	/**
	 * Persists donation payment.
	 *
	 * @param Donation_Payment $donation_payment
	 *
	 * @return Donation_Payment
	 */
	public function create( Donation_Payment $donation_payment ): Donation_Payment {

		// Defines post array
		$post_arr = array(
			'post_type'   => static::get_post_type(),
			'post_title'  => $donation_payment->title,
			'post_status' => 'publish'
		);

		// Try to create post
		$post_id = wp_insert_post( $post_arr );

		// Update post meta
		update_post_meta( $post_id, Donation_Payment::$post_meta['amount'], $donation_payment->amount );
		update_post_meta( $post_id, Donation_Payment::$post_meta['donation_id'], $donation_payment->donation_id );
		update_post_meta( $post_id, Donation_Payment::$post_meta['order_id'], $donation_payment->order_id );

		// Returns created donation payment
		return new Donation_Payment( get_post( $post_id ) );
	}

	/**
	 * Gets all by donation id.
	 *
	 * @param $donation_id
	 *
	 * @return array
	 */
	public function get_all_by_donation_id($donation_id): array {
		return $this->donation_payment_repository->get_all_by_donation_id($donation_id);
	}
}