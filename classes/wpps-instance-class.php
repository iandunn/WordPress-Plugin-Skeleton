<?php

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'WPPSInstanceClass' ) )
{
	/**
	 * Example of an instance class
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	class WPPSInstanceClass
	{
		protected $foo, $bar;
		protected $readableProtectedVars, $writableProtectedVars;
		const FOO	= 'foo';
		const BAR	= 'bar';


		/*
		 * Magic methods
		 */

		/**
		 * Constructor
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function __construct( $foo, $bar )
		{
			$this->foo						= $foo;
			$this->bar						= $bar;

			$this->readableProtectedVars	= array( 'foo', 'bar' );
			$this->writableProtectedVars	= array( 'foo' );

			if( !$this->isValid() )
				throw new Exception( __METHOD__ . " error: constructor input was invalid." );
		}

		/**
		 * Public getter for protected variables
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $variable
		 * @return mixed
		 */
		public function __get( $variable )
		{
			if( in_array( $variable, $this->readableProtectedVars ) )
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
			if( in_array( $variable, $this->writableProtectedVars ) )
			{
				$this->$variable = $value;
				
				if( !$this->isValid( $variable ) )
					throw new Exception( __METHOD__ . ' error: $'. $value .' is not valid.' );
			}
			else
				throw new Exception( __METHOD__ . " error: $". $variable ." doesn't exist or isn't writable." );
		}


		/*
		 * Static methods
		 */

		/**
		 * Does static stuff
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function doStaticStuff()
		{
			// Do static stuff
		}


		/*
	  	 * Instance methods
		 */

		/**
		 * Checks that the object is in a correct state
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $property An individual property to check, or 'all' to check all of them
		 * @return bool
		 */
		protected function isValid( $property = 'all' )
		{
			if( $property == 'foo' || $property == 'all' )
			{
				if( empty( $this->foo ) )
					return false;
			}
			
			if( $property == 'bar' || $property == 'all' )
			{
				if( !is_numeric( $this->bar ) )
					return false;
			}

			return true;
		}

		/**
		 * Does instance stuff
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @return bool
		 */
		public function doInstanceStuff()
		{
			// do instance stuff
			
			return true;
		}
	} // end WPPSInstanceClass
}

?>