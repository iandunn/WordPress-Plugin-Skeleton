<?php

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

if ( ! interface_exists( 'WPPSCustomPostType' ) ) {
	/**
	 * Defines interface for custom post type classes
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	interface WPPSCustomPostType {
		/**
		 * Registers the custom post type
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function create_post_type();

		/**
		 * Registers the category taxonomy
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function create_taxonomies();

		/**
		 * Adds meta boxes for the custom post type
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function add_meta_boxes();

		/**
		 * Builds the markup for all meta boxes
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 *
		 * @param object $post
		 * @param array  $box
		 */
		public static function markup_meta_boxes( $post, $box );

		/**
		 * Saves values of the the custom post type's extra fields
		 * @mvc Controller
		 *
		 * @param int    $post_id
		 * @param object $post
		 *
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function save_post( $post_id, $revision );
	} // end WPPSCustomPostType
}