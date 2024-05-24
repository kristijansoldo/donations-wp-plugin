<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Post_Type_Repository
 */
abstract class Post_Type_Repository {

	/**
	 * @return string
	 */
	abstract protected function getClassName(): string;

	/**
	 * Get all items.
	 *
	 * @return array
	 * @since 1.0.0
	 *
	 */
	public function get_all() {
		// Gets class name
		$class_name = $this->getClassName();

		// Arguments
		$wp_posts = get_posts( [
			'post_type'      => Annotation_Service::get_class_annotation($class_name, 'post_type'),
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'suppress_filters' => 0
		] );

		if ( ! $wp_posts ) {
			return [];
		}

		return array_map( function ( $wp_post ) use ($class_name) {
			return new $class_name( $wp_post );
		}, $wp_posts );
	}

	/**
	 * Get all items by limit.
	 *
	 * @param int $limit
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_all_by_limit($limit = 3) {
		// Gets class name
		$class_name = $this->getClassName();

		// Arguments
		$wp_posts = get_posts( [
			'post_type'      => Annotation_Service::get_class_annotation($class_name, 'post_type'),
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'suppress_filters' => 0
		] );

		if ( ! $wp_posts ) {
			return [];
		}

		return array_map( function ( $wp_post ) use ($class_name) {
			return new $class_name( $wp_post );
		}, $wp_posts );
	}

	/**
	 * Get by id.
	 *
	 * @param int $id
	 *
	 * @return object
	 */
	public function get_by_id( $id ) {
		// Gets class name
		$class_name = $this->getClassName();

		// Get post
		$wp_post = get_post( $id );

		// Returns job
		return new $class_name( $wp_post );
	}

}