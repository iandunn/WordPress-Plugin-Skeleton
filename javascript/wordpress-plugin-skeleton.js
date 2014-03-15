/**
 * Wrapper function to safely use $
 */
function wppsWrapper( $ ) {
	var wpps = {

		/**
		 * Main entry point
		 */
		init: function () {
			wpps.prefix      = 'wpps_';
			wpps.templateURL = $( '#template-url' ).val();
			wpps.ajaxPostURL = $( '#ajax-post-url' ).val();

			wpps.registerEventHandlers();
		},

		/**
		 * Registers event handlers
		 */
		registerEventHandlers: function () {
			$( '#example-container' ).children( 'a' ).click( wpps.exampleHandler );
		},

		/**
		 * Example event handler
		 *
		 * @param object event
		 */
		exampleHandler: function ( event ) {
			alert( $( this ).attr( 'href' ) );

			event.preventDefault();
		}
	}; // end wpps

	$( document ).ready( wpps.init );

} // end wppsWrapper()

wppsWrapper( jQuery );
