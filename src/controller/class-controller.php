<?php

/**
 * Class Controller
 */
abstract class Controller {

	/**
	 * @return array
	 */
	abstract public function get_endpoints(): array;

	/**
	 * @return string
	 */
	public function get_version(): string {
		return 'v1';
	}

	/**
	 * @return string
	 */
	public function get_base_route(): string {
		return 'dp/' . $this->get_version();
	}

	/**
	 * Register routes
	 */
	public function register_routes() {
		// Gets endpoints
		$endpoints = $this->get_endpoints();

		// Foreach through endpoints
		foreach ( $endpoints as $endpoint ) {
			register_rest_route( $this->get_base_route(), $endpoint['endpoint'], [
				'methods'             => $endpoint['method'],
				'callback'            => [ $this, $endpoint['callback'] ],
				'permission_callback' => '__return_true'
			] );
		}
	}

	/**
	 * @param Donations_Plugin_Loader $loader
	 */
	public function load( Donations_Plugin_Loader $loader ){
		$loader->add_action( 'rest_api_init', $this, 'register_routes' );
	}

	/**
	 * Returns error.
	 *
	 * @param Exception $exception
	 *
	 * @return WP_Error
	 */
	public function error( Exception $exception ): WP_Error {
		return new WP_Error( $exception->getCode(), $exception->getMessage(), [ 'status' => intval( $exception->getCode() ) ? intval( $exception->getCode() ) : 500 ] );
	}
}