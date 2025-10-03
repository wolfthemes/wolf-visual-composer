<?php
/**
 * Banner
 *
 * @author WolfThemes
 * @category Core
 * @package WolfWPBakeryPageBuilderExtension/Elements
 * @version 3.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$product_post_posts = get_posts( 'post_type="product"&numberposts=-1' );

$product_posts = array();
if ( $product_post_posts ) {
	foreach ( $product_post_posts as $product_post_options ) {
		$product_posts[ $product_post_options->post_title ] = $product_post_options->ID;
	}
} else {
	$product_posts[ esc_html__( 'No product yet', 'wolf-visual-composer' ) ] = 0;
}

vc_map(
	array(
		'name'        => esc_html__( 'Product Banner', 'wolf-visual-composer' ),
		'description' => esc_html__( 'An alternative way to showcase a prodict.', 'wolf-visual-composer' ),
		'base'        => 'wvc_banner_product',
		'category'    => esc_html__( 'Media', 'wolf-visual-composer' ),
		'icon'        => 'fa fa-bookmark-o',
		'params'      => array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Product', 'wolf-visual-composer' ),
				'param_name'  => 'product_id',
				'value'       => $product_posts,
				'admin_label' => true,
			),
			// Image file
			array(
				'type'        => 'attach_image',
				'heading'     => esc_html__( 'Image', 'wolf-visual-composer' ),
				'param_name'  => 'image',
				'value'       => '',
				'description' => esc_html__( 'By default the product image will be used. To use another image, select image from media library.', 'wolf-visual-composer' ),
				'admin_label' => true,
			),

			// Image size
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Banner Size', 'wolf-visual-composer' ),
				'param_name'  => 'img_size',
				'value'       => wvc_get_image_sizes(),
				'description' => esc_html__( 'You can set the "large", "medium" and "thumbnail" sizes in the WP media settings.', 'wolf-visual-composer' ),
				'admin_label' => true,
			),

			array(
				'type'        => 'wvc_textfield',
				'heading'     => esc_html__( 'Custom Banner Size', 'wolf-visual-composer' ),
				'param_name'  => 'custom_img_size',
				'description' => esc_html__( 'Enter size in pixels (Example: 200x100 (Width x Height).', 'wolf-visual-composer' ),
				'dependency'  => array(
					'element' => 'img_size',
					'value'   => array( 'custom' ),
				),
			),

			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Banner alignment', 'wolf-visual-composer' ),
				'param_name'  => 'alignment',
				'value'       => array(
					esc_html__( 'Center', 'wolf-visual-composer' ) => 'center',
					esc_html__( 'Left', 'wolf-visual-composer' )   => 'left',
					esc_html__( 'Right', 'wolf-visual-composer' )  => 'right',
				),
				'description' => esc_html__( 'Select image alignment.', 'wolf-visual-composer' ),
				'admin_label' => true,
			),

			// Max Width
			array(
				'type'        => 'wvc_textfield',
				'heading'     => esc_html__( 'Maximum width', 'wolf-visual-composer' ),
				'param_name'  => 'max_width',
				'description' => sprintf( esc_html__( 'Set a value in %1$s or %2$s if you want to constrain the image width.', 'wolf-visual-composer' ), 'px', '%' ),
				'placeholder' => '100%',
			),

			array(
				'type'               => 'dropdown',
				'heading'            => esc_html__( 'Overlay Color', 'wolf-visual-composer' ),
				'param_name'         => 'overlay_color',
				'value'              => array_merge(
					array( esc_html__( 'Auto', 'wolf-visual-composer' ) => 'auto' ),
					wvc_get_shared_colors(),
					wvc_get_shared_gradient_colors(),
					array( esc_html__( 'Custom color', 'wolf-visual-composer' ) => 'custom' )
				),
				'std'                => apply_filters( 'wvc_default_banner_overlay_color', 'black' ),
				'description'        => esc_html__( 'Select an overlay color.', 'wolf-visual-composer' ),
				'param_holder_class' => 'wvc_colored-dropdown',
			),

			// Overlay color
			array(
				'type'       => 'colorpicker',
				'heading'    => esc_html__( 'Overlay Custom Color', 'wolf-visual-composer' ),
				'param_name' => 'overlay_custom_color',
				// 'value' => '#000000',
				'dependency' => array(
					'element' => 'overlay_color',
					'value'   => array( 'custom' ),
				),
			),

			array(
				'type'               => 'dropdown',
				'heading'            => esc_html__( 'Overlay Text Color', 'wolf-visual-composer' ),
				'param_name'         => 'overlay_text_color',
				'value'              => array_merge(
					array( esc_html__( 'Auto', 'wolf-visual-composer' ) => 'auto' ),
					wvc_get_shared_colors()
					// wvc_get_shared_gradient_colors()
					// array( esc_html__( 'Custom color', 'wolf-visual-composer' ) => 'custom', )
				),
				'std'                => apply_filters( 'wvc_default_banner_overlay_text_color', 'white' ),
				'description'        => esc_html__( 'Select an overlay color.', 'wolf-visual-composer' ),
				'param_holder_class' => 'wvc_colored-dropdown',
			),

			// Overlay color
			array(
				'type'       => 'colorpicker',
				'heading'    => esc_html__( 'Overlay Text Custom Color', 'wolf-visual-composer' ),
				'param_name' => 'overlay_text_custom_color',
				// 'value' => '#000000',
				'dependency' => array(
					'element' => 'overlay_text_color',
					'value'   => array( 'custom' ),
				),
			),

			// Overlay opacity
			array(
				'type'        => 'wvc_textfield',
				'heading'     => esc_html__( 'Overlay Opacity in Percent', 'wolf-visual-composer' ),
				'param_name'  => 'overlay_opacity',
				'description' => '',
				'value'       => 40,
			),

			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Text Alignment', 'wolf-visual-composer' ),
				'param_name' => 'txt_align',
				'value'      => array(
					esc_html__( 'Center', 'wolf-visual-composer' ) => 'center',
					esc_html__( 'Left', 'wolf-visual-composer' )   => 'left',
					esc_html__( 'Right', 'wolf-visual-composer' )  => 'right',
				),
			),

			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Text Vertical Alignment', 'wolf-visual-composer' ),
				'param_name' => 'txt_v_align',
				'value'      => array(
					esc_html__( 'Middle', 'wolf-visual-composer' ) => 'middle',
					esc_html__( 'Bottom', 'wolf-visual-composer' ) => 'bottom',
					esc_html__( 'Top', 'wolf-visual-composer' )    => 'top',
				),
			),

			// array(
			// 'type' => 'wvc_textfield',
			// 'heading' => esc_html__( 'Title', 'wolf-visual-composer' ),
			// 'param_name' => 'title',
			// 'admin_label' => true,
			// 'group' => esc_html__( 'Text', 'wolf-visual-composer' ),
			// ),

			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Title Tag', 'wolf-visual-composer' ),
				'param_name' => 'title_tag',
				'value'      => array(
					'h3',
					'span',
					'h1',
					'h2',
					'h4',
					'h5',
					'h6',
				),
				'group'      => esc_html__( 'Text', 'wolf-visual-composer' ),
			),

			// array(
			// 'type' => 'textarea',
			// 'heading' => esc_html__( 'Tagline', 'wolf-visual-composer' ),
			// 'param_name' => 'tagline',
			// 'admin_label' => true,
			// 'group' => esc_html__( 'Text', 'wolf-visual-composer' ),
			// ),
		),
	)
);

class WPBakeryShortCode_Wvc_Banner_Product extends WPBakeryShortCode {}
