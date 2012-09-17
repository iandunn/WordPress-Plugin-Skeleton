<?php

require_once( WP_PLUGIN_DIR . '/simpletest-for-wordpress/WpSimpleTest.php' );
require_once( dirname( dirname( __DIR__ ) ) . '/classes/wpps-module.php' );

/**
 * Unit tests for the WPPSModule class
 * Uses the SimpleTest For WordPress plugin
 * 
 * @package WordPressPluginSkeleton
 * @author Ian Dunn <ian@iandunn.name>
 * @link http://wordpress.org/extend/plugins/simpletest-for-wordpress/
 */
if( !class_exists( 'UnitTestWPPSModule' ) )
{
	class UnitTestWPPSModule extends UnitTestCase
	{
		/*
		 * getInstance()
		 */
		public function testGetInstance()
		{
			// Two instances of the same module
			$firstInstance = WPPSChildClass::getInstance();
			$firstInstance->init();
			$firstInstance->foo = 'first';
			
			$secondInstance = WPPSChildClass::getInstance();
			$secondInstance->init();
			$secondInstance->foo = 'second';
			
			$this->assertEqual( $firstInstance->foo, $secondInstance->foo );
			
			// Two different modules
			$separateModule = WPPSAnotherChildClass::getInstance();
			$separateModule->init();
			$this->assertNotEqual( $secondInstance->foo, $separateModule->foo );
		}
		
		/*
		 * __get()
		 */
		public function testMagicGet()
		{
			$child = WPPSChildClass::getInstance();
			$child->init();
			
			// Readable
			$readableProperties = array( 'foo', 'bar' );
			
			foreach( $readableProperties as $property )
			{
				try
				{
					$value = $child->$property;
					$this->pass();
				}
	
				catch( Exception $e )
				{
					$this->fail( 'Unexpected exception from '. $property .'. '. $e->getMessage() );
				}
			}
			
			// Not readable
			$child->init();
			$unreadableProperties = array( 'charlie', 'nonexistant' );
			
			foreach( $unreadableProperties as $property )
			{
				try
				{
					$value = $child->$property;
					$this->fail( 'Expected an exception from '. $property .'.' );
				}
				
				catch( Exception $e )
				{
					$this->pass();
				}
			}
		}
		
		/*
		 * __set()
		 */
		public function testMagicSet()
		{
			$child = WPPSChildClass::getInstance();
			$child->init();
			
			// Writable
			$writableProperties = array( 'foo' );
			
			foreach( $writableProperties as $property )
			{
				try
				{
					$child->$property = 'test';
					$this->pass();
				}
	
				catch( Exception $e )
				{
					$this->fail( 'Unexpected exception from '. $property .'. '. $e->getMessage() );
				}
			}
			
			// Not writeable
			$child->init();
			$unwritableProperties = array( 'charlie', 'nonexistant' );
			
			foreach( $unwritableProperties as $property )
			{
				try
				{
					$child->$property = 'test';
					$this->fail( 'Expected an exception from '. $property .'.' );
				}
				
				catch( Exception $e )
				{
					$this->pass();
				}
			}
		}
	} // end UnitTestWPPSModule
}

// Mock up a child class
if( !class_exists( 'WPPSChildClass' ) )
{
	class WPPSChildClass extends WPPSModule
	{
		protected $foo, $bar, $charlie;
		protected static $readableProperties = array( 'foo', 'bar' );
		protected static $writeableProperties = array( 'foo' );
		
		// exampel of extending get/set to add extra logic
		protected function __construct()
		{
			$this->init();
		}
		
		public static function activate( $networkWide )
		{
		}
				
		public static function deactivate()
		{
		}
		
		public function registerHookCallbacks()
		{
		}
		
		public function init()
		{
			$this->foo		= 'initial foo';
			$this->bar		= 'initial bar';
			$this->charlie	= 'initial charlie';
		}
		
		public function upgrade( $dbVersion = 0 )
		{
		}
		
		protected function isValid( $property = 'all' )
		{
			return true;
		}
	}
}

// Mock up a child class
if( !class_exists( 'WPPSAnotherChildClass' ) )
{
	class WPPSAnotherChildClass extends WPPSModule
	{
		protected $delta, $echo, $foo;
		protected static $readableProperties = array( 'delta', 'foo' );
		protected static $writeableProperties = array( 'foo' );
		
		// exampel of extending get/set to add extra logic
		protected function __construct()
		{
			$this->init();
		}
		
		
		public static function activate( $networkWide )
		{
		}
				
		public static function deactivate()
		{
		}
		
		public function registerHookCallbacks()
		{
		}
		
		public function init()
		{
			$this->delta	= 'initial delta';
			$this->echo		= 'initial echo';
			$this->foo		= 'initial foo';
		}
		
		public function upgrade( $dbVersion = 0 )
		{
		}
		
		protected function isValid( $property = 'all' )
		{
			return false;
		}
	}
}

?>