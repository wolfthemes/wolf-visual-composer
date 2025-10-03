<?php
/**
 * Interactive LInk Item
 *
 * @author WolfThemes
 * @category Core
 * @package WolfWPBakeryPageBuilderExtension/Elements
 * @version 3.2.8
 */

defined( 'ABSPATH' ) || exit;

/* Get bg params */
$bg_params = wvc_background_params();

/* Remove unwanted params */
$remove_bg_params = array(
	'background_effect',
	'video_bg_loop',
	'video_bg_parallax',
	'add_particles',
);

foreach ( $bg_params as $key => $param ) {
	if ( is_array( $param ) && ! empty( $param ) ) {

		if ( 'background_type' === $param['param_name'] ) {
			$bg_params[ $key ]['value'] = array(
				esc_html__( 'Image', 'wolf-visual-composer' ) => 'image',
				esc_html__( 'Video', 'wolf-visual-composer' ) => 'video',
			);

			$bg_params[ $key ]['std'] = 'image';
		}

		if ( 'background_img' === $param['param_name'] ) {
			$bg_params[ $key ]['param_name'] = 'image'; // backward compat
		}

		if ( 'add_overlay' === $param['param_name'] ) {
			// $bg_params[ $key ]['std'] = true;
		}

		$bg_params[ $key ]['group'] = esc_html__( 'Background', 'wolf-visual-composer' );

		if ( in_array( $param['param_name'], $remove_bg_params ) ) {
			unset( $bg_params[ $key ] );
		}
	}
}

// Interactive LInk Item
vc_map(
	array(
		'name'     => esc_html__( 'Showcase Vertical Item', 'wolf-visual-composer' ),
		'base'     => 'wvc_showcase_vertical_carousel_item',
		'as_child' => array(
			'only'   => 'wvc_showcase_vertical_carousel',
			'except' => 'wvc_anything_slider, wvc_posts_index',
		),
		'category' => esc_html__( 'Content', 'wolf-visual-composer' ),
		'icon'     => 'fa fa-television',
		'params'   => array_merge(
			array(

				// array(
				// 'type' => 'attach_image',
				// 'heading' => esc_html__( 'Background Image', 'wolf-visual-composer' ),
				// 'param_name' => 'image',
				// 'admin_label' => true,
				// ),

				array(
					'type'        => 'wvc_textfield',
					'heading'     => esc_html__( 'Title', 'wolf-visual-composer' ),
					'param_name'  => 'text',
					'value'       => esc_html__( 'About', 'wolf-visual-composer' ),
					'admin_label' => true,
					'group'       => esc_html__( 'Text', 'wolf-visual-composer' ),
				),

				array(
					'type'        => 'wvc_textfield',
					'heading'     => esc_html__( 'Tagline', 'wolf-visual-composer' ),
					'param_name'  => 'tagline',
					'value'       => esc_html__( 'Our Story', 'wolf-visual-composer' ),
					'admin_label' => true,
					'group'       => esc_html__( 'Text', 'wolf-visual-composer' ),
				),

				array(
					'type'        => 'wvc_textfield',
					'heading'     => esc_html__( 'Button text', 'wolf-visual-composer' ),
					'param_name'  => 'button_text',
					'value'       => esc_html__( 'Learn more', 'wolf-visual-composer' ),
					'admin_label' => true,
					'group'       => esc_html__( 'Text', 'wolf-visual-composer' ),
				),

				array(
					'type'       => 'vc_link',
					'heading'    => esc_html__( 'Link', 'wolf-visual-composer' ),
					'param_name' => 'link',
					'group'      => esc_html__( 'Text', 'wolf-visual-composer' ),
				),
			),
			$bg_params
		),
	)
);

class WPBakeryShortCode_Wvc_Showcase_Vertical_Carousel_Item extends WPBakeryShortCode {}
