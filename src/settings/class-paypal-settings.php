<?php

class PayPal_Settings extends Settings {

	const ENVIRONMENT_URL = '_dp_paypal_env_url';
	const CLIENT_ID = '_dp_paypal_client_id';
	const CLIENT_SECRET = '_dp_paypal_client_secret';

	/**
	 * Defines settings page slug
	 *
	 * @return string
	 */
	public function get_settings_page_slug(): string {
		return 'dp-paypal-settings';
	}

	/**
	 * Defines settings page display name
	 *
	 * @return string
	 */
	public function get_settings_page_name(): string {
		return 'Paypal settings';
	}

	// Defines setting fields
	public function get_setting_fields(): array {
		// Initialize array
		$fields = [];

		// Add fields to array
		$fields[] = new Setting_Field_Dto(static::ENVIRONMENT_URL, 'Environment URL');
		$fields[] = new Setting_Field_Dto(static::CLIENT_ID, 'Client ID');
		$fields[] = new Setting_Field_Dto(static::CLIENT_SECRET, 'Client Secret');

		// Returns fields
		return $fields;
	}
}