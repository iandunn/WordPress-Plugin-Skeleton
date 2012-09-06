<?php

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'WPPSCron' ) )
{
	/**
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	class WPPSCron
	{
		protected static $notices;
		
		/**
		 * Register callbacks for actions and filters
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function registerCallbacks()
		{
			// NOTE: Make sure you update the did_action() parameter in the corresponding callback method when changing the hooks here
			add_action( 'init',													__CLASS__ . '::init' );
			add_action( WordPressPluginSkeleton::PREFIX . 'cron_example_job',	__CLASS__ . '::exampleJob' );
			
			add_filter( 'cron_schedules',										__CLASS__ . '::addCustomCronIntervals' );
		}
		
		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function activate()
		{
			if( wp_next_scheduled( WordPressPluginSkeleton::PREFIX . 'cron_example_job' ) === false )
			{
				wp_schedule_event(
					current_time( 'timestamp' ),
					WordPressPluginSkeleton::PREFIX . 'cron_example_interval',
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
			wp_clear_scheduled_hook( WordPressPluginSkeleton::PREFIX . 'cron_example_job' );
		}
		
		/**
		 * Initializes variables
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function init()
		{
			if( did_action( 'init' ) !== 1 )
				return;

			self::$notices = IDAdminNotices::cGetSingleton();
			if( WordPressPluginSkeleton::DEBUG_MODE )
				self::$notices->debugMode = true;
		}
		
		/**
		 * Executes the logic of upgrading from specific older versions of the plugin to the current version
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $dbVersion
		 */
		public static function upgrade( $dbVersion )
		{
			/*
			if( version_compare( $dbVersion, 'x.y.z', '<' ) )
			{
				// Do stuff
			}
			*/
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
			$schedules[ WordPressPluginSkeleton::PREFIX . 'cron_example_interval' ] = array(
				'interval'	=> 60 * 60 * 5,
				'display'	=> 'Every 5 hours'
			);

			return $schedules;
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
			
			self::$notices->mEnqueue( __METHOD__ . ' cron job fired.' );
		}
	} // end WPPSCron
}

?>