<?php
/**
 * iframe opener
 *
 * @author WolfThemes
 * @category Core
 * @package WolfWPBakeryPageBuilderExtension/Elements
 * @version 3.2.8
 */

defined( 'ABSPATH' ) || exit;

vc_map(
	array(
		'name'        => esc_html__( 'Iframe Opener', 'wolf-visual-composer' ),
		'base'        => 'wvc_iframe_opener',
		'description' => esc_html__( 'Open an iframe in a lightbox', 'wolf-visual-composer' ),
		'icon'        => 'fa fa-play-circle-o',
		'category'    => esc_html__( 'Media', 'wolf-visual-composer' ),
		'params'      => array(

			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'URL', 'wolf-visual-composer' ),
				'param_name' => 'iframe_url',
				// 'description' => esc_html__( 'Accordion section title.', 'wolf-visual-composer' ),
			),

			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Custom Play Button', 'wolf-visual-composer' ),
				'param_name' => 'custom_play_button',
				'value'      => array(
					esc_html__( 'No', 'wolf-visual-composer' )  => '',
					esc_html__( 'Yes', 'wolf-visual-composer' ) => 'yes',
				),
			),

			array(
				'type'       => 'attach_image',
				'heading'    => esc_html__( 'Button Image', 'wolf-visual-composer' ),
				'param_name' => 'button_image',
				'dependency' => array(
					'element' => 'custom_play_button',
					'value'   => array( 'yes' ),
				),
			),

			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Alignment', 'wolf-visual-composer' ),
				'param_name' => 'alignment',
				'value'      => array(
					esc_html__( 'center', 'wolf-visual-composer' ) => 'center',
					esc_html__( 'left', 'wolf-visual-composer' )   => 'left',
					esc_html__( 'right', 'wolf-visual-composer' )  => 'right',
				),
			),

			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Caption Position', 'wolf-visual-composer' ),
				'param_name' => 'caption_position',
				'value'      => array(
					esc_html__( 'None', 'wolf-visual-composer' )   => 'none',
					esc_html__( 'Bottom', 'wolf-visual-composer' ) => 'bottom',
					esc_html__( 'Right', 'wolf-visual-composer' )  => 'right',
					esc_html__( 'Left', 'wolf-visual-composer' )   => 'left',
					esc_html__( 'Top', 'wolf-visual-composer' )    => 'top',
				),
			),

			array(
				'type'        => 'wvc_textfield',
				'heading'     => esc_html__( 'Caption', 'wolf-visual-composer' ),
				'param_name'  => 'caption',
				'placeholder' => esc_html__( 'Watch "My Video Title"', 'wolf-visual-composer' ),
				'dependency'  => array(
					'element'            => 'caption_position',
					'value_not_equal_to' => array( 'none' ),
				),
			),
		),
	)
);

class WPBakeryShortCode_Wvc_Iframe_Opener extends WPBakeryShortCode {}
