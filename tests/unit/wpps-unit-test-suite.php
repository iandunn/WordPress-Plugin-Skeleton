<?php

/**
 * Unit tests for the WPPSSettings class
 * Uses the SimpleTest For WordPress plugin
 * 
 * @package WordPressPluginSkeleton
 * @author Ian Dunn <ian@iandunn.name>
 * @link http://wordpress.org/extend/plugins/simpletest-for-wordpress/
 */
if( !class_exists( 'WPPSUnitTestSuite' ) )
{
	class WPPSUnitTestSuite extends TestSuite
	{
		function __construct()
		{
			parent::__construct();
	
			$this->addFile( dirname( __FILE__ ) . '/unit-test-wpps-settings.php' );
			$this->addFile( dirname( __FILE__ ) . '/unit-test-wpps-non-static-class.php' );
	   }
		
		/**
		 * Sets a protected or private method to be accessible
		 * @author Joel Uckelman <http://www.nomic.net/~uckelman/>
		 * @link http://stackoverflow.com/questions/249664/best-practices-to-test-protected-methods-with-phpunit
		 * @param string $className
		 * @param string $methodName
		 * @return object
		 */
		public static function getHiddenMethod( $className, $methodName )
		{
			$class = new ReflectionClass( $className );
			$method = $class->getMethod( $methodName );
			$method->setAccessible( true );
			
			return $method;
		}
	} // end WPPSUnitTestSuite
}

?>