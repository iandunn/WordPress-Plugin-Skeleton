<?php

/**
 * Test suite for unit and integration tests
 *
 * Uses the SimpleTest For WordPress plugin
 *
 * @link http://wordpress.org/extend/plugins/simpletest-for-wordpress/
 */
if ( ! class_exists( 'WPPSTestSuite' ) ) {
	class WPPSTestSuite extends TestSuite {
		function __construct() {
			parent::__construct();

			$this->addFile( dirname( __FILE__ ) . '/unit/unit-test-wpps-module.php' );
			$this->addFile( dirname( __FILE__ ) . '/unit/unit-test-wpps-settings.php' );
			$this->addFile( dirname( __FILE__ ) . '/unit/unit-test-wpps-instance-class.php' );
		}

		/**
		 * Sets a protected or private method to be accessible
		 * @author Joel Uckelman <http://www.nomic.net/~uckelman/>
		 * @link   http://stackoverflow.com/questions/249664/best-practices-to-test-protected-methods-with-phpunit
		 *
		 * @param string $class_name
		 * @param string $method_name
		 * @return object
		 */
		public static function get_hidden_method( $class_name, $method_name ) {
			$class  = new ReflectionClass( $class_name );
			$method = $class->getMethod( $method_name );
			$method->setAccessible( true );

			return $method;
		}
	} // end WPPSUnitTestSuite
}

?>