<?php

/**
 * Class PayPal_Controller
 */
class PayPal_Controller extends Controller {


	private $api_base = 'https://api-m.sandbox.paypal.com';


	/**
	 * @inheritDoc
	 */
	public function get_endpoints(): array {
		return [
			[
				'endpoint' => '/orders',
				'method'   => 'POST',
				'callback' => 'create_order'
			],
			[
				'endpoint' => '/orders/(?P<orderID>[\w-]+)/capture',
				'method'   => 'POST',
				'callback' => 'capture_order'
			]
		];
	}


	private function generate_access_token() {
		$auth = base64_encode(PayPal_Settings::get(PayPal_Settings::CLIENT_ID) . ':' . PayPal_Settings::get(PayPal_Settings::CLIENT_SECRET));
		$response = wp_remote_post($this->api_base . '/v1/oauth2/token', array(
			'method' => 'POST',
			'body' => array('grant_type' => 'client_credentials'),
			'headers' => array(
				'Authorization' => 'Basic ' . $auth,
				'Content-Type' => 'application/x-www-form-urlencoded',
			),
		));

		if (is_wp_error($response)) {
			return null;
		}

		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body);

		return $data->access_token ?? null;
	}

	/**
	 * Create order
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function create_order(WP_REST_Request $request) {
		$amount = $request->get_param('amount');
		$access_token = $this->generate_access_token();

		if (!$access_token) {
			return new WP_Error('failed_to_generate_access_token', 'Failed to generate access token', array('status' => 500));
		}

		$response = wp_remote_post($this->api_base . '/v2/checkout/orders', array(
			'method' => 'POST',
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $access_token,
			),
			'body' => json_encode(array(
				'intent' => 'CAPTURE',
				'purchase_units' => array(
					array(
						'amount' => array(
							'currency_code' => 'EUR',
							'value' => $amount, // Calculate based on the cart
						),
					),
				),
			)),
		));

		return $this->handle_response($response);
	}

	public function capture_order(WP_REST_Request $request) {
		$order_id = $request->get_param('orderID');
		$access_token = $this->generate_access_token();

		if (!$access_token) {
			return new WP_Error('failed_to_generate_access_token', 'Failed to generate access token', array('status' => 500));
		}

		$response = wp_remote_post($this->api_base . "/v2/checkout/orders/{$order_id}/capture", array(
			'method' => 'POST',
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $access_token,
			),
		));

		return $this->handle_response($response);
	}


	private function handle_response($response) {
		if (is_wp_error($response)) {
			return new WP_Error('api_error', $response->get_error_message(), array('status' => 500));
		}

		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body);

		if (wp_remote_retrieve_response_code($response) >= 400) {
			return new WP_Error('api_error', $data->message ?? 'An error occurred', array('status' => wp_remote_retrieve_response_code($response)));
		}

		return rest_ensure_response($data);
	}

}