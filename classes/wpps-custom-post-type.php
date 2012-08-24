<?php

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'WPPSCustomPostType' ) )
{
	/**
	 * Creates a custom post type and a custom taxonomy
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	class WPPSCustomPostType
	{
		// Declare variables and constants
		protected static $notices;
		const POST_TYPE_NAME	= 'WPPS Custom Post Type';
		const POST_TYPE_SLUG	= 'wpps-cpt';
		const TAG_NAME			= 'WPPS Custom Taxnomy';
		const TAG_SLUG			= 'wpps-custom-tax';

		/**
		 * Constructor
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function registerCallbacks()
		{
			// NOTE: Make sure you update the did_action() parameter in the corresponding callback method when changing the hooks here
			add_action( 'init',		__CLASS__ . '::init' );
			add_action( 'init',		__CLASS__ . '::createPostType' );
			add_action( 'init',		__CLASS__ . '::createTaxonomies' );	
		}
		
		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function activate()
		{
			self::createPostType();
			self::createTaxonomies();
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
		 * Registers the custom post type
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function createPostType()
		{
			if( did_action( 'init' ) !== 1 )
				return;

			if( !post_type_exists( self::POST_TYPE_SLUG ) )
			{
				$labels = array
				(
					'name'					=> self::POST_TYPE_NAME . 's',
					'singular_name'			=> self::POST_TYPE_NAME,
					'add_new'				=> 'Add New',
					'add_new_item'			=> 'Add New '. self::POST_TYPE_NAME,
					'edit'					=> 'Edit',
					'edit_item'				=> 'Edit '. self::POST_TYPE_NAME,
					'new_item'				=> 'New '. self::POST_TYPE_NAME,
					'view'					=> 'View '. self::POST_TYPE_NAME . 's',
					'view_item'				=> 'View '. self::POST_TYPE_NAME,
					'search_items'			=> 'Search '. self::POST_TYPE_NAME . 's',
					'not_found'				=> 'No '. self::POST_TYPE_NAME .'s found',
					'not_found_in_trash'	=> 'No '. self::POST_TYPE_NAME .'s found in Trash',
					'parent'				=> 'Parent '. self::POST_TYPE_NAME
				);

				$postTypeParams = array(
					'labels'			=> $labels,
					'singular_label'	=> self::POST_TYPE_NAME,
					'public'			=> true,
					'menu_position'		=> 20,
					'hierarchical'		=> true,
					'capability_type'	=> 'post',
					'has_archive'		=> true,
					'rewrite'			=> array( 'slug' => self::POST_TYPE_SLUG, 'with_front' => false ),
					'query_var'			=> true,
					'supports'			=> array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'revisions' )
				);

				$postType = register_post_type(
					self::POST_TYPE_SLUG,
					apply_filters( WordPressPluginSkeleton::PREFIX . 'post-type-params', $postTypeParams )
				);
				
				if( is_wp_error( $postType ) )
					self::$notices->mEnqueue( __METHOD__ . ' error: '. $postType->get_error_message(), 'error' );
			}
		}

		/**
		 * Registers the category taxonomy
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function createTaxonomies()
		{
			if( did_action( 'init' ) !== 1 )
				return;

			if( !taxonomy_exists( self::TAG_SLUG ) )
			{
				$taxParams = array(
					'label'					=> self::TAG_NAME,
					'labels'				=> array( 'name' => self::TAG_NAME, 'singular_name' => self::TAG_NAME ),
					'hierarchical'			=> true,
					'rewrite'				=> array( 'slug' => self::TAG_SLUG ),
					'update_count_callback'	=> '_update_post_term_count'
				);

				register_taxonomy(
					self::TAG_SLUG,
					self::POST_TYPE_SLUG,
					apply_filters( WordPressPluginSkeleton::PREFIX . 'tag-taxonomy-params', $taxParams )
				);
			}
		}
	} // end WPPSCustomPostType
}

?>