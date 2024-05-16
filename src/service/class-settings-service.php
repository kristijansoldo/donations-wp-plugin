<?php

class Settings_Service {

	const SETTINGS_PAGE = 'donations-settings-page';
	const PAYPAL_SETTINGS = 'donations-paypal-settings';
	const PAYPAL_SETTINGS_FIELDS = [
		'client_id' => '_dp_client_id',
		'client_secret' => '_dp_client_secret'
	];

	/**
	 * Initialize page
	 *
	 * @param Donations_Plugin_Loader $loader
	 *
	 * @return void
	 */
	public static function load_page( Donations_Plugin_Loader $loader ) {
		// Hook to add the settings page to the admin menu
		$loader->add_action( 'admin_menu', static::class, 'init_settings_page' );
		$loader->add_action( 'admin_init', static::class, 'register_settings' );
	}

	/**
	 * Add settings page
	 *
	 * @return void
	 */
	public static function init_settings_page() {
		add_submenu_page(
			'edit.php?post_type='.Donation_Service::get_post_type(), // Parent slug
			'Donations settings',
			'Settings',
			'manage_options',
			static::SETTINGS_PAGE,
			[ static::class, 'render_settings_page' ]
		);
	}

	/**
	 * Function to render the settings page
	 */
	public static function render_settings_page() {

		// Gets template
		$template_path = dirname( __FILE__ ) . '/../../admin/partials/donation-settings-page.php';

		// Include template
		include($template_path);
	}

	/**
	 * Registers settings for donations
	 *
	 * @return void
	 */
	public static function register_settings() {
		register_setting( 'donations_settings_section', static::PAYPAL_SETTINGS_FIELDS['client_id'] );
		register_setting( 'donations_settings_section', static::PAYPAL_SETTINGS_FIELDS['client_secret'] );

		add_settings_section(
			static::PAYPAL_SETTINGS,
			'Paypal Settings',
			[static::class, 'donations_settings_section_callback'],
			static::SETTINGS_PAGE
		);

		add_settings_field(
			static::PAYPAL_SETTINGS_FIELDS['client_id'],
			'Client id',
			[static::class, static::PAYPAL_SETTINGS_FIELDS['client_id'].'_setting_name_callback'],
			static::SETTINGS_PAGE,
			static::PAYPAL_SETTINGS
		);

		add_settings_field(
			static::PAYPAL_SETTINGS_FIELDS['client_secret'],
			'Client secret',
			[static::class, static::PAYPAL_SETTINGS_FIELDS['client_secret'].'_setting_name_callback'],
			static::SETTINGS_PAGE,
			static::PAYPAL_SETTINGS
		);
	}

	public static function donations_settings_section_callback() {}

	public static function _dp_client_id_setting_name_callback() {
		$setting = get_option(static::PAYPAL_SETTINGS_FIELDS['client_id']);
		$name = static::PAYPAL_SETTINGS_FIELDS['client_id'];

		echo "<input type='text' name='$name' value='$setting' />";
	}

	public static function _dp_client_secret_setting_name_callback() {
		$setting = get_option(static::PAYPAL_SETTINGS_FIELDS['client_secret']);
		$name = static::PAYPAL_SETTINGS_FIELDS['client_secret'];
		echo "<input type='text' name='$name' value='$setting' />";
	}

	public static function get_client_id() {
		return get_option(static::PAYPAL_SETTINGS_FIELDS['client_id']);
	}

	public static function get_client_secret() {
		return get_option(static::PAYPAL_SETTINGS_FIELDS['client_secret']);
	}
}