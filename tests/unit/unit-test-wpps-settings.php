<?php

require_once( WP_PLUGIN_DIR . '/simpletest-for-wordpress/WpSimpleTest.php' );
require_once( dirname( dirname( __DIR__ ) ) . '/classes/wpps-settings.php' );

/**
 * Unit tests for the WPPSSettings class
 * Uses the SimpleTest For WordPress plugin
 *
 * @package WordPressPluginSkeleton
 * @author Ian Dunn <ian@iandunn.name>
 * @link    http://wordpress.org/extend/plugins/simpletest-for-wordpress/
 */
if ( ! class_exists( 'UnitTestWPPSSettings' ) ) {
	class UnitTestWPPSSettings extends UnitTestCase {
		public function __construct() {
			$this->WPPSSettings = WPPSSettings::get_instance();
		}

		/*
		 * validate_settings()
		 */
		public function test_validate_settings() {
			// Valid settings
			$this->WPPSSettings->init();
			$valid_settings = array(
				'basic'    => array(
					'field-example1' => 'valid data'
				),

				'advanced' => array(
					'field-example2' => 5
				)
			);

			$clean_settings = $this->WPPSSettings->validate_settings( $valid_settings );

			$this->assertEqual( $valid_settings['basic']['field-example1'], $clean_settings['basic']['field-example1'] );
			$this->assertEqual( $valid_settings['advanced']['field-example2'], $clean_settings['advanced']['field-example2'] );


			// Invalid settings
			$this->WPPSSettings->init();
			$invalid_settings = array(
				'basic'    => array(
					'field-example1' => 'invalid data'
				),

				'advanced' => array(
					'field-example2' => - 5
				)
			);

			$clean_settings = $this->WPPSSettings->validate_settings( $invalid_settings );
			$this->assertNotEqual( $invalid_settings['basic']['field-example1'], $clean_settings['basic']['field-example1'] );
			$this->assertNotEqual( $invalid_settings['advanced']['field-example2'], $clean_settings['advanced']['field-example2'] );
		}

		/*
		 * __set()
		 */
		public function test_magic_set() {
			// Test that fields are validated
			$this->WPPSSettings->init();
			$this->WPPSSettings->settings = array( 'db-version' => array() );
			$this->assertEqual( $this->WPPSSettings->settings['db-version'], WordPressPluginSkeleton::VERSION );

			// Test that values gets written to database
			$this->WPPSSettings->settings = array( 'db-version' => '5' );
			$this->WPPSSettings->init();
			$this->assertEqual( $this->WPPSSettings->settings['db-version'], '5' );
			$this->WPPSSettings->settings = array( 'db-version' => WordPressPluginSkeleton::VERSION );

			// Test that setting deep values triggers error
			$this->expectError( new PatternExpectation( '/Indirect modification of overloaded property/i' ) );
			$this->WPPSSettings->settings['db-version'] = WordPressPluginSkeleton::VERSION;
		}
	} // end UnitTestWPPSSettings
}

?>