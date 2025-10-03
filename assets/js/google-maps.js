/*!
 * Gmaps
 *
 * WPBakery Page Builder Extension 3.2.8
 */
/* jshint -W062 */

/* global google, WVCParams */
var WVCGoogleMaps = function( $ ) {

	'use strict';

	return {

		/**
		 * Init UI
		 */
		init : function () {

			if ( ! WVCParams.googleMapApiKey ) {
				return;
			}

			var _this = this;

			$( '.wvc-google-maps' ).each( function() {
				_this.loadMap( this );
			} );

			//this.scrollFix();
		},

		/**
		 * loadMap
		 */
		loadMap : function( element ) {
			var map,
				$map = $( element ),
				//mapId = $map.attr( 'id' ),
				locations = $map.data( 'locations' ),
				skin = $map.data( 'map-skin' ) || 'standard',
				customSkin = $map.data( 'custom-map-skin' ),
				zoom = $map.data( 'zoom' ) || 14,
				markerIcon = $map.data( 'marker-icon' ) || null,
				style,
				mapOptions = {},
				location,
				marker, i,
				markerColor = $map.data( 'marker-color' ) || 'F7584C',
				infowindow,
				customIcon;

			if ( 'standard' === skin ) {

				style = [];

			} else if ( 'silver' === skin ) {

				style = [ { 'elementType': 'geometry', 'stylers': [ { 'color': '#f5f5f5' } ] }, { 'elementType': 'labels.icon', 'stylers': [ { 'visibility': 'off' } ] }, { 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#616161' } ] }, { 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#f5f5f5' } ] }, { 'featureType': 'administrative.land_parcel', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#bdbdbd' } ] }, { 'featureType': 'poi', 'elementType': 'geometry', 'stylers': [ { 'color': '#eeeeee' } ] }, { 'featureType': 'poi', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#757575' } ] }, { 'featureType': 'poi.park', 'elementType': 'geometry', 'stylers': [ { 'color': '#e5e5e5' } ] }, { 'featureType': 'poi.park', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#9e9e9e' } ] }, { 'featureType': 'road', 'elementType': 'geometry', 'stylers': [ { 'color': '#ffffff' } ] }, { 'featureType': 'road.arterial', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#757575' } ] }, { 'featureType': 'road.highway', 'elementType': 'geometry', 'stylers': [ { 'color': '#dadada' } ] }, { 'featureType': 'road.highway', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#616161' } ] }, { 'featureType': 'road.local', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#9e9e9e' } ] }, { 'featureType': 'transit.line', 'elementType': 'geometry', 'stylers': [ { 'color': '#e5e5e5' } ] }, { 'featureType': 'transit.station', 'elementType': 'geometry', 'stylers': [ { 'color': '#eeeeee' } ] }, { 'featureType': 'water', 'elementType': 'geometry', 'stylers': [ { 'color': '#c9c9c9' } ] }, { 'featureType': 'water', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#9e9e9e' } ] }];

			} else if ( 'retro' === skin ) {

				style = [ { 'elementType': 'geometry', 'stylers': [ { 'color': '#ebe3cd' } ] }, { 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#523735' } ] }, { 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#f5f1e6' } ] }, { 'featureType': 'administrative', 'elementType': 'geometry.stroke', 'stylers': [ { 'color': '#c9b2a6' } ] }, { 'featureType': 'administrative.land_parcel', 'elementType': 'geometry.stroke', 'stylers': [ { 'color': '#dcd2be' } ] }, { 'featureType': 'administrative.land_parcel', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#ae9e90' } ] }, { 'featureType': 'landscape.natural', 'elementType': 'geometry', 'stylers': [ { 'color': '#dfd2ae' } ] }, { 'featureType': 'poi', 'elementType': 'geometry', 'stylers': [ { 'color': '#dfd2ae' } ] }, { 'featureType': 'poi', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#93817c' } ] }, { 'featureType': 'poi.park', 'elementType': 'geometry.fill', 'stylers': [ { 'color': '#a5b076' } ] }, { 'featureType': 'poi.park', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#447530' } ] }, { 'featureType': 'road', 'elementType': 'geometry', 'stylers': [ { 'color': '#f5f1e6' } ] }, { 'featureType': 'road.arterial', 'elementType': 'geometry', 'stylers': [ { 'color': '#fdfcf8' } ] }, { 'featureType': 'road.highway', 'elementType': 'geometry', 'stylers': [ { 'color': '#f8c967' } ] }, { 'featureType': 'road.highway', 'elementType': 'geometry.stroke', 'stylers': [ { 'color': '#e9bc62' } ] }, { 'featureType': 'road.highway.controlled_access', 'elementType': 'geometry', 'stylers': [ { 'color': '#e98d58' } ] }, { 'featureType': 'road.highway.controlled_access', 'elementType': 'geometry.stroke', 'stylers': [ { 'color': '#db8555' } ] }, { 'featureType': 'road.local', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#806b63' } ] }, { 'featureType': 'transit.line', 'elementType': 'geometry', 'stylers': [ { 'color': '#dfd2ae' } ] }, { 'featureType': 'transit.line', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#8f7d77' } ] }, { 'featureType': 'transit.line', 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#ebe3cd' } ] }, { 'featureType': 'transit.station', 'elementType': 'geometry', 'stylers': [ { 'color': '#dfd2ae' } ] }, { 'featureType': 'water', 'elementType': 'geometry.fill', 'stylers': [ { 'color': '#b9d3c2' } ] }, { 'featureType': 'water', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#92998d' } ] }];

			} else if ( 'dark' === skin ) {

				style = [ { 'elementType': 'geometry', 'stylers': [ { 'color': '#212121' } ] }, { 'elementType': 'labels.icon', 'stylers': [ { 'visibility': 'off' } ] }, { 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#757575' } ] }, { 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#212121' } ] }, { 'featureType': 'administrative', 'elementType': 'geometry', 'stylers': [ { 'color': '#757575' } ] }, { 'featureType': 'administrative.country', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#9e9e9e' } ] }, { 'featureType': 'administrative.land_parcel', 'stylers': [ { 'visibility': 'off' } ] }, { 'featureType': 'administrative.locality', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#bdbdbd' } ] }, { 'featureType': 'poi', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#757575' } ] }, { 'featureType': 'poi.park', 'elementType': 'geometry', 'stylers': [ { 'color': '#181818' } ] }, { 'featureType': 'poi.park', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#616161' } ] }, { 'featureType': 'poi.park', 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#1b1b1b' } ] }, { 'featureType': 'road', 'elementType': 'geometry.fill', 'stylers': [ { 'color': '#2c2c2c' } ] }, { 'featureType': 'road', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#8a8a8a' } ] }, { 'featureType': 'road.arterial', 'elementType': 'geometry', 'stylers': [ { 'color': '#373737' } ] }, { 'featureType': 'road.highway', 'elementType': 'geometry', 'stylers': [ { 'color': '#3c3c3c' } ] }, { 'featureType': 'road.highway.controlled_access', 'elementType': 'geometry', 'stylers': [ { 'color': '#4e4e4e' } ] }, { 'featureType': 'road.local', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#616161' } ] }, { 'featureType': 'transit', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#757575' } ] }, { 'featureType': 'water', 'elementType': 'geometry', 'stylers': [ { 'color': '#000000' } ] }, { 'featureType': 'water', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#3d3d3d' } ] }];

			} else if ( 'night' === skin ) {

				style = [ { 'elementType': 'geometry', 'stylers': [ { 'color': '#242f3e' } ] }, { 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#746855' } ] }, { 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#242f3e' } ] }, { 'featureType': 'administrative.locality', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#d59563' } ] }, { 'featureType': 'poi', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#d59563' } ] }, { 'featureType': 'poi.park', 'elementType': 'geometry', 'stylers': [ { 'color': '#263c3f' } ] }, { 'featureType': 'poi.park', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#6b9a76' } ] }, { 'featureType': 'road', 'elementType': 'geometry', 'stylers': [ { 'color': '#38414e' } ] }, { 'featureType': 'road', 'elementType': 'geometry.stroke', 'stylers': [ { 'color': '#212a37' } ] }, { 'featureType': 'road', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#9ca5b3' } ] }, { 'featureType': 'road.highway', 'elementType': 'geometry', 'stylers': [ { 'color': '#746855' } ] }, { 'featureType': 'road.highway', 'elementType': 'geometry.stroke', 'stylers': [ { 'color': '#1f2835' } ] }, { 'featureType': 'road.highway', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#f3d19c' } ] }, { 'featureType': 'transit', 'elementType': 'geometry', 'stylers': [ { 'color': '#2f3948' } ] }, { 'featureType': 'transit.station', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#d59563' } ] }, { 'featureType': 'water', 'elementType': 'geometry', 'stylers': [ { 'color': '#17263c' } ] }, { 'featureType': 'water', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#515c6d' } ] }, { 'featureType': 'water', 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#17263c' } ] }];

			} else if ( 'aubergine' === skin ) {

				style = [ { 'elementType': 'geometry', 'stylers': [ { 'color': '#1d2c4d' } ] }, { 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#8ec3b9' } ] }, { 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#1a3646' } ] }, { 'featureType': 'administrative.country', 'elementType': 'geometry.stroke', 'stylers': [ { 'color': '#4b6878' } ] }, { 'featureType': 'administrative.land_parcel', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#64779e' } ] }, { 'featureType': 'administrative.province', 'elementType': 'geometry.stroke', 'stylers': [ { 'color': '#4b6878' } ] }, { 'featureType': 'landscape.man_made', 'elementType': 'geometry.stroke', 'stylers': [ { 'color': '#334e87' } ] }, { 'featureType': 'landscape.natural', 'elementType': 'geometry', 'stylers': [ { 'color': '#023e58' } ] }, { 'featureType': 'poi', 'elementType': 'geometry', 'stylers': [ { 'color': '#283d6a' } ] }, { 'featureType': 'poi', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#6f9ba5' } ] }, { 'featureType': 'poi', 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#1d2c4d' } ] }, { 'featureType': 'poi.park', 'elementType': 'geometry.fill', 'stylers': [ { 'color': '#023e58' } ] }, { 'featureType': 'poi.park', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#3C7680' } ] }, { 'featureType': 'road', 'elementType': 'geometry', 'stylers': [ { 'color': '#304a7d' } ] }, { 'featureType': 'road', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#98a5be' } ] }, { 'featureType': 'road', 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#1d2c4d' } ] }, { 'featureType': 'road.highway', 'elementType': 'geometry', 'stylers': [ { 'color': '#2c6675' } ] }, { 'featureType': 'road.highway', 'elementType': 'geometry.stroke', 'stylers': [ { 'color': '#255763' } ] }, { 'featureType': 'road.highway', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#b0d5ce' } ] }, { 'featureType': 'road.highway', 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#023e58' } ] }, { 'featureType': 'transit', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#98a5be' } ] }, { 'featureType': 'transit', 'elementType': 'labels.text.stroke', 'stylers': [ { 'color': '#1d2c4d' } ] }, { 'featureType': 'transit.line', 'elementType': 'geometry.fill', 'stylers': [ { 'color': '#283d6a' } ] }, { 'featureType': 'transit.station', 'elementType': 'geometry', 'stylers': [ { 'color': '#3a4762' } ] }, { 'featureType': 'water', 'elementType': 'geometry', 'stylers': [ { 'color': '#0e1626' } ] }, { 'featureType': 'water', 'elementType': 'labels.text.fill', 'stylers': [ { 'color': '#4e6d70' } ] }];

			} else if ( 'ultra_light' === skin ) {

				style = [{'featureType':'water','elementType':'geometry','stylers':[{'color':'#e9e9e9'},{'lightness':17}]},{'featureType':'landscape','elementType':'geometry','stylers':[{'color':'#f5f5f5'},{'lightness':20}]},{'featureType':'road.highway','elementType':'geometry.fill','stylers':[{'color':'#ffffff'},{'lightness':17}]},{'featureType':'road.highway','elementType':'geometry.stroke','stylers':[{'color':'#ffffff'},{'lightness':29},{'weight':0.2}]},{'featureType':'road.arterial','elementType':'geometry','stylers':[{'color':'#ffffff'},{'lightness':18}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#ffffff'},{'lightness':16}]},{'featureType':'poi','elementType':'geometry','stylers':[{'color':'#f5f5f5'},{'lightness':21}]},{'featureType':'poi.park','elementType':'geometry','stylers':[{'color':'#dedede'},{'lightness':21}]},{'elementType':'labels.text.stroke','stylers':[{'visibility':'on'},{'color':'#ffffff'},{'lightness':16}]},{'elementType':'labels.text.fill','stylers':[{'saturation':36},{'color':'#333333'},{'lightness':40}]},{'elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'featureType':'transit','elementType':'geometry','stylers':[{'color':'#f2f2f2'},{'lightness':19}]},{'featureType':'administrative','elementType':'geometry.fill','stylers':[{'color':'#fefefe'},{'lightness':20}]},{'featureType':'administrative','elementType':'geometry.stroke','stylers':[{'color':'#fefefe'},{'lightness':17},{'weight':1.2}]}];

			} else if ( 'shades_of_grey' === skin ) {

				style = [{'featureType':'all','elementType':'labels.text.fill','stylers':[{'saturation':36},{'color':'#000000'},{'lightness':40}]},{'featureType':'all','elementType':'labels.text.stroke','stylers':[{'visibility':'on'},{'color':'#000000'},{'lightness':16}]},{'featureType':'all','elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'featureType':'administrative','elementType':'geometry.fill','stylers':[{'color':'#000000'},{'lightness':20}]},{'featureType':'administrative','elementType':'geometry.stroke','stylers':[{'color':'#000000'},{'lightness':17},{'weight':1.2}]},{'featureType':'landscape','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':20}]},{'featureType':'poi','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':21}]},{'featureType':'road.highway','elementType':'geometry.fill','stylers':[{'color':'#000000'},{'lightness':17}]},{'featureType':'road.highway','elementType':'geometry.stroke','stylers':[{'color':'#000000'},{'lightness':29},{'weight':0.2}]},{'featureType':'road.arterial','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':18}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':16}]},{'featureType':'transit','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':19}]},{'featureType':'water','elementType':'geometry','stylers':[{'color':'#000000'},{'lightness':17}]}];

			} else if ( 'cool_grey' === skin ) {

				style = [{'featureType':'landscape','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'transit','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'poi','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'water','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'road','elementType':'labels.icon','stylers':[{'visibility':'off'}]},{'stylers':[{'hue':'#00aaff'},{'saturation':-100},{'gamma':2.15},{'lightness':12}]},{'featureType':'road','elementType':'labels.text.fill','stylers':[{'visibility':'on'},{'lightness':24}]},{'featureType':'road','elementType':'geometry','stylers':[{'lightness':57}]}];

			} else if ( 'pale_dawn' === skin ) {

				style = [{'featureType':'administrative','elementType':'all','stylers':[{'visibility':'on'},{'lightness':33}]},{'featureType':'landscape','elementType':'all','stylers':[{'color':'#f2e5d4'}]},{'featureType':'poi.park','elementType':'geometry','stylers':[{'color':'#c5dac6'}]},{'featureType':'poi.park','elementType':'labels','stylers':[{'visibility':'on'},{'lightness':20}]},{'featureType':'road','elementType':'all','stylers':[{'lightness':20}]},{'featureType':'road.highway','elementType':'geometry','stylers':[{'color':'#c5c6c6'}]},{'featureType':'road.arterial','elementType':'geometry','stylers':[{'color':'#e4d7c6'}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#fbfaf7'}]},{'featureType':'water','elementType':'all','stylers':[{'visibility':'on'},{'color':'#acbcc9'}]}];
			
			} else if ( 'map' === skin ) {
			
				style = [{"featureType":"all","elementType":"geometry.fill","stylers":[{"weight":"2.00"}]},{"featureType":"all","elementType":"geometry.stroke","stylers":[{"color":"#9c9c9c"}]},{"featureType":"all","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#eeeeee"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#7b7b7b"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#c8d7d4"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#070707"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]}];
			
			} else if ( 'accent' === skin ) {
			
				style = [
				    {
				        "featureType": "all",
				        "elementType": "geometry.fill",
				        "stylers": [
				            {
				                "weight": "2.00"
				            }
				        ]
				    },
				    {
				        "featureType": "all",
				        "elementType": "geometry.stroke",
				        "stylers": [
				            {
				                "color": "#9c9c9c"
				            }
				        ]
				    },
				    {
				        "featureType": "all",
				        "elementType": "labels.text",
				        "stylers": [
				            {
				                "visibility": "on"
				            }
				        ]
				    },
				    {
				        "featureType": "landscape",
				        "elementType": "all",
				        "stylers": [
				            {
				                "color": "#f2f2f2"
				            }
				        ]
				    },
				    {
				        "featureType": "landscape",
				        "elementType": "geometry.fill",
				        "stylers": [
				            {
				                "color": "#ffffff"
				            }
				        ]
				    },
				    {
				        "featureType": "landscape.man_made",
				        "elementType": "geometry.fill",
				        "stylers": [
				            {
				                "color": "#ffffff"
				            }
				        ]
				    },
				    {
				        "featureType": "poi",
				        "elementType": "all",
				        "stylers": [
				            {
				                "visibility": "off"
				            }
				        ]
				    },
				    {
				        "featureType": "road",
				        "elementType": "all",
				        "stylers": [
				            {
				                "saturation": -100
				            },
				            {
				                "lightness": 45
				            }
				        ]
				    },
				    {
				        "featureType": "road",
				        "elementType": "geometry.fill",
				        "stylers": [
				            {
				                "color": "#eeeeee"
				            }
				        ]
				    },
				    {
				        "featureType": "road",
				        "elementType": "labels.text.fill",
				        "stylers": [
				            {
				                "color": "#7b7b7b"
				            }
				        ]
				    },
				    {
				        "featureType": "road",
				        "elementType": "labels.text.stroke",
				        "stylers": [
				            {
				                "color": "#ffffff"
				            }
				        ]
				    },
				    {
				        "featureType": "road.highway",
				        "elementType": "all",
				        "stylers": [
				            {
				                "visibility": "simplified"
				            }
				        ]
				    },
				    {
				        "featureType": "road.arterial",
				        "elementType": "labels.icon",
				        "stylers": [
				            {
				                "visibility": "off"
				            }
				        ]
				    },
				    {
				        "featureType": "transit",
				        "elementType": "all",
				        "stylers": [
				            {
				                "visibility": "off"
				            }
				        ]
				    },
				    {
				        "featureType": "water",
				        "elementType": "all",
				        "stylers": [
				            {
				                "color": "#46bcec"
				            },
				            {
				                "visibility": "on"
				            }
				        ]
				    },
				    {
				        "featureType": "water",
				        "elementType": "geometry.fill",
				        "stylers": [
				            {
				                 "color": WVCParams.accentColor
				            }
				        ]
				    },
				    {
				        "featureType": "water",
				        "elementType": "labels.text.fill",
				        "stylers": [
				            {
				                "color": "#070707"
				            }
				        ]
				    },
				    {
				        "featureType": "water",
				        "elementType": "labels.text.stroke",
				        "stylers": [
				            {
				                "color": "#ffffff"
				            }
				        ]
				    }
				]

			} else if ( 'custom' === skin && customSkin ) {

				style = customSkin;
			}

			mapOptions = {
				zoom: zoom,
				center: new google.maps.LatLng( locations[0][1], locations[0][2]),
				panControl: false,
				streetViewControl: false,
				mapTypeControl: false,
				styles: style,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};

			if ( markerIcon ) {

				customIcon = {
					url: markerIcon, // url
					scaledSize: new google.maps.Size( 50, 50 ), // scaled size
    				origin: new google.maps.Point( 0,0 ), // origin
   					anchor: new google.maps.Point( 0, 0 ) // anchor
				};

			} else {
				customIcon = {
					path: 'M -0.700129 -465.882 c -85.1 0 -154.334 69.234 -154.334 154.333 c 0 34.275 21.887 90.155 66.908 170.834 c 31.846 57.063 63.168 104.643 64.484 106.64 l 22.942 34.775 l 22.941 -34.774 c 1.31699 -1.99799 32.641 -49.577 64.483 -106.64 c 45.023 -80.68 66.908 -136.559 66.908 -170.834 c 0.00100708 -85.1 -69.233 -154.334 -154.332 -154.334 Z M -0.700129 -232.592 c -44.182 0 -80 -35.817 -80 -80 s 35.818 -80 80 -80 c 44.182 0 80 35.817 80 80 s -35.819 80 -80 80 Z',
					//path: 'M-20,0a20,20 0 1,0 40,0a20,20 0 1,0 -40,0',
					fillColor: markerColor,
					fillOpacity: 1,
					strokeColor: markerColor,
					strokeWeight: 0,
					//scale: ( $( 'html' ).hasClass( 'no-hidpi' ) ? 0.1 : 0.2 ),
					scale : 0.08
				};
			}
			
			map = new google.maps.Map( element, mapOptions );
			infowindow = new google.maps.InfoWindow();

			for ( i = 0; i < locations.length; i++ ) {  
				marker = new google.maps.Marker( {
					position: new google.maps.LatLng( locations[i][1], locations[i][2] ),
					map: map,
					visible: true,
					optimized: false,
					title: 'spot',
					icon: customIcon
				} );

				google.maps.event.addListener( marker, 'click', ( function( marker, i ) {
					return function() {
						infowindow.setContent( locations[i][0] );
						infowindow.open( map, marker );
					}
				} )( marker, i) );
			}
			
			// marker = new google.maps.Marker( {
			// 	position: location,
			// 	map: map,
			// 	visible: true,
			// 	optimized: false,
			// 	title: 'spot',
			// 	icon: customIcon
			// } );
		},

		/**
		 * Google map fix to avoid scroll
		 */
		scrollFix : function () {
			$( '.wvc-google-maps-container' ).click( function () {
				$( this ).find( '.wvc-google-maps' ).css( 'pointer-events', 'auto' );
			} );

			$( '.wvc-google-maps-container' ).mouseleave( function() {
				$( this ).find( '.wvc-google-maps' ).css( 'pointer-events', 'none' );
			} );
		}
	};

}( jQuery );

( function( $ ) {

	'use strict';

	if ( ! WVCParams.googleMapApiKey ) {
		return;
	}

	if ( 'undefined' !== google && WVCParams.googleMapApiKey ) {
		google.maps.event.addDomListener( window, 'load', WVCGoogleMaps.init() );
	}

} )( jQuery );