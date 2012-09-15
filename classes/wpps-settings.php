<?php

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'WPPSSettings' ) )
{
	/**
	 * Handles plugin settings and user profile meta fields
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	class WPPSSettings
	{
		public static $settings;	// Note: this should be considered read-only. It won't be automatically updated during the script's execution when values change
		protected static $notices, $defaultSettings;
		const REQUIRED_CAPABILITY = 'administrator';
		
		/**
		 * Register callbacks for actions and filters
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function registerHookCallbacks()
		{
			// NOTE: Make sure you update the did_action() parameter in the corresponding callback method when changing the hooks here
			add_action( 'init',							__CLASS__ . '::init' );
			add_action( 'admin_menu',					__CLASS__ . '::registerSettingsPages' );
			add_action( 'admin_init',					__CLASS__ . '::registerSettings' );
			add_action( 'show_user_profile',			__CLASS__ . '::addUserFields' );
			add_action( 'edit_user_profile',			__CLASS__ . '::addUserFields' );
			add_action( 'personal_options_update',		__CLASS__ . '::saveUserFields' );
			add_action( 'edit_user_profile_update',		__CLASS__ . '::saveUserFields' );
			
			add_filter( 'plugin_action_links_'. plugin_basename( dirname( __DIR__ ) ) .'/bootstrap.php',	__CLASS__ . '::addPluginActionLinks' );
		}
		
		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function activate()
		{
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function deactivate()
		{
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

			self::$notices = IDAdminNotices::getSingleton();
			if( WordPressPluginSkeleton::DEBUG_MODE )
				self::$notices->debugMode = true;
			
			self::$defaultSettings = self::getDefaultSettings();
			self::$settings = self::getSettings();
			
			self::$notices->enqueue( 'v: '.self::$settings['db-version'] );
		}
		
		/**
		 * Establishes initial values for all settings
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @return array
		 */
		protected static function getDefaultSettings()
		{
			$basic = array(
				'field-example1'	=> ''
			);
				
			$advanced = array(
				'field-example2'	=> ''
			);
			
			return array(
				'db-version'		=> 0, 
				'basic'				=> $basic,
				'advanced'			=> $advanced
			);
		}
		
		/**
		 * Retrieves all of the settings from the database
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @return array
		 */
		protected static function getSettings()
		{
			$settings = shortcode_atts(
				self::$defaultSettings,
				get_option( WordPressPluginSkeleton::PREFIX . 'settings', array() )
			);
			
			return $settings;
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
		 * Updates settings outside of the Settings API or other subsystems
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $newValues An array of new values to be merged with WPPSSettings::$settings. 
		 */
		public static function updateSettings( $newValues )
		{
			self::$settings	= self::validateFieldValues( $newValues );
			update_option( WordPressPluginSkeleton::PREFIX . 'settings', self::$settings );
		}
		
		
		/*
		 * Plugin Settings
		 */
		
		/**
		 * Adds links to the plugin's action link section on the Plugins page
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $links The links currently mapped to the plugin
		 * @return array
		 */
		public static function addPluginActionLinks( $links )
		{
			array_unshift( $links, '<a href="http://wordpress.org/extend/plugins/wordpress-plugin-skeleton/faq/">Help</a>' );
			array_unshift( $links, '<a href="options-general.php?page='. WordPressPluginSkeleton::PREFIX . 'settings">Settings</a>' );
			
			return $links; 
		}
		
		/**
		 * Adds pages to the Admin Panel menu
		 * @mvc Controller
		 * @author Ian Dunn <ian.dunn@mpangodev.com>
		 */
		public static function registerSettingsPages()
		{
			if( did_action( 'admin_menu' ) !== 1 )
				return;
			
			add_submenu_page(
				'options-general.php',
				WPPS_NAME . ' Settings',
				WPPS_NAME,
				self::REQUIRED_CAPABILITY,
				WordPressPluginSkeleton::PREFIX . 'settings',
				__CLASS__ . '::markupSettingsPage'
			);
		}

		/**
		 * Creates the markup for the Settings page
		 * @mvc Controller
		 * @author Ian Dunn <ian.dunn@mpangodev.com>
		 */
		public static function markupSettingsPage()
		{
			if( current_user_can( self::REQUIRED_CAPABILITY ) )
				require_once( dirname( __DIR__ ) . '/views/wpps-settings/page-settings.php' );
			else
				wp_die( 'Access denied.' );
		}

		/**
		 * Registers settings sections, fields and settings
		 * @mvc Controller
		 * @author Ian Dunn <ian.dunn@mpangodev.com>
		 */
		public static function registerSettings()
		{
			if( did_action( 'admin_init' ) !== 1 )
				return;
			
			/*
			 * Basic Section
			 */
			add_settings_section(
				WordPressPluginSkeleton::PREFIX . 'section-basic',
				'Basic Settings',
				__CLASS__ . '::markupSectionHeaders',
				WordPressPluginSkeleton::PREFIX . 'settings'
			);
			
			add_settings_field(
				WordPressPluginSkeleton::PREFIX . 'field-example1',
				'Example Field 1',
				__CLASS__ . '::markupFields',
				WordPressPluginSkeleton::PREFIX . 'settings',
				WordPressPluginSkeleton::PREFIX . 'section-basic',
				array( 'label_for' => WordPressPluginSkeleton::PREFIX . 'field-example1' )
			);
			
			
			/*
			 * Advanced Section
			 */
			 add_settings_section(
				WordPressPluginSkeleton::PREFIX . 'section-advanced',
				'Advanced Settings',
				__CLASS__ . '::markupSectionHeaders',
				WordPressPluginSkeleton::PREFIX . 'settings'
			);
			
			add_settings_field(
				WordPressPluginSkeleton::PREFIX . 'field-example2',
				'Example Field 2',
				__CLASS__ . '::markupFields',
				WordPressPluginSkeleton::PREFIX . 'settings',
				WordPressPluginSkeleton::PREFIX . 'section-advanced',
				array( 'label_for' => WordPressPluginSkeleton::PREFIX . 'field-example2' )
			);
			
			
			// The settings container
			register_setting(
				WordPressPluginSkeleton::PREFIX . 'settings',
				WordPressPluginSkeleton::PREFIX . 'settings',
				__CLASS__ . '::validateFieldValues'
			);
		}

		/**
		 * Adds the section introduction text to the Settings page
		 * @mvc Controller
		 * @author Ian Dunn <ian.dunn@mpangodev.com>
		 * @param array $section
		 */
		public static function markupSectionHeaders( $section )
		{
			require( dirname( __DIR__ ) . '/views/wpps-settings/page-settings-section-headers.php' );
		}

		/**
		 * Adds the map-width field to the Settings page
		 * @mvc Controller
		 * @author Ian Dunn <ian.dunn@mpangodev.com>
		 * @param array $field
		 */
		public static function markupFields( $field )
		{
			switch( $field[ 'label_for' ] )
			{
				case WordPressPluginSkeleton::PREFIX . 'field-example1':
					// Do any extra processing here
				break;
			}
			
			require( dirname( __DIR__ ) . '/views/wpps-settings/page-settings-fields.php' );
		}

		/**
		 * Validates submitted field values before they get saved to the database
		 * @mvc Model
		 * @author Ian Dunn <ian.dunn@mpangodev.com>
		 * @param array $settings
		 * @return array
		 */
		public static function validateFieldValues( $settings )
		{
			$settings = shortcode_atts( self::$settings, $settings );
			
			
			/*
			 * Basic Settings
			 */
		
			if( $settings[ 'basic' ][ 'field-example1' ] != 'valid data' )
			{
				self::$notices->enqueue( 'Example 1 must have x and y properties.', 'error' );
				$settings[ 'basic' ][ 'field-example1' ] = self::$defaultSettings[ 'basic' ][ 'field-example1' ];
			}
			
			
			/*
			 * Advanced Settings
			 */
			
			$settings[ 'advanced' ][ 'field-example2' ] = absint( $settings[ 'advanced' ][ 'field-example2' ] );
			
			
			return $settings;
		}
		
		
		
		/*
		 * User Settings
		 */
		 
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
			
			require_once( dirname( __DIR__ ) .'/views/wpps-settings/user-fields.php' );
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
				if( get_user_meta( $userID, WordPressPluginSkeleton::PREFIX . 'user-example-field1', true ) != $_POST[ WordPressPluginSkeleton::PREFIX . 'user-example-field1' ] )
					self::$notices->enqueue( 'You do not have permission to change the Example Field 1 for this user.', 'error' );
			}
			else
				update_user_meta( $userID, WordPressPluginSkeleton::PREFIX . 'user-example-field1', $_POST[ WordPressPluginSkeleton::PREFIX . 'user-example-field1' ] );
			
			if( true )
				update_user_meta( $userID, WordPressPluginSkeleton::PREFIX . 'user-example-field2', $_POST[ WordPressPluginSkeleton::PREFIX . 'user-example-field2' ] );
			else
				self::$notices->enqueue( 'Example validation error', 'error' );
		}
	} // end WPPSSettings
}

?>