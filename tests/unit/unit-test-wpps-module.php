<?php

require_once( WP_PLUGIN_DIR . '/simpletest-for-wordpress/WpSimpleTest.php' );
require_once( dirname( dirname( __DIR__ ) ) . '/classes/wpps-module.php' );

/**
 * Unit tests for the WPPS_Module class
 * Uses the SimpleTest For WordPress plugin
 *
 * @link http://wordpress.org/extend/plugins/simpletest-for-wordpress/
 */
if ( ! class_exists( 'UnitTestWPPS_Module' ) ) {
	class UnitTestWPPS_Module extends UnitTestCase {
		/*
		 * get_instance()
		 */
		public function test_get_instance() {
			// Two instances of the same module
			$first_instance = WPPSChildClass::get_instance();
			$first_instance->init();
			$first_instance->foo = 'first';

			$second_instance = WPPSChildClass::get_instance();
			$second_instance->init();
			$second_instance->foo = 'second';

			$this->assertEqual( $first_instance->foo, $second_instance->foo );

			// Two different modules
			$separate_module = WPPSAnotherChildClass::get_instance();
			$separate_module->init();
			$this->assertNotEqual( $second_instance->foo, $separate_module->foo );
		}

		/*
		 * __get()
		 */
		public function test_magic_get() {
			$child = WPPSChildClass::get_instance();

			// Readable
			$child->init();
			$readable_properties = array( 'foo', 'bar' );

			foreach ( $readable_properties as $property ) {
				try {
					$value = $child->$property;
					$this->pass();
				} catch ( Exception $e ) {
					$this->fail( 'Unexpected exception from ' . $property . '. ' . $e->getMessage() );
				}
			}

			// Not readable
			$child->init();
			$unreadable_properties = array( 'charlie', 'nonexistant' );

			foreach ( $unreadable_properties as $property ) {
				try {
					$value = $child->$property;
					$this->fail( 'Expected an exception from ' . $property . '.' );
				} catch ( Exception $e ) {
					$this->pass();
				}
			}
		}

		/*
		 * __set()
		 */
		public function test_magic_set() {
			$child = WPPSChildClass::get_instance();

			// Writable
			$child->init();
			$writable_properties = array( 'foo' );

			foreach ( $writable_properties as $property ) {
				try {
					$child->$property = 'test';
					$this->pass();
				} catch ( Exception $e ) {
					$this->fail( 'Unexpected exception from ' . $property . '. ' . $e->getMessage() );
				}
			}

			// Not writeable
			$child->init();
			$unwritable_properties = array( 'charlie', 'nonexistant' );

			foreach ( $unwritable_properties as $property ) {
				try {
					$child->$property = 'test';
					$this->fail( 'Expected an exception from ' . $property . '.' );
				} catch ( Exception $e ) {
					$this->pass();
				}
			}
		}
	} // end UnitTestWPPS_Module
}

// Mock up a child class
if ( ! class_exists( 'WPPSChildClass' ) ) {
	class WPPSChildClass extends WPPS_Module {
		protected $foo, $bar, $charlie;
		protected static $readable_properties = array( 'foo', 'bar' );
		protected static $writeable_properties = array( 'foo' );

		// example of extending get/set to add extra logic
		protected function __construct() {
			$this->init();
		}

		public function activate( $network_wide ) {
		}

		public function deactivate() {
		}

		public function register_hook_callbacks() {
		}

		public function init() {
			$this->foo     = 'initial foo';
			$this->bar     = 'initial bar';
			$this->charlie = 'initial charlie';
		}

		public function upgrade( $db_version = 0 ) {
		}

		protected function is_valid( $property = 'all' ) {
			return true;
		}
	}
}

// Mock up a child class
if ( ! class_exists( 'WPPSAnotherChildClass' ) ) {
	class WPPSAnotherChildClass extends WPPS_Module {
		protected $delta, $echo, $foo;
		protected static $readable_properties = array( 'delta', 'foo' );
		protected static $writeable_properties = array( 'foo' );

		// example of extending get/set to add extra logic
		protected function __construct() {
			$this->init();
		}

		public function activate( $network_wide ) {
		}

		public function deactivate() {
		}

		public function register_hook_callbacks() {
		}

		public function init() {
			$this->delta = 'initial delta';
			$this->echo  = 'initial echo';
			$this->foo   = 'initial foo';
		}

		public function upgrade( $db_version = 0 ) {
		}

		protected function is_valid( $property = 'all' ) {
			return false;
		}
	}
}

?>