<?php

if ( ! class_exists( 'WPPS_Cron' ) ) {

	/**
	 * Handles cron jobs and intervals
	 *
	 * Note: Because WP-Cron only fires hooks when HTTP requests are made, make sure that an external monitoring service pings the site regularly to ensure hooks are fired frequently
	 */
	class WPPS_Cron extends WPPS_Module {
		protected static $readable_properties  = array();
		protected static $writeable_properties = array();

		/*
		 * Magic methods
		 */

		/**
		 * Constructor
		 *
		 * @mvc Controller
		 */
		protected function __construct() {
			$this->register_hook_callbacks();
		}


		/*
		 * Static methods
		 */

		/**
		 * Adds custom intervals to the cron schedule.
		 *
		 * @mvc Model
		 *
		 * @param array $schedules
		 * @return array
		 */
		public static function add_custom_cron_intervals( $schedules ) {
			$schedules[ 'wpps_debug' ] = array(
				'interval' => 5,
				'display'  => 'Every 5 seconds'
			);

			$schedules[ 'wpps_ten_minutes' ] = array(
				'interval' => 60 * 10,
				'display'  => 'Every 10 minutes'
			);

			$schedules[ 'wpps_example_interval' ] = array(
				'interval' => 60 * 60 * 5,
				'display'  => 'Every 5 hours'
			);

			return $schedules;
		}

		/**
		 * Fires a cron job at a specific time of day, rather than on an interval
		 *
		 * @mvc Controller
		 */
		public static function fire_job_at_time() {
			$now = current_time( 'timestamp' );

			// Example job to fire between 1am and 3am
			if ( (int) date( 'G', $now ) >= 1 && (int) date( 'G', $now ) <= 3 ) {
				if ( ! get_transient( 'wpps_cron_example_timed_job' ) ) {
					//WPPS_CPT_Example::exampleTimedJob();
					set_transient( 'wpps_cron_example_timed_job', true, 60 * 60 * 6 );
				}
			}
		}

		/**
		 * Example WP-Cron job
		 *
		 * @mvc Model
		 *
		 * @param array $schedules
		 * @return array
		 */
		public static function example_job() {
			// Do stuff

			add_notice( __METHOD__ . ' cron job fired.' );
		}


		/*
		 * Instance methods
		 */

		/**
		 * Register callbacks for actions and filters
		 *
		 * @mvc Controller
		 */
		public function register_hook_callbacks() {
			add_action( 'wpps_cron_timed_jobs',  __CLASS__ . '::fire_job_at_time' );
			add_action( 'wpps_cron_example_job', __CLASS__ . '::example_job' );

			add_action( 'init',                  array( $this, 'init' ) );

			add_filter( 'cron_schedules',        __CLASS__ . '::add_custom_cron_intervals' );
		}

		/**
		 * Prepares site to use the plugin during activation
		 *
		 * @mvc Controller
		 *
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {
			if ( wp_next_scheduled( 'wpps_cron_timed_jobs' ) === false ) {
				wp_schedule_event(
					current_time( 'timestamp' ),
					'wpps_ten_minutes',
					'wpps_cron_timed_jobs'
				);
			}

			if ( wp_next_scheduled( 'wpps_cron_example_job' ) === false ) {
				wp_schedule_event(
					current_time( 'timestamp' ),
					'wpps_example_interval',
					'wpps_cron_example_job'
				);
			}
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 *
		 * @mvc Controller
		 */
		public function deactivate() {
			wp_clear_scheduled_hook( 'wpps_cron_timed_jobs' );
			wp_clear_scheduled_hook( 'wpps_cron_example_job' );
		}

		/**
		 * Initializes variables
		 *
		 * @mvc Controller
		 */
		public function init() {
		}

		/**
		 * Executes the logic of upgrading from specific older versions of the plugin to the current version
		 *
		 * @mvc Model
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
		 *
		 * @mvc Model
		 *
		 * @param string $property An individual property to check, or 'all' to check all of them
		 * @return bool
		 */
		protected function is_valid( $property = 'all' ) {
			return true;
		}
	} // end WPPS_Cron
}
