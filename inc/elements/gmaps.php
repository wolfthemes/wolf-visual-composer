<?php
/**
 * Google Map
 *
 * @author WolfThemes
 * @category Core
 * @package WolfWPBakeryPageBuilderExtension/Elements
 * @version 3.2.8
 */

defined( 'ABSPATH' ) || exit;

$wvc_gmap_colors = array();

vc_map(
	array(
		'name'             => esc_html__( 'Google Maps', 'wolf-visual-composer' ),
		'base'             => 'vc_gmaps',
		'icon'             => 'fa fa-map-marker',
		'category'         => esc_html__( 'Content', 'wolf-visual-composer' ),
		'description'      => esc_html__( 'Map block', 'wolf-visual-composer' ),
		'admin_enqueue_js' => WVC_JS . '/admin/numeric-slider.js',
		'deprecated'       => '5.3',
		'params'           => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Widget title', 'wolf-visual-composer' ),
				'param_name'  => 'title',
				'description' => esc_html__( 'Enter text which will be used as widget title. Leave blank if no title is needed.', 'wolf-visual-composer' ),
			),
			array(
				'type'        => 'wvc_textfield',
				'heading'     => esc_html__( 'Coordinates (Latitude, Longitude)', 'wolf-visual-composer' ),
				'param_name'  => 'coordinates',
				'placeholder' => '50.799852, 2.486477',
				'description' => sprintf(
					wp_kses(
						__( 'To extract the Latitude and Longitude of your address, follow the instructions %s. 1) Use the directions under the section "Get the coordinates of a place" 2) Copy the coordinates 3) Paste the coordinates in the field with the "comma" sign.', 'wolf-visual-composer' ),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						)
					),
					'<a href="https://support.google.com/maps/answer/18539?source=gsearch&hl=en" target="_blank">here</a>'
				),
				'admin_label' => true,
			),

			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Skin', 'wolf-visual-composer' ),
				'param_name'  => 'map_skin',
				'value'       => array(
					esc_html__( 'Standard', 'wolf-visual-composer' )       => 'standard',
					esc_html__( 'Light', 'wolf-visual-composer' )          => 'silver',
					esc_html__( 'Retro', 'wolf-visual-composer' )          => 'retro',
					esc_html__( 'Dark', 'wolf-visual-composer' )           => 'dark',
					esc_html__( 'Night', 'wolf-visual-composer' )          => 'night',
					esc_html__( 'Aubergine', 'wolf-visual-composer' )      => 'aubergine',
					esc_html__( 'Ultra Light with Labels', 'wolf-visual-composer' ) => 'ultra_light',
					esc_html__( 'Shades of Grey', 'wolf-visual-composer' ) => 'shades_of_grey',
					esc_html__( 'Cool Grey', 'wolf-visual-composer' )      => 'cool_grey',
					esc_html__( 'Pale Dawn', 'wolf-visual-composer' )      => 'pale_dawn',
				),
				'admin_label' => true,
			),

			array(
				'type'               => 'dropdown',
				'heading'            => esc_html__( 'Marker Color', 'wolf-visual-composer' ),
				'param_name'         => 'marker_color',
				'value'              => array_merge(
					array( esc_html__( 'Default color', 'wolf-visual-composer' ) => 'default' ),
					wvc_get_shared_colors(),
					array( esc_html__( 'Custom color', 'wolf-visual-composer' ) => 'custom' )
				),
				'std'                => 'default',
				'description'        => esc_html__( 'Select a marker color.', 'wolf-visual-composer' ),
				'param_holder_class' => 'wvc_colored-dropdown',
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => esc_html__( 'Marker Custom Color', 'wolf-visual-composer' ),
				'param_name' => 'marker_custom_color',
				'dependency' => array(
					'element' => 'marker_color',
					'value'   => 'custom',
				),
			),

			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Map height', 'wolf-visual-composer' ),
				'param_name'  => 'size',
				'value'       => '500px',
				'admin_label' => true,
			// 'description' => esc_html__( 'Enter the map height in pixels or any other valid CSS unit. Leave this field empty so it takes the full height of the container.', 'wolf-visual-composer' )
			),
			array(
				'type'        => 'textarea_safe',
				'heading'     => esc_html__( 'Address', 'wolf-visual-composer' ),
				'param_name'  => 'address',
				'description' => esc_html__( 'Insert the address here if you want it to be display below the map.', 'wolf-visual-composer' ),
				'admin_label' => true,
			),

		// array(
		// 'type' => 'dropdown',
		// 'heading' => esc_html__( 'Map color', 'wolf-visual-composer' ),
		// 'param_name' => 'map_color',
		// 'value' => $wvc_gmap_colors,
		// 'description' => esc_html__( 'Specify the map base color.', 'wolf-visual-composer' ),
		// 'admin_label' => true,
		// 'param_holder_class' => 'vc_colored-dropdown'
		// ),
		// array(
		// 'type' => 'dropdown',
		// 'heading' => esc_html__( 'UI color', 'wolf-visual-composer' ),
		// 'param_name' => 'ui_color',
		// 'value' => $wvc_gmap_colors,
		// 'description' => esc_html__( 'Specify the UI color.', 'wolf-visual-composer' ),
		// 'admin_label' => true,
		// 'param_holder_class' => 'vc_colored-dropdown'
		// ),
		// array(
		// 'type' => 'wvc_numeric_slider',
		// 'heading' => esc_html__( 'Zoom', 'wolf-visual-composer' ),
		// 'param_name' => 'zoom',
		// 'min' => 0,
		// 'max' => 19,
		// 'step' => 1,
		// 'value' => 14,
		// 'description' => esc_html__( 'Set map zoom level.', 'wolf-visual-composer' ),
		// ),
		// array(
		// 'type' => 'wvc_numeric_slider',
		// 'heading' => esc_html__( 'Saturation', 'wolf-visual-composer' ),
		// 'param_name' => 'map_saturation',
		// 'min' => - 100,
		// 'max' => 100,
		// 'step' => 1,
		// 'value' => - 20,
		// 'description' => esc_html__( 'Set map color saturation.', 'wolf-visual-composer' ),
		// ),
		// array(
		// 'type' => 'wvc_numeric_slider',
		// 'heading' => esc_html__( 'Brightness', 'wolf-visual-composer' ),
		// 'param_name' => 'map_brightness',
		// 'min' => - 100,
		// 'max' => 100,
		// 'step' => 1,
		// 'value' => 5,
		// 'description' => esc_html__( 'Set map color brightness.', 'wolf-visual-composer' ),
		// ),
		// array(
		// 'type' => 'checkbox',
		// 'heading' => esc_html__( 'Mobile no draggable', 'wolf-visual-composer' ),
		// 'param_name' => 'mobile_no_drag',
		// 'description' => esc_html__( 'Deactivate the drag function on mobile devices.', 'wolf-visual-composer' ),
		// 'group' => esc_html__( 'Mobile', 'wolf-visual-composer' ),
		// 'value' => Array(
		// '' => 'yes'
		// ),
		// ),
		),
	)
);
