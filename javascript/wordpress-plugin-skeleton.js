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
			wpps.templateURL = $( '#templateURL' ).val(); 
			wpps.ajaxPostURL = $( '#ajaxPostURL' ).val();
		}
	}; // end wpps
	
	$( document ).ready( wpps.init );
	
} // end wpps_wrapper()

wpps_wrapper( jQuery );