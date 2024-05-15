<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly;

/**
 * Class Annotation_Service
 */
class Annotation_Service {

	/**
	 * Gets class annotation.
	 *
	 * @param string $className
	 * @param string $annotation
	 *
	 * @return string
	 * @noinspection PhpDocMissingThrowsInspection
	 */
	public static function get_class_annotation(string $className, string $annotation): string {
		// Gets a reflection class
		$reflection_class = new ReflectionClass( $className );

		// Returns value
		return explode( ' ', implode( '', preg_grep( '/@'.$annotation.'\s.*/', explode( '*', $reflection_class->getDocComment() ) ) ) )[2];
	}
}