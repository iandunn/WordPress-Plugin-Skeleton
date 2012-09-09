<?php

/**
 * Unit tests for the WPPSSettings class
 * Uses the SimpleTest For WordPress plugin
 * 
 * @package WordPressPluginSkeleton
 * @author Ian Dunn <ian@iandunn.name>
 * @link http://wordpress.org/extend/plugins/simpletest-for-wordpress/
 */
class UnitTestWPPSNonStaticClass extends UnitTestCase
{
	/*
	 * validateFieldValues()
	 */
	public function testIsValid()
	{
		// Valid
		$validDataSet = array(
			array( 'KUOW', 94.9 ),
			array( array( 'x' ), '1e4' ),
			array( new stdClass(), 5 ),
		);
		
		foreach( $validDataSet as $pair )
		{
	        try
	        {
	            $valid = new WPPSNonStaticClass( $pair[ 0 ], $pair[ 1 ] );
				$this->pass();
				
				$valid->foo = $pair[ 0 ];
				$this->pass();
	        }
	
	        catch( Exception $e )
			{
	            $this->fail( 'Unexpected exception from ('. $pair[ 0 ] .', '. $pair[ 1 ] .'). '. $e->getMessage() );
	        }
		}		
		
		// Invalid
		$invalidDataSet = array(
			array( array(), 'a' ),
			array( 'hello', new stdClass() ),
			array( '', 5 ),
		);
		
		foreach( $invalidDataSet as $pair )
		{
	        try
	        {
	            $invalid = new WPPSNonStaticClass( $pair[ 0 ], $pair[ 1 ] );
				$this->fail( 'Expected exception from ('. $pair[ 0 ] .', '. $pair[ 1 ] .').' );
	        }
	
	        catch( Exception $e )
			{
	            $this->pass();
	        }
		}
	}
} // end UnitTestWPPSSettings

require_once( WP_PLUGIN_DIR . '/simpletest-for-wordpress/WpSimpleTest.php' );
require_once( dirname( dirname( __DIR__ ) ) . '/classes/wpps-non-static-class.php' );

?>