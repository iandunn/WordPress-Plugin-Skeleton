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
	class WPPSSettings extends WPPSModule
	{
		protected $settings;
		protected static $defaultSettings;
		protected static $readableProperties	= array( 'settings' );
		protected static $writeableProperties	= array( 'settings' );
		const REQUIRED_CAPABILITY = 'administrator';
		
		
		/*
		 * General methods
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
		
		/**
		 * Public setter for protected variables
		 * Updates settings outside of the Settings API or other subsystems
		 * 
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $variable
		 * @param array $value This will be merged with WPPSSettings->settings, so it should mimic the structure of the WPPSSettings::$defaultSettings. It only needs the contain the values that will change, though. See WordPressPluginSkeleton->upgrade() for an example.
		 */
		public function __set( $variable, $value )
		{
			// Note: WPPSModule::__set() is automatically called before this
			
			if( $variable != 'settings' )
				return;
			
			$this->settings	= self::validateSettings( $value );
			update_option( WordPressPluginSkeleton::PREFIX . 'settings', $this->settings );
		}
		
		/**
		 * Register callbacks for actions and filters
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function registerHookCallbacks()
		{
			// NOTE: Make sure you update the did_action() parameter in the corresponding callback method when changing the hooks here
			add_action( 'admin_menu',					__CLASS__ . '::registerSettingsPages' );
			add_action( 'show_user_profile',			__CLASS__ . '::addUserFields' );
			add_action( 'edit_user_profile',			__CLASS__ . '::addUserFields' );
			add_action( 'personal_options_update',		__CLASS__ . '::saveUserFields' );
			add_action( 'edit_user_profile_update',		__CLASS__ . '::saveUserFields' );
			
			add_action( 'init',							array( $this, 'init' ) );
			add_action( 'admin_init',					array( $this, 'registerSettings' ) );
			
			add_filter(
				'plugin_action_links_'. plugin_basename( dirname( __DIR__ ) ) .'/bootstrap.php',
				__CLASS__ . '::addPluginActionLinks'
			);
		}
				
		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param bool $networkWide
		 */
		public function activate( $networkWide )
		{
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function deactivate()
		{
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
			
			self::$defaultSettings = self::getDefaultSettings();
			$this->settings = self::getSettings();
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
			// Note: __set() calls validateSettings(), so settings are never invalid
			
			return true;
		}
		
		
		/*
		 * Plugin Settings
		 */
		
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
				'db-version'		=> '0', 
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
		 * @author Ian Dunn <ian@iandunn.name>
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
		 * @author Ian Dunn <ian@iandunn.name>
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
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function registerSettings()
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
				array( $this, 'markupFields' ),
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
				array( $this, 'markupFields' ),
				WordPressPluginSkeleton::PREFIX . 'settings',
				WordPressPluginSkeleton::PREFIX . 'section-advanced',
				array( 'label_for' => WordPressPluginSkeleton::PREFIX . 'field-example2' )
			);
			
			
			// The settings container
			register_setting(
				WordPressPluginSkeleton::PREFIX . 'settings',
				WordPressPluginSkeleton::PREFIX . 'settings',
				array( $this, 'validateSettings' )
			);
		}

		/**
		 * Adds the section introduction text to the Settings page
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $section
		 */
		public static function markupSectionHeaders( $section )
		{
			require( dirname( __DIR__ ) . '/views/wpps-settings/page-settings-section-headers.php' );
		}

		/**
		 * Adds the map-width field to the Settings page
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $field
		 */
		public function markupFields( $field )
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
		 * Validates submitted setting values before they get saved to the database. Invalid data will be overwritten with defaults.
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $newSettings
		 * @return array
		 */
		public function validateSettings( $newSettings )
		{
			$newSettings = shortcode_atts( $this->settings, $newSettings );
			
			if( !is_string( $newSettings[ 'db-version' ] ) )
				$newSettings[ 'db-version' ] = WordPressPluginSkeleton::VERSION;
			
			
			/*
			 * Basic Settings
			 */
		
			if( strcmp( $newSettings[ 'basic' ][ 'field-example1' ], 'valid data' ) !== 0 )
			{
				WordPressPluginSkeleton::$notices->enqueue( 'Example 1 must equal "valid data"', 'error' );
				$newSettings[ 'basic' ][ 'field-example1' ] = self::$defaultSettings[ 'basic' ][ 'field-example1' ];
			}
			
			
			/*
			 * Advanced Settings
			 */
			
			$newSettings[ 'advanced' ][ 'field-example2' ] = absint( $newSettings[ 'advanced' ][ 'field-example2' ] );
			
			
			return $newSettings;
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
			
			$userFields = self::validateUserFields( $userID, $_POST );
			
			update_user_meta( $userID, WordPressPluginSkeleton::PREFIX . 'user-example-field1', $userFields[ WordPressPluginSkeleton::PREFIX . 'user-example-field1' ] );
			update_user_meta( $userID, WordPressPluginSkeleton::PREFIX . 'user-example-field2', $userFields[ WordPressPluginSkeleton::PREFIX . 'user-example-field2' ] );
		}
		
		/**
		 * Validates submitted user field values before they get saved to the database
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param int $userID
		 * @param array $userFields
		 * @return array
		 */
		public static function validateUserFields( $userID, $userFields )
		{
			if( $userFields[ WordPressPluginSkeleton::PREFIX . 'user-example-field1' ] == false )
			{
				$userFields[ WordPressPluginSkeleton::PREFIX . 'user-example-field1' ] = true;
				WordPressPluginSkeleton::$notices->enqueue( 'Example Field 1 should be true', 'error' );
			}
			
			if( !current_user_can( 'manage_options' ) )
			{
				$currentField2 = get_user_meta( $userID, WordPressPluginSkeleton::PREFIX . 'user-example-field2', true );
				
				if( $currentField2 != $userFields[ WordPressPluginSkeleton::PREFIX . 'user-example-field2' ] )
				{
					$userFields[ WordPressPluginSkeleton::PREFIX . 'user-example-field2' ] = $currentField2;
					WordPressPluginSkeleton::$notices->enqueue( 'Only administrators can change Example Field 2.', 'error' );
				}
			}
			
			return $userFields;
		}
	} // end WPPSSettings
}

?>