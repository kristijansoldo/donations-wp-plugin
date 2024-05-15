<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Meta_Data_Service
 */
class Meta_Data_Service {

	/**
	 * Helper function for update meta data.
	 *
	 * @since   1.0.0
	 *
	 * @param   int $post_id
	 * @param   mixed $meta_data
	 * @param   string $meta_key
	 * @param   bool $isEscape
	 */
	public static function update_meta_data( $post_id, $meta_data, $meta_key, $isEscape = false ) {
		if ( empty( $_REQUEST[ $meta_data[ $meta_key ] ] ) ) {
			delete_post_meta( $post_id, $meta_data[ $meta_key ] );
		} else {
			$request_value = $_REQUEST[ $meta_data[ $meta_key ] ];
			$value         = ( $isEscape ) ? esc_attr( $request_value ) : $request_value;
			update_post_meta( $post_id, $meta_data[ $meta_key ], $value );
		}
	}
}