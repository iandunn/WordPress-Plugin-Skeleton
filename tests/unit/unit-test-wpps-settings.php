<?php

/**
 * Unit tests for the WPPSSettings class
 * Uses the SimpleTest For WordPress plugin
 * 
 * @package WordPressPluginSkeleton
 * @author Ian Dunn <ian@iandunn.name>
 * @link http://wordpress.org/extend/plugins/simpletest-for-wordpress/
 */
class UnitTestWPPSSettings extends UnitTestCase
{
	/*
	 * validateFieldValues()
	 */
	public function testValidateFieldValues()
	{
		// Valid settings
		$validSettings = array(
			'basic'		=> array(
				'field-example1'	=> 'valid data'
			),
			
			'advanced'	=> array(
				'field-example2'	=> 5
			)
		);
		
		$cleanSettings = WPPSSettings::validateFieldValues( $validSettings );
		$this->assertEqual( $validSettings[ 'basic' ][ 'field-example1' ], $cleanSettings[ 'basic' ][ 'field-example1' ] );
		$this->assertEqual( $validSettings[ 'advanced' ][ 'field-example2' ], $cleanSettings[ 'advanced' ][ 'field-example2' ] );
		
		
		// Invalid settings
		$invalidSettings = array(
			'basic'		=> array(
				'field-example1'	=> 'invalid data'
			),
			
			'advanced'	=> array(
				'field-example2'	=> -5
			)
		);
		
		$cleanSettings = WPPSSettings::validateFieldValues( $invalidSettings );
		$this->assertNotEqual( $invalidSettings[ 'basic' ][ 'field-example1' ], $cleanSettings[ 'basic' ][ 'field-example1' ] );
		$this->assertNotEqual( $invalidSettings[ 'advanced' ][ 'field-example2' ], $cleanSettings[ 'advanced' ][ 'field-example2' ] );
		
	}
} // end UnitTestWPPSSettings

require_once( WP_PLUGIN_DIR . '/simpletest-for-wordpress/WpSimpleTest.php' );
require_once( dirname( dirname( __DIR__ ) ) . '/classes/wpps-settings.php' );

// Create any necessary stubs
if( !function_exists( 'add_settings_error' ) ) { function add_settings_error() {} }

?>