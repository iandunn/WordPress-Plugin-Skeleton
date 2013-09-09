<?php

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'WPPSCPTExample' ) ) {
	/**
	 * Creates a custom post type and associated taxonomies
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	class WPPSCPTExample extends WPPSModule implements WPPSCustomPostType {
		protected static $readable_properties  = array();
		protected static $writeable_properties = array();

		const POST_TYPE_NAME = 'WPPS Custom Post Type';
		const POST_TYPE_SLUG = 'wpps-cpt';
		const TAG_NAME       = 'WPPS Custom Taxonomy';
		const TAG_SLUG       = 'wpps-custom-tax';


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
		 * Registers the custom post type
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function create_post_type() {
			if ( did_action( 'init' ) !== 1 ) {
				return;
			}

			if ( ! post_type_exists( self::POST_TYPE_SLUG ) ) {
				$post_type_params = self::get_post_type_params();
				$post_type        = register_post_type( self::POST_TYPE_SLUG, $post_type_params );

				if ( is_wp_error( $post_type ) ) {
					WordPressPluginSkeleton::$notices->enqueue( __METHOD__ . ' error: ' . $post_type->get_error_message(), 'error' );
				}
			}
		}

		/**
		 * Defines the parameters for the custom post type
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @return array
		 */
		protected static function get_post_type_params() {
			$labels = array(
				'name'               => self::POST_TYPE_NAME . 's',
				'singular_name'      => self::POST_TYPE_NAME,
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New ' . self::POST_TYPE_NAME,
				'edit'               => 'Edit',
				'edit_item'          => 'Edit ' .    self::POST_TYPE_NAME,
				'new_item'           => 'New ' .     self::POST_TYPE_NAME,
				'view'               => 'View ' .    self::POST_TYPE_NAME . 's',
				'view_item'          => 'View ' .    self::POST_TYPE_NAME,
				'search_items'       => 'Search ' .  self::POST_TYPE_NAME . 's',
				'not_found'          => 'No ' .      self::POST_TYPE_NAME . 's found',
				'not_found_in_trash' => 'No ' .      self::POST_TYPE_NAME . 's found in Trash',
				'parent'             => 'Parent ' .  self::POST_TYPE_NAME
			);

			$post_type_params = array(
				'labels'          => $labels,
				'singular_label'  => self::POST_TYPE_NAME,
				'public'          => true,
				'menu_position'   => 20,
				'hierarchical'    => true,
				'capability_type' => 'post',
				'has_archive'     => true,
				'rewrite'         => array( 'slug' => self::POST_TYPE_SLUG, 'with_front' => false ),
				'query_var'       => true,
				'supports'        => array( 'title', 'editor', 'author', 'thumbnail', 'revisions' )
			);

			return apply_filters( WordPressPluginSkeleton::PREFIX . 'post-type-params', $post_type_params );
		}

		/**
		 * Registers the category taxonomy
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function create_taxonomies() {
			if ( did_action( 'init' ) !== 1 ) {
				return;
			}

			if ( ! taxonomy_exists( self::TAG_SLUG ) ) {
				$tag_taxonomy_params = self::get_tag_taxonomy_params();
				register_taxonomy( self::TAG_SLUG, self::POST_TYPE_SLUG, $tag_taxonomy_params );
			}
		}

		/**
		 * Defines the parameters for the custom taxonomy
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @return array
		 */
		protected static function get_tag_taxonomy_params() {
			$tag_taxonomy_params = array(
				'label'                 => self::TAG_NAME,
				'labels'                => array( 'name' => self::TAG_NAME, 'singular_name' => self::TAG_NAME ),
				'hierarchical'          => true,
				'rewrite'               => array( 'slug' => self::TAG_SLUG ),
				'update_count_callback' => '_update_post_term_count'
			);

			return apply_filters( WordPressPluginSkeleton::PREFIX . 'tag-taxonomy-params', $tag_taxonomy_params );
		}

		/**
		 * Adds meta boxes for the custom post type
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function add_meta_boxes() {
			if ( did_action( 'admin_init' ) !== 1 )
				return;

			add_meta_box(
				WordPressPluginSkeleton::PREFIX . 'example-box',
				'Example Box',
				__CLASS__ . '::markup_meta_boxes',
				self::POST_TYPE_SLUG,
				'normal',
				'core'
			);
		}

		/**
		 * Builds the markup for all meta boxes
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param object $post
		 * @param array  $box
		 */
		public static function markup_meta_boxes( $post, $box ) {
			switch ( $box['id'] ) {
				case WordPressPluginSkeleton::PREFIX . 'example-box':
					$exampleBoxField = get_post_meta( $post->ID, WordPressPluginSkeleton::PREFIX . 'example-box-field', true );
					$view            = 'wpps-cpt-example/metabox-example-box.php';
					break;

				/*
				case WordPressPluginSkeleton::PREFIX . 'some-other-box':
					$someOtherField = get_post_meta( $post->ID, WordPressPluginSkeleton::PREFIX . 'some-other-field', true );
				 	$view           = 'wpps-cpt-example/metabox-another-box.php';
				break;
				*/
			}

			$view = dirname( __DIR__ ) . '/views/' . $view;
			if ( is_file( $view ) ) {
				require_once( $view );
			} else {
				echo __METHOD__ . " error: " . $view . " doesn't exist.";
			}
		}

		/**
		 * Saves values of the the custom post type's extra fields
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param int    $post_id
		 * @param object $post
		 */
		public static function save_post( $post_id, $revision ) {
			global $post;
			$ignored_actions = array( 'trash', 'untrash', 'restore' );

			if ( did_action( 'save_post' ) !== 1 ) {
				return;
			}

			if ( isset( $_GET['action'] ) && in_array( $_GET['action'], $ignored_actions ) ) {
				return;
			}

			if ( ! $post || $post->post_type != self::POST_TYPE_SLUG || ! current_user_can( 'edit_posts', $post_id ) ) {
				return;
			}

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft' ) {
				return;
			}

			self::save_custom_fields( $post_id, $_POST );
		}

		/**
		 * Validates and saves values of the the custom post type's extra fields
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param int   $post_id
		 * @param array $new_values
		 */
		protected static function save_custom_fields( $post_id, $new_values ) {
			if ( isset( $new_values[ WordPressPluginSkeleton::PREFIX . 'example-box-field' ] ) ) {
				if ( true ) { // some business logic check
					update_post_meta( $post_id, WordPressPluginSkeleton::PREFIX . 'example-box-field', $new_values[ WordPressPluginSkeleton::PREFIX . 'example-box-field' ] );
				} else {
					WordPressPluginSkeleton::$notices->enqueue( 'Example of failing validation', 'error' );
				}
			}
		}

		/**
		 * Defines the [wpps-cpt-shortcode] shortcode
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param array $attributes
		 * return string
		 */
		public static function cpt_shortcode_example( $attributes ) {
			$attributes = apply_filters( WordPressPluginSkeleton::PREFIX . 'cpt-shortcode-example-attributes', $attributes );
			$attributes = self::validate_cpt_shortcode_example_attributes( $attributes );

			ob_start();
			require_once( dirname( __DIR__ ) . '/views/wpps-cpt-example/shortcode-cpt-shortcode-example.php' );
			$output = ob_get_clean();

			return apply_filters( WordPressPluginSkeleton::PREFIX . 'cpt-shortcode-example', $output );
		}

		/**
		 * Validates the attributes for the [cpt-shortcode-example] shortcode
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param array $attributes
		 * return array
		 */
		protected static function validate_cpt_shortcode_example_attributes( $attributes ) {
			$defaults   = self::get_default_cpt_shortcode_example_attributes();
			$attributes = shortcode_atts( $defaults, $attributes );

			if ( $attributes['foo'] != 'valid data' )
				$attributes['foo'] = $defaults['foo'];

			return apply_filters( WordPressPluginSkeleton::PREFIX . 'validate-cpt-shortcode-example-attributes', $attributes );
		}

		/**
		 * Defines the default arguments for the [cpt-shortcode-example] shortcode
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @return array
		 */
		protected static function get_default_cpt_shortcode_example_attributes() {
			$attributes = array(
				'foo' => 'bar',
				'bar' => 'foo'
			);

			return apply_filters( WordPressPluginSkeleton::PREFIX . 'default-cpt-shortcode-example-attributes', $attributes );
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
			add_action( 'init',                     __CLASS__ . '::create_post_type' );
			add_action( 'init',                     __CLASS__ . '::create_taxonomies' );
			add_action( 'admin_init',               __CLASS__ . '::add_meta_boxes' );
			add_action( 'save_post',                __CLASS__ . '::save_post', 10, 2 );

			add_action( 'init',                     array( $this, 'init' ) );

			add_shortcode( 'cpt-shortcode-example', __CLASS__ . '::cpt_shortcode_example' );
		}

		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {
			self::create_post_type();
			self::create_taxonomies();
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function deactivate() {
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
		 *
		 * @return bool
		 */
		protected function is_valid( $property = 'all' ) {
			return true;
		}
	} // end WPPSCPTExample
}

?>