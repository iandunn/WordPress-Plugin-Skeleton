<?php

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'WordPressPluginSkeleton' ) )
{
	/**
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	class WordPressPluginSkeleton
	{
		// Declare variables and constants
		protected static $callbacksRegistered, $notices;
		const VERSION			= '0.1';
		const PREFIX			= 'WPPS_';
		const DEBUG_MODE		= false;

		/**
		 * Register callbacks for actions and filters
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function registerCallbacks()
		{
			if( self::$callbacksRegistered === true )
				return;

			// NOTE: Make sure you update the did_action() parameter in the corresponding callback method when changing the hooks here
			add_action( 'init',		__CLASS__ . '::init' );
						
			WPPSCustomPostType::registerCallbacks();
			WPPSCron::registerCallbacks();
			WPPSSettings::registerCallbacks();
			
			self::$callbacksRegistered = true;
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
			if( self::DEBUG_MODE )
				self::$notices->debugMode = true;
			
			try
			{
				$nonStatic = new WPPSNonStaticClass( 'Non-static example', '42' );
				//self::$notices->mEnqueue( $nonStatic->foo .' '. $nonStatic->bar );
			}
			catch( Exception $e )
			{
				self::$notices->mEnqueue( __METHOD__ . ' error: '. $e->getMessage(), 'error' );
			}
		}

		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function activate()
		{
			WPPSCustomPostType::activate();
			WPPSCron::activate();
			flush_rewrite_rules();
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function deactivate()
		{
			WPPSCron::deactivate();
			flush_rewrite_rules();
		}
	} // end WordPressPluginSkeleton
	
	require_once( dirname( __FILE__ ) . '/../includes/IDAdminNotices/id-admin-notices.php' );
	require_once( dirname( __FILE__ ) . '/wpps-custom-post-type.php' );
	require_once( dirname( __FILE__ ) . '/wpps-settings.php' );
	require_once( dirname( __FILE__ ) . '/wpps-cron.php' );
	require_once( dirname( __FILE__ ) . '/wpps-non-static-class.php' );
}

?>