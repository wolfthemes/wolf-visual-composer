<?php
/**
 * Soundcloud shortcode template
 *
 * @author WolfThemes
 * @category Core
 * @package WolfWPBakeryPageBuilderExtension/Templates
 * @version 3.2.8
 */

defined( 'ABSPATH' ) || exit;

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

extract(
	shortcode_atts(
		array(
			'link'                => '',
			'max_width'           => '',
			'css_animation'       => '',
			'css_animation_delay' => '',
			'el_class'            => '',
			'css'                 => '',
			'inline_style'        => '',
		),
		$atts
	)
);

$output = '';

$class         = $el_class;
$inline_style  = wvc_sanitize_css_field( $inline_style );
$inline_style .= wvc_shortcode_custom_style( $css );

/*Animate */
if ( ! wvc_is_new_animation( $css_animation ) ) {
	$class        .= wvc_get_css_animation( $css_animation );
	$inline_style .= wvc_get_css_animation_delay( $css_animation_delay );
}

$url       = esc_url( $link );
$max_width = esc_attr( $max_width );

$embed = wp_oembed_get( $url );

$class .= ' wvc-soundcloud-container wvc-element';

$output .= '<div  class="' . wvc_sanitize_html_classes( $class ) . '" style="' . wvc_esc_style_attr( $inline_style ) . '"';

$output .= wvc_element_aos_animation_data_attr( $atts );
$output .= '>';

$output .= $embed . '</div>';

echo $output;
