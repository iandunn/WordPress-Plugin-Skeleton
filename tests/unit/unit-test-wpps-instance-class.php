<?php

require_once( WP_PLUGIN_DIR . '/simpletest-for-wordpress/WpSimpleTest.php' );
require_once( dirname( dirname( __DIR__ ) ) . '/classes/wpps-instance-class.php' );

/**
 * Unit tests for WPPS_Instance_Class
 *
 * Uses the SimpleTest For WordPress plugin
 *
 * @link http://wordpress.org/extend/plugins/simpletest-for-wordpress/
 */
if ( ! class_exists( 'UnitTestWPPS_Instance_Class' ) ) {
	class UnitTestWPPS_Instance_Class extends UnitTestCase {
		/*
		 * validate_is_valid()
		 */
		public function test_is_valid() {
			// Valid
			$valid_data_set = array(
				array( 'KUOW', 94.9 ),
				array( array( 'x' ), '1e4' ),
				array( new GenericObject(), 5 ),
			);

			foreach ( $valid_data_set as $pair ) {
				try {
					$valid = new WPPS_Instance_Class( $pair[0], $pair[1] );
					$this->pass();

					$valid->foo = $pair[0];
					$this->pass();
				} catch ( Exception $e ) {
					$this->fail( 'Unexpected exception from (' . $pair[0] . ', ' . $pair[1] . '). ' . $e->getMessage() );
				}
			}


			// Invalid
			$invalid_data_set = array(
				array( array(), 'a' ),
				array( 'hello', new GenericObject() ),
				array( '', 5 ),
			);

			foreach ( $invalid_data_set as $pair ) {
				try {
					$invalid = new WPPS_Instance_Class( $pair[0], $pair[1] );
					$this->fail( 'Expected an exception from (' . $pair[0] . ', ' . $pair[1] . ').' );
				} catch ( Exception $e ) {
					$this->pass();
				}
			}
		}
	} // end UnitTestWPPS_Settings
}

if ( ! class_exists( 'GenericObject' ) ) {
	class GenericObject {
		public function __toString() {
			return __CLASS__;
		}
	} // end GenericObject
}

?>