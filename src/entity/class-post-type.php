<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Post_Type
 */
class Post_Type {

	/**
	 * Post type meta keys.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $post_meta = [];

	/**
	 * @since   1.0.0
	 * @var     WP_Post
	 */
	public $post;

	/**
	 * @since   1.0.0
	 * @var     int
	 */
	public $id;

	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var false|string
	 */
	public $featured_image;

	/**
	 * Post type constructor
	 *
	 * @param WP_Post|null $wp_post
	 */
	public function __construct( WP_Post $wp_post = null ) {
		// If empty
		if ( empty( $wp_post ) ) {
			return;
		}

		// Sets the data
		$this->post           = $wp_post;
		$this->id             = $wp_post->ID;
		$this->title          = $wp_post->post_title;
		$this->description    = $wp_post->post_excerpt;
		$this->featured_image = get_the_post_thumbnail_url( $wp_post );
		$this->featured_image = ( $this->featured_image ) ? $this->featured_image : 'https://via.placeholder.com/380x200?text=PLACEHOLDER';
	}
}