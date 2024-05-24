<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Donation_Payment_Repository
 *
 * @method Donation_Payment[]    get_all()
 */
class Donation_Payment_Repository extends Post_Type_Repository {

	/**
	 * @inheritDoc
	 */
	protected function getClassName(): string {
		return Donation_Payment::class;
	}

	/**
	 * @param int $donation_id
	 *
	 * @return array|Donation_Payment[]
	 */
	public function get_all_by_donation_id( $donation_id) {
		// Gets class name
		$class_name = $this->getClassName();

		// Gets post type=
		$post_type = Annotation_Service::get_class_annotation( $class_name, 'post_type' );

		// Init args
		$args = [
			'post_type'   => $post_type,
			'post_status' => 'publish',
			'meta_query'  => [],
			'numberposts' => -1,
			'suppress_filters' => false
		];

		if ( ! is_null( $donation_id) ) {
			$args['meta_query'][] =
				[
					'key'   => Donation_Payment::$post_meta['donation_id'],
					'value' => $donation_id,
				];
		}

		// Arguments
		$wp_posts = get_posts( $args );

		if ( ! $wp_posts ) {
			return [];
		}

		return array_map( function ( $wp_post ) use ( $class_name ) {
			return new $class_name( $wp_post );
		}, $wp_posts );

	}
}