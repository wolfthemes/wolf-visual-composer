/*!
 * Accordion
 *
 * WPBakery Page Builder Extension 3.2.8
 */
/* jshint -W062 */

var WVCAccordion = function( $ ) {

	'use strict';

	return {

		/**
		 * Init UI
		 */
		init : function () {
			$( '.wvc-accordion' ).each( function() {

				var openPanel = 0,
					collapsible = false;

				if ( $( this ).data( 'active-tab' ) ) {
					openPanel = $( this ).data( 'active-tab' ) - 1;
				}

				//console.log( openPanel );

				if ( '0' == $( this ).data( 'active-tab' ) ) {
					openPanel = false;
					collapsible = true;
				}
				
				var options = {
					autoHeight: true,
					heightStyle: 'content',
					active: openPanel,
					collapsible: collapsible
				};

				//console.log( options );

				$( this ).accordion( options );
			} );
		}
	};

}( jQuery );

( function( $ ) {

	'use strict';

	$( document ).ready( function() {
		WVCAccordion.init();
	} );

} )( jQuery );