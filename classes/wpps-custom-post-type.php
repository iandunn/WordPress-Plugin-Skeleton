<?php

if ( ! interface_exists( 'WPPS_Custom_Post_Type' ) ) {

	/**
	 * Defines interface for custom post type classes
	 */
	interface WPPS_Custom_Post_Type {

		/**
		 * Registers the custom post type
		 *
		 * @mvc Controller
		 */
		public static function create_post_type();

		/**
		 * Registers the category taxonomy
		 *
		 * @mvc Controller
		 */
		public static function create_taxonomies();

		/**
		 * Adds meta boxes for the custom post type
		 *
		 * @mvc Controller
		 */
		public static function add_meta_boxes();

		/**
		 * Builds the markup for all meta boxes
		 *
		 * @mvc Controller
		 *
		 * @param object $post
		 * @param array  $box
		 */
		public static function markup_meta_boxes( $post, $box );

		/**
		 * Saves values of the the custom post type's extra fields
		 *
		 * @mvc Controller
		 *
		 * @param int    $post_id
		 * @param object $post
		 */
		public static function save_post( $post_id, $revision );

		/**
		 * Determines whether a meta key should be considered public or not
		 *
		 * @param bool   $protected
		 * @param string $meta_key
		 * @param mixed  $meta_type
		 * @return bool
		 */
		public static function is_protected_meta( $protected, $meta_key, $meta_type );
	} // end WPPS_Custom_Post_Type
}
