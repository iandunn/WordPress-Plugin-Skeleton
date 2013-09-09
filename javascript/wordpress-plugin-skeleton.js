/**
 * @package WordPress Plugin Skeleton
 * @author Ian Dunn <ian@iandunn.name>
 */


/**
 * Wrapper function to safely use $
 * @author Ian Dunn <ian@iandunn.name>
 */
function wppsWrapper( $ ) {
	var wpps = {
		/**
		 * Main entry point
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		init: function () {
			wpps.prefix = 'wpps_';
			wpps.templateURL = $( '#template-url' ).val();
			wpps.ajaxPostURL = $( '#ajax-post-url' ).val();

			wpps.registerEventHandlers();
		},

		/**
		 * Registers event handlers
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		registerEventHandlers: function () {
			$( '#example-container' ).children( 'a' ).click( wpps.exampleHandler );
		},

		/**
		 * Example event handler
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param object event
		 */
		exampleHandler: function ( event ) {
			alert( $( this ).attr( 'href' ) );

			event.preventDefault();
		}
	}; // end wpps

	$( document ).ready( wpps.init );

} // end wpps_wrapper()

wppsWrapper( jQuery );