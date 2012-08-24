<?php

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'WPPSSettings' ) )
{
	/**
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	class WPPSSettings
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
			add_action( 'init',							__CLASS__ . '::init' );
			add_action( 'show_user_profile',			__CLASS__ . '::addUserFields' );
			add_action( 'edit_user_profile',			__CLASS__ . '::addUserFields' );
			add_action( 'personal_options_update',		__CLASS__ . '::saveUserFields' );
			add_action( 'edit_user_profile_update',		__CLASS__ . '::saveUserFields' );
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
		 * Adds extra option fields to a user's profile
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param object
		 */
		public static function addUserFields( $user )
		{
			if( did_action( 'show_user_profile' ) !== 1 && did_action( 'edit_user_profile' ) !== 1 )
				return;
			
			require_once( dirname( __FILE__ ) .'/../views/wpps-settings/user-fields.php' );
		}

		/**
		 * Validates and saves the values of extra user fields to the database 
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param int $userID
		 */
		public static function saveUserFields( $userID )
		{
			
			if( did_action( 'personal_options_update' ) !== 1 && did_action( 'edit_user_profile_update' ) !== 1 )
				return;

			if( !current_user_can( 'manage_options' ) )
			{
				if( get_user_meta( $userID, WordPressPluginSkeleton::PREFIX . 'user-example-field', true ) != $_POST[ WordPressPluginSkeleton::PREFIX . 'user-example-field' ] )
					self::$notices->mEnqueue( 'You do not have permission to change the Example Field for this user.', 'error' );
				
				return;
			}
			
			update_user_meta( $userID, WordPressPluginSkeleton::PREFIX . 'user-example-field', $_POST[ WordPressPluginSkeleton::PREFIX . 'user-example-field' ] );
		}
	} // end WPPSSettings
}

?>