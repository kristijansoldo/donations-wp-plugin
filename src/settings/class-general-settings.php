<?php

class General_Settings extends Settings {

	const CURRENCY_SYMBOL = [
		'EUR' => '€',
		'USD' => '$'
	];

	const CUSTOM_CSS_CLASS = '_dp_general_custom_css_class';
	const THANK_YOU_MESSAGE = '_dp_thank_you_message';
	const CURRENCY = '_dp_currency';
	const PREDEFINED_AMOUNTS = '_dp_predefined_amounts';
	const DESCRIPTION_BEFORE = '_dp_description_before';
	const DESCRIPTION_AFTER = '_dp_description_after';

	/**
	 * Defines settings page slug
	 *
	 * @return string
	 */
	public function get_settings_page_slug(): string {
		return 'dp-general-settings';
	}

	/**
	 * Defines settings page display name
	 *
	 * @return string
	 */
	public function get_settings_page_name(): string {
		return 'General settings';
	}

	// Defines setting fields
	public function get_setting_fields(): array {
		// Initialize array
		$fields = [];

		// Add fields to array
		$fields[] = new Setting_Field_Dto(static::CUSTOM_CSS_CLASS, 'Custom CSS class');
		$fields[] = new Setting_Field_Dto(static::THANK_YOU_MESSAGE, 'Thank you message');
		$fields[] = new Setting_Field_Dto(static::CURRENCY, 'Currency code');
		$fields[] = new Setting_Field_Dto(static::DESCRIPTION_BEFORE, 'Description before form');
		$fields[] = new Setting_Field_Dto(static::DESCRIPTION_AFTER, 'Description after form');
		$fields[] = new Setting_Field_Dto(static::PREDEFINED_AMOUNTS, 'Predefined amounts, comma separated');

		// Returns fields
		return $fields;
	}
}