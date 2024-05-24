<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;


/**
 * Class Post_Type_Service
 */
abstract class Post_Type_Service {

	/**
	 * @return string
	 */
	abstract public static function get_class_name(): string;

	/**
	 * @return string
	 */
	abstract public static function get_slug(): string;

	/**
	 * @return string
	 */
	public static function get_post_type(): string {
		return Annotation_Service::get_class_annotation( static::get_class_name(), 'post_type' );
	}

	/**
	 * @return string
	 */
	public static function get_singular_label(): string {
		return Annotation_Service::get_class_annotation( static::get_class_name(), 'singular_label' );
	}

	/**
	 * @return string
	 */
	public static function get_plural_label(): string {
		return Annotation_Service::get_class_annotation( static::get_class_name(), 'plural_label' );
	}

	/**
	 * @return string
	 */
	public static function get_icon(): string {
		return Annotation_Service::get_class_annotation( static::get_class_name(), 'icon' );
	}

	/**
	 * @return array
	 *
	 * @noinspection PhpDocMissingThrowsInspection
	 */
	public static function get_post_meta(): array {
		// Gets a reflection class
		$reflection_class = new ReflectionClass( static::get_class_name() );

		// Returns post type
		return $reflection_class->getStaticPropertyValue( 'post_meta' );
	}

	/**
	 * Load post type.
	 *
	 * @param Donations_Plugin_Loader $loader
	 */
	public static function load_post_type( Donations_Plugin_Loader $loader ) {
		// Init post type action
		$loader->add_action( 'init', static::class, 'init_post_type');
		// Add action on save file
		$loader->add_action( 'save_post', static::class, 'save_item', 10, 3 );
		// Add meta boxes
		$loader->add_action( 'add_meta_boxes', static::class, 'add_meta_boxes' );
        // Add custom columns
		$loader->add_filter( 'manage_'.static::get_post_type().'_posts_columns', static::class, 'add_custom_columns' );
        // Add custom column content
		$loader->add_action( 'manage_'.static::get_post_type().'_posts_custom_column', static::class, 'custom_column_content', 10, 2 );
	}


    /**
	 * @param array $columns
	 *
	 * @return array
	 */
	public static function add_custom_columns( array $columns ) {
		return $columns;
	}

    /**
	 * @param string $column
	 * @param int $post_id
	 *
	 * @return void
	 */
	public static function custom_column_content($column, $post_id) { return; }

	/**
	 * @param array $labels
	 *
	 * @return array
	 */
	public static function get_args( array $labels ) {
		return array(
			'labels'             => $labels,
			'description'        => __( 'Description.', DP_PLUGIN_TEXTOMAIN ),
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => static::get_slug() ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'show_in_rest'       => true,
			'show_admin_column'  => true,
			'menu_icon'          => static::get_icon(),
			'supports'           => array(
				'title',
				'editor' => false,
				'thumbnail' => false,
				'excerpt' => false,
			)
		);
	}

	/**
	 * @return array
	 */
	public static function get_labels( ) {
		return array(
			'name'               => _x( static::get_plural_label(), 'post type general name', DP_PLUGIN_TEXTOMAIN ),
			'singular_name'      => _x( static::get_singular_label(), 'post type singular name', DP_PLUGIN_TEXTOMAIN ),
			'menu_name'          => _x( static::get_plural_label(), 'admin menu', DP_PLUGIN_TEXTOMAIN ),
			'name_admin_bar'     => _x( static::get_plural_label(), 'add new on admin bar', DP_PLUGIN_TEXTOMAIN ),
			'add_new'            => _x( 'Add New', static::get_post_type(), DP_PLUGIN_TEXTOMAIN ),
			'add_new_item'       => __( 'Add New ' . static::get_singular_label(), DP_PLUGIN_TEXTOMAIN ),
			'new_item'           => __( 'New ' . static::get_plural_label(), DP_PLUGIN_TEXTOMAIN ),
			'edit_item'          => __( 'Edit ' . static::get_singular_label(), DP_PLUGIN_TEXTOMAIN ),
			'view_item'          => __( 'View ' . static::get_plural_label(), DP_PLUGIN_TEXTOMAIN ),
			'all_items'          => __( 'All ' . static::get_plural_label(), DP_PLUGIN_TEXTOMAIN ),
			'search_items'       => __( 'Search ' . static::get_plural_label(), DP_PLUGIN_TEXTOMAIN ),
			'parent_item_colon'  => __( 'Parent ' . static::get_singular_label() . ':', DP_PLUGIN_TEXTOMAIN ),
			'not_found'          => __( 'No ' . static::get_plural_label() . ' found.', DP_PLUGIN_TEXTOMAIN ),
			'not_found_in_trash' => __( 'No ' . static::get_plural_label() . ' found in Trash.', DP_PLUGIN_TEXTOMAIN ),
		);

	}

	/**
	 * Register post type.
	 *
	 * @since   1.0.0
	 */
	public static function init_post_type() {
		// Labels
		$labels = static::get_labels();

		// Args
		$args = static::get_args( $labels );

		// Register post type
		register_post_type( static::get_post_type(), $args );
	}

	/**
	 * Adds all post type meta boxes.
	 *
	 * @since   1.0.0
	 */
	public static function add_meta_boxes() {

		// Add file informations meta box
		add_meta_box(
			static::get_post_type() . '-informations',
			__( static::get_singular_label() . ' informations', DP_PLUGIN_TEXTOMAIN),
			[
				static::class,
				'render_meta_box',
			],
			static::get_post_type(),
			'advanced',
			'default'
		);

	}

	/**
	 * Render meta box.
	 *
	 * @param WP_Post $wp_post
	 *
	 * @since 1.0.0
	 *
	 */
	public static function render_meta_box( $wp_post ) {
		// Gets class name
		$class_name = static::get_class_name();

		// Get data
		${strtolower( $class_name )} = new $class_name( $wp_post );

		// Render template
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '../admin/partials/' . strtolower( $class_name ) . '-info-box.php';
	}

	/**
	 * On save
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 * @param bool $update
	 *
	 * @since 1.0.0
	 *
	 */
	public static function save_item( $post_id, $post, $update ) {
		// Gets post meta
		$post_meta = static::get_post_meta();

		// Save
		foreach ( $post_meta as $meta_key => $value ) {
			Meta_Data_Service::update_meta_data( $post_id, $post_meta, $meta_key );
		}
	}

}