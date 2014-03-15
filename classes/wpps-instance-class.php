<?php

if ( ! class_exists( 'WPPS_Instance_Class' ) ) {

	/**
	 * Example of an instance class
	 */
	class WPPS_Instance_Class {
		protected $foo, $bar;
		protected $readable_protected_vars, $writeable_protected_vars;
		const FOO = 'foo';
		const BAR = 'bar';


		/*
		 * Magic methods
		 */

		/**
		 * Constructor
		 *
		 * @mvc Controller
		 */
		public function __construct( $foo, $bar ) {
			$this->foo = $foo;
			$this->bar = $bar;

			$this->readable_protected_vars = array( 'foo', 'bar' );
			$this->writeable_protected_vars = array( 'foo' );

			if ( ! $this->is_valid() ) {
				throw new Exception( __METHOD__ . " error: constructor input was invalid." );
			}
		}

		/**
		 * Public getter for protected variables
		 *
		 * @mvc Model
		 *
		 * @param string $variable
		 * @return mixed
		 */
		public function __get( $variable ) {
			if ( in_array( $variable, $this->readable_protected_vars ) ) {
				return $this->$variable;
			} else {
				throw new Exception( __METHOD__ . " error: $" . $variable . " doesn't exist or isn't readable." );
			}
		}

		/**
		 * Public setter for protected variables
		 *
		 * @mvc Model
		 *
		 * @param string $variable
		 * @param mixed  $value
		 */
		public function __set( $variable, $value ) {
			if ( in_array( $variable, $this->writeable_protected_vars ) ) {
				$this->$variable = $value;

				if ( ! $this->is_valid( $variable ) ) {
					throw new Exception( __METHOD__ . ' error: $' . $value . ' is not valid.' );
				}
			} else {
				throw new Exception( __METHOD__ . " error: $" . $variable . " doesn't exist or isn't writable." );
			}
		}


		/*
		 * Static methods
		 */

		/**
		 * Does static stuff
		 *
		 * @mvc Controller
		 */
		public static function do_static_stuff() {
			// Do static stuff
		}


		/*
	  	 * Instance methods
		 */

		/**
		 * Checks that the object is in a correct state
		 *
		 * @mvc Model
		 *
		 * @param string $property An individual property to check, or 'all' to check all of them
		 * @return bool
		 */
		protected function is_valid( $property = 'all' ) {
			if ( 'foo' == $property || 'all' == $property ) {
				if ( empty( $this->foo ) ) {
					return false;
				}
			}

			if ( 'bar' == $property || 'all' == $property ) {
				if ( ! is_numeric( $this->bar ) ) {
					return false;
				}
			}

			return true;
		}

		/**
		 * Does instance stuff
		 *
		 * @mvc Model
		 *
		 * @return bool
		 */
		public function do_instance_stuff() {
			// do instance stuff

			return true;
		}
	} // end WPPS_Instance_Class
}
