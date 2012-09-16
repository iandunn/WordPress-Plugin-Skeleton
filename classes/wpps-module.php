<?php

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'WPPSModule' ) )
{
	/**
	 * Abstract class to define/implement base methods for all module classes
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	abstract class WPPSModule
	{
		private static $instances = array();
		
				
		/*
		 * Magic methods
		 */
		
		/**
		 * Public getter for protected variables
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $variable
		 * @return mixed
		 */
		public function __get( $variable )
		{
			$module = get_called_class();
			
			if( in_array( $variable, $module::$readableProperties ) )
				return $this->$variable;
			else
				throw new Exception( __METHOD__ . " error: $". $variable ." doesn't exist or isn't readable." );
		}

		/**
		 * Public setter for protected variables
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $variable
		 * @param mixed $value
		 */
		public function __set( $variable, $value )
		{
			$module = get_called_class();
			
			if( in_array( $variable, $module::$writeableProperties ) )
			{
				$this->$variable = $value;
				
				if( !$this->isValid() )
					throw new Exception( __METHOD__ . ' error: $'. $value .' is not valid.' );
			}
			else
				throw new Exception( __METHOD__ . " error: $". $variable ." doesn't exist or isn't writable." );
		}
		
		
		/*
		 * Non-abstract methods
		 */
		 
		/**
		 * Provides access to a single instance of the class using the singleton pattern
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @return object
		 */
		public static function getInstance()
		{
			$module = get_called_class();
			
			if( !isset( self::$instances[ $module ] ) )
				self::$instances[ $module ] = new $module();

			return self::$instances[ $module ];
		}
		
		
		/*
		 * Abstract methods
		 */
		
		/**
		 * Constructor
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		abstract protected function __construct();
		
		/**
		 * Prepares sites to use the plugin during single or network-wide activation
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		abstract public static function activate( $networkWide );

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		abstract public static function deactivate();
		
		/**
		 * Register callbacks for actions and filters
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		abstract public function registerHookCallbacks();
		
		/**
		 * Initializes variables
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		abstract public function init();

		/**
		 * Checks if the plugin was recently updated and upgrades if necessary
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		abstract public function upgrade( $dbVersion = 0 );
				 
		/**
		 * Checks that the object is in a correct state
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $property An individual property to check, or 'all' to check all of them
		 * @return bool
		 */
		abstract protected function isValid( $property = 'all' );
	} // end WPPSModule
}

?>