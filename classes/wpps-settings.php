<?php

if ( ! class_exists( 'WPPS_Settings' ) ) {

	/**
	 * Handles plugin settings and user profile meta fields
	 */
	class WPPS_Settings extends WPPS_Module {
		protected $settings;
		protected static $default_settings;
		protected static $readable_properties  = array( 'settings' );
		protected static $writeable_properties = array( 'settings' );

		const REQUIRED_CAPABILITY = 'administrator';


		/*
		 * General methods
		 */

		/**
		 * Constructor
		 *
		 * @mvc Controller
		 */
		protected function __construct() {
			$this->register_hook_callbacks();
		}

		/**
		 * Public setter for protected variables
		 *
		 * Updates settings outside of the Settings API or other subsystems
		 *
		 * @mvc Controller
		 *
		 * @param string $variable
		 * @param array  $value This will be merged with WPPS_Settings->settings, so it should mimic the structure of the WPPS_Settings::$default_settings. It only needs the contain the values that will change, though. See WordPress_Plugin_Skeleton->upgrade() for an example.
		 */
		public function __set( $variable, $value ) {
			// Note: WPPS_Module::__set() is automatically called before this

			if ( $variable != 'settings' ) {
				return;
			}

			$this->settings = self::validate_settings( $value );
			update_option( 'wpps_settings', $this->settings );
		}

		/**
		 * Register callbacks for actions and filters
		 *
		 * @mvc Controller
		 */
		public function register_hook_callbacks() {
			add_action( 'admin_menu',               __CLASS__ . '::register_settings_pages' );
			add_action( 'show_user_profile',        __CLASS__ . '::add_user_fields' );
			add_action( 'edit_user_profile',        __CLASS__ . '::add_user_fields' );
			add_action( 'personal_options_update',  __CLASS__ . '::save_user_fields' );
			add_action( 'edit_user_profile_update', __CLASS__ . '::save_user_fields' );

			add_action( 'init',                     array( $this, 'init' ) );
			add_action( 'admin_init',               array( $this, 'register_settings' ) );

			add_filter(
				'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) ) . '/bootstrap.php',
				__CLASS__ . '::add_plugin_action_links'
			);
		}

		/**
		 * Prepares site to use the plugin during activation
		 *
		 * @mvc Controller
		 *
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 *
		 * @mvc Controller
		 */
		public function deactivate() {
		}

		/**
		 * Initializes variables
		 *
		 * @mvc Controller
		 */
		public function init() {
			self::$default_settings = self::get_default_settings();
			$this->settings         = self::get_settings();
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
			// Note: __set() calls validate_settings(), so settings are never invalid

			return true;
		}


		/*
		 * Plugin Settings
		 */

		/**
		 * Establishes initial values for all settings
		 *
		 * @mvc Model
		 *
		 * @return array
		 */
		protected static function get_default_settings() {
			$basic = array(
				'field-example1' => ''
			);

			$advanced = array(
				'field-example2' => ''
			);

			return array(
				'db-version' => '0',
				'basic'      => $basic,
				'advanced'   => $advanced
			);
		}

		/**
		 * Retrieves all of the settings from the database
		 *
		 * @mvc Model
		 *
		 * @return array
		 */
		protected static function get_settings() {
			$settings = shortcode_atts(
				self::$default_settings,
				get_option( 'wpps_settings', array() )
			);

			return $settings;
		}

		/**
		 * Adds links to the plugin's action link section on the Plugins page
		 *
		 * @mvc Model
		 *
		 * @param array $links The links currently mapped to the plugin
		 * @return array
		 */
		public static function add_plugin_action_links( $links ) {
			array_unshift( $links, '<a href="http://wordpress.org/extend/plugins/wordpress-plugin-skeleton/faq/">Help</a>' );
			array_unshift( $links, '<a href="options-general.php?page=' . 'wpps_settings">Settings</a>' );

			return $links;
		}

		/**
		 * Adds pages to the Admin Panel menu
		 *
		 * @mvc Controller
		 */
		public static function register_settings_pages() {
			add_submenu_page(
				'options-general.php',
				WPPS_NAME . ' Settings',
				WPPS_NAME,
				self::REQUIRED_CAPABILITY,
				'wpps_settings',
				__CLASS__ . '::markup_settings_page'
			);
		}

		/**
		 * Creates the markup for the Settings page
		 *
		 * @mvc Controller
		 */
		public static function markup_settings_page() {
			if ( current_user_can( self::REQUIRED_CAPABILITY ) ) {
				echo self::render_template( 'wpps-settings/page-settings.php' );
			} else {
				wp_die( 'Access denied.' );
			}
		}

		/**
		 * Registers settings sections, fields and settings
		 *
		 * @mvc Controller
		 */
		public function register_settings() {
			/*
			 * Basic Section
			 */
			add_settings_section(
				'wpps_section-basic',
				'Basic Settings',
				__CLASS__ . '::markup_section_headers',
				'wpps_settings'
			);

			add_settings_field(
				'wpps_field-example1',
				'Example Field 1',
				array( $this, 'markup_fields' ),
				'wpps_settings',
				'wpps_section-basic',
				array( 'label_for' => 'wpps_field-example1' )
			);


			/*
			 * Advanced Section
			 */
			add_settings_section(
				'wpps_section-advanced',
				'Advanced Settings',
				__CLASS__ . '::markup_section_headers',
				'wpps_settings'
			);

			add_settings_field(
				'wpps_field-example2',
				'Example Field 2',
				array( $this, 'markup_fields' ),
				'wpps_settings',
				'wpps_section-advanced',
				array( 'label_for' => 'wpps_field-example2' )
			);


			// The settings container
			register_setting(
				'wpps_settings',
				'wpps_settings',
				array( $this, 'validate_settings' )
			);
		}

		/**
		 * Adds the section introduction text to the Settings page
		 *
		 * @mvc Controller
		 *
		 * @param array $section
		 */
		public static function markup_section_headers( $section ) {
			echo self::render_template( 'wpps-settings/page-settings-section-headers.php', array( 'section' => $section ), 'always' );
		}

		/**
		 * Delivers the markup for settings fields
		 *
		 * @mvc Controller
		 *
		 * @param array $field
		 */
		public function markup_fields( $field ) {
			switch ( $field['label_for'] ) {
				case 'wpps_field-example1':
					// Do any extra processing here
					break;
			}

			echo self::render_template( 'wpps-settings/page-settings-fields.php', array( 'settings' => $this->settings, 'field' => $field ), 'always' );
		}

		/**
		 * Validates submitted setting values before they get saved to the database. Invalid data will be overwritten with defaults.
		 *
		 * @mvc Model
		 *
		 * @param array $new_settings
		 * @return array
		 */
		public function validate_settings( $new_settings ) {
			$new_settings = shortcode_atts( $this->settings, $new_settings );

			if ( ! is_string( $new_settings['db-version'] ) ) {
				$new_settings['db-version'] = WordPress_Plugin_Skeleton::VERSION;
			}


			/*
			 * Basic Settings
			 */

			if ( strcmp( $new_settings['basic']['field-example1'], 'valid data' ) !== 0 ) {
				add_notice( 'Example 1 must equal "valid data"', 'error' );
				$new_settings['basic']['field-example1'] = self::$default_settings['basic']['field-example1'];
			}


			/*
			 * Advanced Settings
			 */

			$new_settings['advanced']['field-example2'] = absint( $new_settings['advanced']['field-example2'] );


			return $new_settings;
		}


		/*
		 * User Settings
		 */

		/**
		 * Adds extra option fields to a user's profile
		 *
		 * @mvc Controller
		 *
		 * @param object
		 */
		public static function add_user_fields( $user ) {
			echo self::render_template( 'wpps-settings/user-fields.php', array( 'user' => $user ) );
		}

		/**
		 * Validates and saves the values of extra user fields to the database
		 *
		 * @mvc Controller
		 *
		 * @param int $user_id
		 */
		public static function save_user_fields( $user_id ) {
			$user_fields = self::validate_user_fields( $user_id, $_POST );

			update_user_meta( $user_id, 'wpps_user-example-field1', $user_fields[ 'wpps_user-example-field1' ] );
			update_user_meta( $user_id, 'wpps_user-example-field2', $user_fields[ 'wpps_user-example-field2' ] );
		}

		/**
		 * Validates submitted user field values before they get saved to the database
		 *
		 * @mvc Model
		 *
		 * @param int   $user_id
		 * @param array $user_fields
		 * @return array
		 */
		public static function validate_user_fields( $user_id, $user_fields ) {
			if ( $user_fields[ 'wpps_user-example-field1' ] == false ) {
				$user_fields[ 'wpps_user-example-field1' ] = true;
				add_notice( 'Example Field 1 should be true', 'error' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				$current_field2 = get_user_meta( $user_id, 'wpps_user-example-field2', true );

				if ( $current_field2 != $user_fields[ 'wpps_user-example-field2' ] ) {
					$user_fields[ 'wpps_user-example-field2' ] = $current_field2;
					add_notice( 'Only administrators can change Example Field 2.', 'error' );
				}
			}

			return $user_fields;
		}
	} // end WPPS_Settings
}
