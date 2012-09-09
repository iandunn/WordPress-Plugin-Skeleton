/**
 * @package WordPress Plugin Skeleton
 * @author Ian Dunn <ian@iandunn.name>
 */


/**
 * Wrapper function to safely use $
 * @author Ian Dunn <ian@iandunn.name>
 */
function wpps_wrapper( $ )
{
	var wpps = 
	{
		/**
		 * Main entry point
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		init : function()
		{
			wpps.prefix			= 'wpps_';
			wpps.templateURL	= $( '#templateURL' ).val(); 
			wpps.ajaxPostURL	= $( '#ajaxPostURL' ).val();
			
			wpps.registerEventHandlers();
		},
		
		/**
		 * Registers event handlers
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		registerEventHandlers : function()
		{
			$( '#example-container' ).children( 'a' ).click( wpps.exampleHandler );
		},
		
		/**
		 * Example event handler
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param object event
		 */
		exampleHandler : function( event )
		{
			event.preventDefault();
			
			alert( $( this ).attr( 'href' ) );
		}
	}; // end wpps
	
	$( document ).ready( wpps.init );
	
} // end wpps_wrapper()

wpps_wrapper( jQuery );