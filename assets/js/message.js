/*!
 * Message
 *
 * WPBakery Page Builder Extension 3.2.8 
 */
/* jshint -W062 */

var WVCMessage = function( $ ) {

	'use strict';

	return {

		/**
		 * Init UI
		 */
		init : function () {
			$( '.wvc-notification-close' ).on( 'click', function() {
				$( this ).parents( '.wvc-notification' ).slideUp();
			} );
		}
	};

}( jQuery );

( function( $ ) {

	'use strict';

	$( document ).ready( function() {
		WVCMessage.init();
	} );

} )( jQuery );