<?php

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'WPPSCron' ) )
{
	/**
	 * Handles cron jobs and intervals
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	class WPPSCron extends WPPSModule
	{
		protected static $readableProperties	= array();
		protected static $writeableProperties	= array();
		
		/*
		 * Magic methods
		 */
		
		/**
		 * Constructor
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		protected function __construct()
		{
			$this->registerHookCallbacks();
		}
		
		
		/*
		 * Static methods
		 */
		
		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param bool $networkWide
		 */
		public static function activate( $networkWide )
		{
			if( wp_next_scheduled( WordPressPluginSkeleton::PREFIX . 'cron_timed_jobs' ) === false )
			{
				wp_schedule_event(
					current_time( 'timestamp' ),
					WordPressPluginSkeleton::PREFIX . 'ten_minutes',
					WordPressPluginSkeleton::PREFIX . 'cron_timed_jobs'
				);
			}
				
			if( wp_next_scheduled( WordPressPluginSkeleton::PREFIX . 'cron_example_job' ) === false )
			{
				wp_schedule_event(
					current_time( 'timestamp' ),
					WordPressPluginSkeleton::PREFIX . 'example_interval',
					WordPressPluginSkeleton::PREFIX . 'cron_example_job'
				);
			}
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function deactivate()
		{
			wp_clear_scheduled_hook( WordPressPluginSkeleton::PREFIX . 'timed_jobs' );
			wp_clear_scheduled_hook( WordPressPluginSkeleton::PREFIX . 'example_job' );
		}
		
		/**
		 * Adds custom intervals to the cron schedule.
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $schedules
		 * @return array
		 */
		public static function addCustomCronIntervals( $schedules )
		{
			$schedules[ WordPressPluginSkeleton::PREFIX . 'debug' ] = array(
				'interval'	=> 5,
				'display'	=> 'Every 5 seconds'
			);
			
			$schedules[ WordPressPluginSkeleton::PREFIX . 'ten_minutes' ] = array(
				'interval'	=> 60 * 10,
				'display'	=> 'Every 10 minutes'
			);
			
			$schedules[ WordPressPluginSkeleton::PREFIX . 'example_interval' ] = array(
				'interval'	=> 60 * 60 * 5,
				'display'	=> 'Every 5 hours'
			);

			return $schedules;
		}
		
		/**
		 * Fires a cron job at a specific time of day, rather than on an interval
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function fireJobAtTime()
		{
			if( did_action( WordPressPluginSkeleton::PREFIX . 'cron_timed_jobs' ) !== 1 )
				return;
				
			$now = current_time( 'timestamp' );
			
			// Example job to fire between 1am and 3am
			if( (int) date( 'G', $now ) >= 1 && (int) date( 'G', $now ) <= 3 )
			{
				if( !get_transient( WordPressPluginSkeleton::PREFIX . 'cron_example_timed_job' ) )
				{
					//WPPSCPTExample::exampleTimedJob();
					set_transient( WordPressPluginSkeleton::PREFIX . 'cron_example_timed_job', true, 60 * 60 * 6 );
				}
			}
		}
		
		/**
		 * Example WP-Cron job
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $schedules
		 * @return array
		 */
		public static function exampleJob()
		{
			if( did_action( WordPressPluginSkeleton::PREFIX . 'cron_example_job' ) !== 1 )
				return;
			
			WordPressPluginSkeleton::$notices->enqueue( __METHOD__ . ' cron job fired.' );
		}
		
		
		/*
		 * Non-static methods
		 */
		 
		/**
		 * Register callbacks for actions and filters
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function registerHookCallbacks()
		{
			// NOTE: Make sure you update the did_action() parameter in the corresponding callback method when changing the hooks here
			add_action( WordPressPluginSkeleton::PREFIX . 'cron_timed_jobs',	__CLASS__ . '::fireJobAtTime' );
			add_action( WordPressPluginSkeleton::PREFIX . 'cron_example_job',	__CLASS__ . '::exampleJob' );
			add_action( 'init',													array( $this, 'init' ) );
			
			add_filter( 'cron_schedules',										__CLASS__ . '::addCustomCronIntervals' );
		}
		
		/**
		 * Initializes variables
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function init()
		{
			if( did_action( 'init' ) !== 1 )
				return;
		}
		
		/**
		 * Executes the logic of upgrading from specific older versions of the plugin to the current version
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $dbVersion
		 */
		public function upgrade( $dbVersion = 0 )
		{
			/*
			if( version_compare( $dbVersion, 'x.y.z', '<' ) )
			{
				// Do stuff
			}
			*/
		}

		/**
		 * Checks that the object is in a correct state
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $property An individual property to check, or 'all' to check all of them
		 * @return bool
		 */
		protected function isValid( $property = 'all' )
		{
			return true;
		}
	} // end WPPSCron
}

?>