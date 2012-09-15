<?php

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'WPPSCustomPostType' ) )
{
	/**
	 * Creates a custom post type and associates taxonomies
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
		public static function registerHookCallbacks()
		{
			// NOTE: Make sure you update the did_action() parameter in the corresponding callback method when changing the hooks here
			add_action( 'init',			__CLASS__ . '::init' );
			add_action( 'init',			__CLASS__ . '::createPostType' );
			add_action( 'init',			__CLASS__ . '::createTaxonomies' );
			add_action( 'admin_init',	__CLASS__ . '::addMetaBoxes' );
			add_action( 'save_post',	__CLASS__ . '::savePost', 10, 2 );
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
					'supports'			=> array( 'title', 'editor', 'author', 'thumbnail', 'revisions' )
				);

				$postType = register_post_type(
					self::POST_TYPE_SLUG,
					apply_filters( WordPressPluginSkeleton::PREFIX . 'post-type-params', $postTypeParams )
				);
				
				if( is_wp_error( $postType ) )
					self::$notices->enqueue( __METHOD__ . ' error: '. $postType->get_error_message(), 'error' );
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
		
		/**
		 * Adds meta boxes for the custom post type
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function addMetaBoxes()
		{
			if( did_action( 'admin_init' ) !== 1 )
				return;

			add_meta_box(
				WordPressPluginSkeleton::PREFIX . 'example-box',
				'Example Box',
				__CLASS__ . '::markupMetaBoxes',
				self::POST_TYPE_SLUG,
				'normal',
				'core'
			);
		}

		/**
		 * Builds the markup for all meta boxes
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param object $post
		 * @param array $box
		 */
		public static function markupMetaBoxes( $post, $box )
		{
			switch( $box[ 'id' ] )
			{
				case WordPressPluginSkeleton::PREFIX . 'example-box':
					$exampleBoxField = get_post_meta( $post->ID, WordPressPluginSkeleton::PREFIX . 'example-box-field', true );
					require_once( dirname( __DIR__ ) . '/views/wpps-custom-post-type/metabox-example-box.php' );
				break;
				
				/*
				case WordPressPluginSkeleton::PREFIX . 'some-other-box':
					$someOtherField = get_post_meta( $post->ID, WordPressPluginSkeleton::PREFIX . 'some-other-field', true );
					require_once( dirname( __DIR__ ) . '/views/wpps-custom-post-type/metabox-some-other-box.php' );
				break;
				*/
			}
		}

		/**
		 * Saves values of the the custom post type's extra fields
		 * @mvc Controller
		 * @param int $postID
		 * @param object $post
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function savePost( $postID, $revision )
		{
			global $post;
			
			if( did_action( 'save_post' ) !== 1 )
				return;

			if( isset( $_GET[ 'action' ] ) && ( $_GET[ 'action' ] == 'trash' || $_GET[ 'action' ] == 'untrash' ) )
				return;

			if(	!$post || $post->post_type != self::POST_TYPE_SLUG || !current_user_can( 'edit_posts', $postID ) )
				return;

			if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft' )
				return;

			self::saveCustomFields( $postID, $_POST );
		}

		/**
		 * Validates and saves values of the the custom post type's extra fields
		 * @mvc Model
		 * @param int $postID
		 * @param array $newValues
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		protected static function saveCustomFields( $postID, $newValues )
		{
			if( true )
				update_post_meta( $postID, WordPressPluginSkeleton::PREFIX . 'example-box-field', $newValues[ WordPressPluginSkeleton::PREFIX . 'example-box-field' ] );
			else
				self::$notices->enqueue( 'Example of failing validation', 'error' );
		}
	} // end WPPSCustomPostType
}

?>