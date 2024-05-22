<?php

abstract class Settings {

	/**
	 * @return string
	 */
	public abstract function get_settings_page_slug(): string;

	/**
	 * @return string
	 */
	public abstract function get_settings_page_name(): string;

	/**
	 * @return array|Setting_Field_Dto[]
	 */
	public abstract function get_setting_fields(): array;

	/**
	 * Initialize page
	 *
	 * @param Donations_Plugin_Loader $loader
	 *
	 * @return void
	 */
	public function load( Donations_Plugin_Loader $loader ) {
		// Hook to add the settings page to the admin menu
		$loader->add_action( 'admin_menu', $this, 'init_settings_page' );
		$loader->add_action( 'admin_init', $this, 'register_settings' );
	}

	/**
	 * Add settings page
	 *
	 * @return void
	 */
	public function init_settings_page() {
		add_submenu_page(
			'edit.php?post_type='.Donation_Service::get_post_type(), // Parent slug
			$this->get_settings_page_name(),
			$this->get_settings_page_name(),
			'manage_options',
			$this->get_settings_page_slug(),
			[ $this, 'render_settings_page' ]
		);
	}

	/**
	 * Function to render the settings page
	 */
	public function render_settings_page() {

		// Gets template
		$template_path = dirname( __FILE__ ) . '/../../admin/partials/settings-page.php';

		// Initialize current object
		$settings = $this;

		// Include template
		include($template_path);
	}

	/**
	 * Renders inputs in settings page template
	 *
	 * @return void
	 */
	public function get_form() {
		settings_fields($this->get_settings_page_slug());
		do_settings_sections($this->get_settings_page_slug());
		submit_button();
	}

	/**
	 * Registers settings for donations
	 *
	 * @return void
	 */
	public function register_settings() {

		// Register settings section
		add_settings_section(
			$this->get_settings_page_slug(),
			$this->get_settings_page_name(),
			function() {},
			$this->get_settings_page_slug()
		);

		// Get fields
		$fields = $this->get_setting_fields();

		// Iterates through and render fields
		foreach ($fields as $setting_field_dto) {
			register_setting( $this->get_settings_page_slug(), $setting_field_dto->name );
			add_settings_field(
				$setting_field_dto->name,
				$setting_field_dto->label,
				function() use ($setting_field_dto) {
					$this->setting_field_callback($setting_field_dto);
				},
				$this->get_settings_page_slug(),
				$this->get_settings_page_slug()
			);
		}
	}

	/**
	 * Render setting field
	 *
	 * @param Setting_Field_Dto $setting_field_dto
	 *
	 * @return void
	 */
	public function setting_field_callback(Setting_Field_Dto $setting_field_dto) {
		$setting = get_option($setting_field_dto->name);
		echo "<input type='text' name='$setting_field_dto->name' value='$setting' />";
	}

	/**
	 * Get option.
	 *
	 * @param string $option
	 *
	 * @return mixed
	 */
	public static function get(string $option) {
		return get_option($option);
	}
}