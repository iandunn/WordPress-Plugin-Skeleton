<?php

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'WPPSCron' ) ) {
	/**
	 * Handles cron jobs and intervals
	 * Note: Because WP-Cron only fires hooks when HTTP requests are made, make sure that an external monitoring service pings the site regularly to ensure hooks are fired frequently
	 *
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	class WPPSCron extends WPPSModule {
		protected static $readable_properties  = array();
		protected static $writeable_properties = array();

		/*
		 * Magic methods
		 */

		/**
		 * Constructor
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		protected function __construct() {
			$this->register_hook_callbacks();
		}


		/*
		 * Static methods
		 */

		/**
		 * Adds custom intervals to the cron schedule.
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param array $schedules
		 * @return array
		 */
		public static function add_custom_cron_intervals( $schedules ) {
			$schedules[ WordPressPluginSkeleton::PREFIX . 'debug' ] = array(
				'interval' => 5,
				'display'  => 'Every 5 seconds'
			);

			$schedules[ WordPressPluginSkeleton::PREFIX . 'ten_minutes' ] = array(
				'interval' => 60 * 10,
				'display'  => 'Every 10 minutes'
			);

			$schedules[ WordPressPluginSkeleton::PREFIX . 'example_interval' ] = array(
				'interval' => 60 * 60 * 5,
				'display'  => 'Every 5 hours'
			);

			return $schedules;
		}

		/**
		 * Fires a cron job at a specific time of day, rather than on an interval
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function fire_job_at_time() {
			if ( did_action( WordPressPluginSkeleton::PREFIX . 'cron_timed_jobs' ) !== 1 ) {
				return;
			}

			$now = current_time( 'timestamp' );

			// Example job to fire between 1am and 3am
			if ( (int) date( 'G', $now ) >= 1 && (int) date( 'G', $now ) <= 3 ) {
				if ( ! get_transient( WordPressPluginSkeleton::PREFIX . 'cron_example_timed_job' ) ) {
					//WPPSCPTExample::exampleTimedJob();
					set_transient( WordPressPluginSkeleton::PREFIX . 'cron_example_timed_job', true, 60 * 60 * 6 );
				}
			}
		}

		/**
		 * Example WP-Cron job
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param array $schedules
		 * @return array
		 */
		public static function exampleJob() {
			if ( did_action( WordPressPluginSkeleton::PREFIX . 'cron_example_job' ) !== 1 ) {
				return;
			}

			// Do stuff

			WordPressPluginSkeleton::$notices->enqueue( __METHOD__ . ' cron job fired.' );
		}


		/*
		 * Instance methods
		 */

		/**
		 * Register callbacks for actions and filters
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function register_hook_callbacks() {
			// NOTE: Make sure you update the did_action() parameter in the corresponding callback method when changing the hooks here
			add_action( WordPressPluginSkeleton::PREFIX . 'cron_timed_jobs',  __CLASS__ . '::fire_job_at_time' );
			add_action( WordPressPluginSkeleton::PREFIX . 'cron_example_job', __CLASS__ . '::exampleJob' );

			add_action( 'init',                                               array( $this, 'init' ) );

			add_filter( 'cron_schedules',                                     __CLASS__ . '::add_custom_cron_intervals' );
		}

		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {
			if ( wp_next_scheduled( WordPressPluginSkeleton::PREFIX . 'cron_timed_jobs' ) === false ) {
				wp_schedule_event(
					current_time( 'timestamp' ),
					WordPressPluginSkeleton::PREFIX . 'ten_minutes',
					WordPressPluginSkeleton::PREFIX . 'cron_timed_jobs'
				);
			}

			if ( wp_next_scheduled( WordPressPluginSkeleton::PREFIX . 'cron_example_job' ) === false ) {
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
		public function deactivate() {
			wp_clear_scheduled_hook( WordPressPluginSkeleton::PREFIX . 'timed_jobs' );
			wp_clear_scheduled_hook( WordPressPluginSkeleton::PREFIX . 'example_job' );
		}

		/**
		 * Initializes variables
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function init() {
			if ( did_action( 'init' ) !== 1 ) {
				return;
			}
		}

		/**
		 * Executes the logic of upgrading from specific older versions of the plugin to the current version
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param string $db_version
		 */
		public function upgrade( $db_version = 0 ) {
			/*
			if( version_compare( $db_version, 'x.y.z', '<' ) )
			{
				// Do stuff
			}
			*/
		}

		/**
		 * Checks that the object is in a correct state
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param string $property An individual property to check, or 'all' to check all of them
		 * @return bool
		 */
		protected function is_valid( $property = 'all' ) {
			return true;
		}
	} // end WPPSCron
}

?>