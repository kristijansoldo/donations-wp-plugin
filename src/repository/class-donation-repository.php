<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Donation_Repository
 *
 * @method Donation[]    get_all()
 */
class Donation_Repository extends Post_Type_Repository {

	/**
	 * @inheritDoc
	 */
	protected function getClassName(): string {
		return Donation::class;
	}
}