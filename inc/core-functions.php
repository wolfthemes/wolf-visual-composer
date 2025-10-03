<?php
/**
 * WPBakery Page Builder Extension core functions
 *
 * General core functions available on admin and frontend
 *
 * @author WolfThemes
 * @category Core
 * @package WolfWPBakeryPageBuilderExtension/Core
 * @version 3.2.8
 */

defined( 'ABSPATH' ) || exit;

/**
 * Gets the ID of the post, even if it's not inside the loop.
 *
 * @uses WP_Query
 * @uses get_queried_object()
 * @extends get_the_ID()
 * @see get_the_ID()
 *
 * @return int
 */
function wvc_get_the_ID() {
	global $wp_query;

	$post_id = null;

	if ( function_exists( 'is_shop' ) && is_shop() ) {

		$post_id = get_option( 'woocommerce_shop_page_id' );

		// Get post ID outside the loop
	} elseif ( is_object( $wp_query ) && isset( $wp_query->queried_object ) && isset( $wp_query->queried_object->ID ) ) {

		$post_id = $wp_query->queried_object->ID;

	} else {
		$post_id = get_the_ID();
	}

	return $post_id;
}

/**
 * Get theme slug
 *
 * @return string
 */
function wvc_get_theme_slug() {
	return apply_filters( 'wolftheme_theme_slug', esc_attr( sanitize_title_with_dashes( get_template() ) ) );
}

/**
 * Allow SVG files
 *
 * @param array $mimes Additional allowed file types.
 * @return array $mimes
 */
function wvc_mime_types( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	$mimes['webp'] = 'image/webp';
	$mimes['csv']  = 'text/csv';

	if ( class_exists( 'PixProofPlugin' ) ) {
		$mimes['zip'] = 'application/zip';
		$mimes['gz']  = 'application/x-gzip';
	}

	return $mimes;
}
add_filter( 'upload_mimes', 'wvc_mime_types', 10, 1 );

/**
 * Disable Gutenberg
 */
function wvc_disable_gutenberg() {

	add_filter( 'use_block_editor_for_post', '__return_false', 10 );
	add_filter( 'use_block_editor_for_page', '__return_false', 10 );
	add_filter( 'use_block_editor_for_post_type', '__return_false', 10 );
}
// add_action( 'init', 'wvc_disable_gutenberg' );

/**
 * Add image sizes
 *
 * These size will be ued for galleries and sliders
 */
function wvc_add_image_sizes() {

	// Extra Large for background.
	add_image_size( 'wvc-XL', 2000, 3000, false );

	// Slides.
	add_image_size( 'wvc-slide', 1200, 700, true );

	// Masonry.
	add_image_size( 'wvc-masonry', 500, 2000, false );

	// Horizontal photo.
	add_image_size( 'wvc-photo', 500, 500, false );
}
add_action( 'init', 'wvc_add_image_sizes' );

/**
 * Get element list in array to allow filtering by theme and stuff
 *
 * @return array
 */
function wvc_get_element_list() {

	$wvc_elements = array(
		'accordion',
		'accordion-tab',
		'admin-helper-text',
		'advanced-slider',
		'advanced-slide',
		// 'albums',
		// 'album-disc',
		// 'album-tracklist',
		// 'album-tracklist-item',
		'anchor',
		// 'anything-slider',
		// 'anything-slide',
		'audio',
		'audio-embed',
		'bandsintown-events',
		// 'bandsintown-tracking-button',
		'banner-gallery',
		'banner-product',
		'banner',
		'bigtext',
		'bit-artist',
		'breadcrumb',
		'button',
		// 'cards-gallery',
		'cta',
		'column',
		'column-inner',
		'column-text',
		'comparison_slider',
		'content-block',
		'content-slider',
		'countdown',
		'counter',
		'current-year',
		'custom-heading',
		// 'discography',
		'dropcap',
		'embed-video',
		'empty-space',
		// 'events',
		'facebook-page-box',
		'gallery',
		'gmaps',
		'google-maps',
		'highlight',
		'hours',
		'hoverbox',
		'icon',
		'iframe-opener',
		// 'image-link',
		'image-device-slider',
		'info-table',
		'instagram-gallery',
		'instagram-old',
		'instagram',
		'interactive-links',
		'interactive-link-item',
		// 'interactive-overlays',
		// 'interactive-overlay-item',
		'item-price',
		// 'last-posts',
		'post-slider',
		'list',
		'mailchimp',
		'message',
		'music-network',
		'next-month',
		'oembed-gist',
		// 'old-instagram',
		'parallax-holder',
		'pie',
		'playlist',
		// 'portfolio',
		'pricing-table',
		'process-container',
		'process-item',
		'progress-bar',
		'rev-slider-vc',
		'row',
		'row-inner',
		// 'section',
		'sb-instagram-feed',
		'separator',
		'service-table',
		'social-icons',
		'social-icons-custom',
		'single-image',
		'soundcloud',
		'span',
		'spotify-player',
		'spotify-follow-button',
		'tabs',
		'tab',
		'team-member',
		'testimonials',
		'testimonial-slider',
		'testimonial-slide',
		'toggle',
		'twitter',
		'typed',
		'video',
		'video-opener',
		'video-self-hosted',
		// 'videos-carousel', //  last videos from plugin carousel.
		// 'videos',
		// 'waveform-player',
		'wc-categories',
		'youtube',
		'zigzag',
	);

	// apply filters.
	$wvc_elements = apply_filters( 'wvc_element_list', $wvc_elements );

	// sort by alphabetical order.
	sort( $wvc_elements );

	return $wvc_elements;
}

/**
 * Add supportted 3rd party plugin elements
 *
 * @param array Elements array.
 * @return array
 */
function wvc_add_third_party_plugins_elements( $elements ) {

	if ( class_exists( 'Mp_Time_Table' ) ) {
		$elements[] = 'mp-timetable';
	}

	return $elements;
}
// add_filter( 'wvc_element_list', 'wvc_add_third_party_plugins_elements' );

/**
 * Get blog URL
 */
function wvc_get_blog_url() {
	if ( get_option( 'page_for_posts' ) ) {
		return esc_url( get_permalink( get_option( 'page_for_posts' ) ) );
	} else {
		return esc_url( home_url( '/' ) );
	}
}

/**
 * Check if we're on a blog page
 *
 * @return bool
 */
function wvc_is_blog() {

	$is_blog = ( wvc_is_home_as_blog() || wvc_is_blog_index() || is_search() || is_archive() ) && ! wvc_is_woocommerce_page() && 'post' == get_post_type();
	return ( true === $is_blog );
}

/**
 * Check if we're on the blog index page
 *
 * @return bool
 */
function wvc_is_blog_index() {

	return wvc_is_home_as_blog() || ( wvc_get_the_ID() == get_option( 'page_for_posts' ) );
}

/**
 * Get shared color list in array to allow filtering by theme and stuff
 *
 * @return array
 */
function wvc_get_shared_colors() {

	$wvc_shared_colors = array(
		esc_html__( 'Black', 'wolf-visual-composer' )      => 'black',
		esc_html__( 'Light Grey', 'wolf-visual-composer' ) => 'lightergrey',
		esc_html__( 'Dark Grey', 'wolf-visual-composer' )  => 'darkgrey',
		esc_html__( 'White', 'wolf-visual-composer' )      => 'white',
		esc_html__( 'Orange', 'wolf-visual-composer' )     => 'orange',
		esc_html__( 'Green', 'wolf-visual-composer' )      => 'green',
		esc_html__( 'Turquoise', 'wolf-visual-composer' )  => 'turquoise',
		esc_html__( 'Violet', 'wolf-visual-composer' )     => 'violet',
		esc_html__( 'Pink', 'wolf-visual-composer' )       => 'pink',
		esc_html__( 'Grey blue', 'wolf-visual-composer' )  => 'greyblue',
		esc_html__( 'Red', 'wolf-visual-composer' )        => 'red',
		esc_html__( 'Yellow', 'wolf-visual-composer' )     => 'yellow',
		esc_html__( 'Blue', 'wolf-visual-composer' )       => 'blue',
		esc_html__( 'Peacoc', 'js_composer' )              => 'peacoc',
		esc_html__( 'Chino', 'js_composer' )               => 'chino',
		esc_html__( 'Mulled Wine', 'js_composer' )         => 'mulled-wine',
		esc_html__( 'Vista Blue', 'js_composer' )          => 'vista-blue',
		esc_html__( 'Grey', 'js_composer' )                => 'grey',
		esc_html__( 'Sky', 'js_composer' )                 => 'sky',
		esc_html__( 'Juicy pink', 'js_composer' )          => 'juicy-pink',
		esc_html__( 'Sandy brown', 'js_composer' )         => 'sandy-brown',
		esc_html__( 'Purple', 'js_composer' )              => 'purple',
	);

	$wvc_shared_colors = apply_filters( 'wvc_shared_colors', $wvc_shared_colors );

	return $wvc_shared_colors;
}

/**
 * Get shared color hex value
 */
function wvc_get_shared_colors_hex() {

	$wvc_shared_colors_hex = array(
		'black'       => '#000000',
		'lightergrey' => '#f7f7f7',
		'darkgrey'    => '#444444',
		'white'       => '#ffffff',
		'orange'      => '#F7BE68',
		'green'       => '#6DAB3C',
		'turquoise'   => '#49afcd',
		'violet'      => '#8D6DC4',
		'pink'        => '#FE6C61',
		'greyblue'    => '#49535a',
		'red'         => '#da4f49',
		'yellow'      => '#e6ae48',
		'blue'        => '#75D69C',
		'peacoc'      => '#4CADC9',
		'chino'       => '#CEC2AB',
		'mulled-wine' => '#50485B',
		'vista-blue'  => '#75D69C',
		'grey'        => '#EBEBEB',
		'sky'         => '#5AA1E3',
		'juicy-pink'  => '#F4524D',
		'sandy-brown' => '#F79468',
		'purple'      => '#B97EBB',
		'accent'      => apply_filters( 'wvc_theme_accent_color', '#0073AA' ),
	);

	$wvc_shared_colors_hex = apply_filters( 'wvc_shared_colors_hex', $wvc_shared_colors_hex );

	return $wvc_shared_colors_hex;
}

/**
 * Get shape divider options
 */
function wvc_get_shape_divider_options() {
	$options = array(
		'tilt'           => esc_html__( 'Angle', 'wolf-visual-composer' ),
		// 'tilt_opacity' => esc_html__( 'Angle Opacity', 'wolf-visual-composer' ),
		'curve'          => esc_html__( 'Curve', 'wolf-visual-composer' ),
		// 'curve_opacity' => esc_html__( 'Curve Opacity', 'wolf-visual-composer' ),
		'grunge_border1' => esc_html__( 'Grunge Border', 'wolf-visual-composer' ),
	);

	$options = array_flip( apply_filters( 'wvc_shape_divider_options', $options ) );

	return $options;
}

/**
 * Add animations
 *
 * @param array $animations Animation array.
 * @return array
 */
function wvc_add_animations( $animations ) {

	$animations[] = array(
		'label'  => esc_html__( 'Custom Animations', 'wolf-visual-composer' ),
		'values' => array(
			'uncoverXLeft'   => array(
				'value' => 'uncoverXLeft',
				'type'  => 'new',
			),
			'uncoverXRight'  => array(
				'value' => 'uncoverXRight',
				'type'  => 'new',
			),

			'uncoverYTop'    => array(
				'value' => 'uncoverYTop',
				'type'  => 'new',
			),

			'uncoverYBottom' => array(
				'value' => 'uncoverYBottom',
				'type'  => 'new',
			),
		),
	);

	return $animations;
}
add_filter( 'vc_param_animation_style_list', 'wvc_add_animations' );

/**
 * New animations
 */
function wvc_get_aos_animations() {
	return array(
		'fade'            => esc_html__( 'Fade', 'wolf-visual-composer' ),
		'fade-up'         => esc_html__( 'Fade Up', 'wolf-visual-composer' ),
		'fade-down'       => esc_html__( 'Fade Down', 'wolf-visual-composer' ),
		'fade-left'       => esc_html__( 'Fade Left', 'wolf-visual-composer' ),
		'fade-right'      => esc_html__( 'Fade Right', 'wolf-visual-composer' ),
		'fade-up-right'   => esc_html__( 'Fade Up Right', 'wolf-visual-composer' ),
		'fade-up-left'    => esc_html__( 'Fade Up Left', 'wolf-visual-composer' ),
		'fade-down-right' => esc_html__( 'Fade Down Right', 'wolf-visual-composer' ),
		'fade-down-left'  => esc_html__( 'Fade Down Left', 'wolf-visual-composer' ),

		'flip-up'         => esc_html__( 'Flip Up', 'wolf-visual-composer' ),
		'flip-down'       => esc_html__( 'Flip Down', 'wolf-visual-composer' ),
		'flip-left'       => esc_html__( 'Flip Left', 'wolf-visual-composer' ),
		'flip-right'      => esc_html__( 'Flip Right', 'wolf-visual-composer' ),

		'slide-up'        => esc_html__( 'Slide Up', 'wolf-visual-composer' ),
		'slide-down'      => esc_html__( 'Slide Down', 'wolf-visual-composer' ),
		'slide-left'      => esc_html__( 'Slide Left', 'wolf-visual-composer' ),
		'slide-right'     => esc_html__( 'Slide Right', 'wolf-visual-composer' ),

		'zoom-in'         => esc_html__( 'Zoom In', 'wolf-visual-composer' ),
		'zoom-in-up'      => esc_html__( 'Zoom In Up', 'wolf-visual-composer' ),
		'zoom-in-down'    => esc_html__( 'Zoom In Down', 'wolf-visual-composer' ),
		'zoom-in-left'    => esc_html__( 'Zoom In Left', 'wolf-visual-composer' ),
		'zoom-in-right'   => esc_html__( 'Zoom In Right', 'wolf-visual-composer' ),
		'zoom-out'        => esc_html__( 'Zoom Out', 'wolf-visual-composer' ),
		'zoom-out-up'     => esc_html__( 'Zoom Out Up', 'wolf-visual-composer' ),
		'zoom-out-down'   => esc_html__( 'Zoom Out Down', 'wolf-visual-composer' ),
		'zoom-out-left'   => esc_html__( 'Zoom Out Left', 'wolf-visual-composer' ),
		'zoom-out-right'  => esc_html__( 'Zoom Out Right', 'wolf-visual-composer' ),
	);
}

/**
 * Check is if new animation engine (AOS)
 *
 * @param string $animation_name Tha animation name.
 * @return bool
 */
function wvc_is_new_animation( $animation_name ) {
	$new_animations = wvc_get_aos_animations();

	if ( isset( $new_animations[ $animation_name ] ) ) {
		return true;
	}
}

/**
 * Filter animation style
 *
 * @param array $animation_syles WPBPB animations.
 * @return array
 */
function wvc_filter_animation_styles( $animation_syles ) {

	$new_animations = array(
		array(
			// 'label' => esc_html__( 'New Animations', 'wolf-visual-composer' ),
			'values' => array_flip( array( 'none' => esc_html__( 'None', 'wolf-visual-composer' ) ) ),
		),
		array(
			'label'  => esc_html__( 'New Animation Engine (beta)', 'wolf-visual-composer' ),
			'values' => array_flip( wvc_get_aos_animations() ),
		),
	);

	// $animation_syles[] = $new_animations;

	// debug( $animation_syles );

	$animation_syles = $new_animations + $animation_syles;

	return $animation_syles;
}
add_filter( 'vc_param_animation_style_list', 'wvc_filter_animation_styles' );

/**
 * Get shared gradient color list in array to allow filtering by theme and stuff
 *
 * @return array
 */
function wvc_get_shared_gradient_colors() {

	$wvc_shared_gradient_colors = array(
		esc_html__( 'Gradient Red', 'wolf-visual-composer' )          => 'gradient-color-3452ff', // red salient
		esc_html__( 'Gradient Red 2', 'wolf-visual-composer' )        => 'gradient-color-588694', // red uncode
		esc_html__( 'Gradient Green', 'wolf-visual-composer' )        => 'gradient-color-105898',
		esc_html__( 'Gradient Green Circle', 'wolf-visual-composer' ) => 'gradient-color-111420',
		esc_html__( 'Gradient Orange', 'wolf-visual-composer' )       => 'gradient-color-470604',
		esc_html__( 'Gradient Violet', 'wolf-visual-composer' )       => 'gradient-color-b900b4',
	);

	$wvc_shared_gradient_colors = apply_filters( 'wvc_shared_gradient_colors', $wvc_shared_gradient_colors );

	return $wvc_shared_gradient_colors;
}

/**
 * Get image sizes in array to allow filtering by theme and stuff
 *
 * @return array
 */
function wvc_get_image_sizes() {

	$wvc_image_sizes = array(
		esc_html__( 'Landscape', 'wolf-visual-composer' )   => apply_filters( 'wvc_landscape_thumbnail_size', '600x360' ),
		esc_html__( 'Square', 'wolf-visual-composer' )      => apply_filters( 'wvc_square_thumbnail_size', '600x600' ),
		esc_html__( 'Portrait', 'wolf-visual-composer' )    => apply_filters( 'wvc_portrait_thumbnail_size', '300x537' ),
		esc_html__( 'Extra large', 'wolf-visual-composer' ) => 'wvc-XL',
		esc_html__( 'Large', 'wolf-visual-composer' )       => 'large',
		esc_html__( 'Medium', 'wolf-visual-composer' )      => 'medium',
		esc_html__( 'Thumbnail', 'wolf-visual-composer' )   => 'thumbnail',
		esc_html__( 'Full', 'wolf-visual-composer' )        => 'full',
		esc_html__( 'Custom', 'wolf-visual-composer' )      => 'custom',
	);

	// apply filters
	$wvc_image_sizes = apply_filters( 'wvc_image_sizes', $wvc_image_sizes );

	return $wvc_image_sizes;
}

/**
 * Get hover effects in array to allow filtering by theme and stuff
 *
 * @return array
 */
function wvc_get_hover_effects() {

	$wvc_hover_effects = array(
		esc_html__( 'Theme Default', 'wolf-visual-composer' )              => 'default',
		esc_html__( 'Opacity', 'wolf-visual-composer' )                    => 'opacity',
		esc_html__( 'Opacity Reversed', 'wolf-visual-composer' )           => 'opacity-reverse',
		esc_html__( 'Zoom In', 'wolf-visual-composer' )                    => 'zoomin',
		esc_html__( 'Zoom Out', 'wolf-visual-composer' )                   => 'zoomout',
		esc_html__( 'Move Left', 'wolf-visual-composer' )                  => 'move-left',
		esc_html__( 'Move Right', 'wolf-visual-composer' )                 => 'move-right',
		esc_html__( 'Move Up', 'wolf-visual-composer' )                    => 'move-up',
		esc_html__( 'Move Down', 'wolf-visual-composer' )                  => 'move-down',
		esc_html__( 'Up', 'wolf-visual-composer' )                         => 'up',
		esc_html__( 'Black and white to colored', 'wolf-visual-composer' ) => 'greyscale',
		esc_html__( 'Colored to Black and white', 'wolf-visual-composer' ) => 'to-greyscale',
	);

	// apply filters
	$wvc_hover_effects = apply_filters( 'wvc_hover_effects', $wvc_hover_effects );

	return $wvc_hover_effects;
}

/**
 * Get query order options
 */
function wvc_get_order_by_category_values() {
	return array(
		esc_html__( 'Count', 'wolf-visual-composer' )  => 'count',
		esc_html__( 'Name', 'wolf-visual-composer' )   => 'name',
		esc_html__( 'Random', 'wolf-visual-composer' ) => 'rand',
	);
}

/**
 * Get query order options
 */
function wvc_get_order_way_category_values() {
	return array(
		esc_html__( 'Descending', 'wolf-visual-composer' ) => 'DESC',
		esc_html__( 'Ascending', 'wolf-visual-composer' )  => 'ASC',
	);
}

/**
 * Get metro pattern options
 */
function wvc_get_metro_patterns() {
	return array_flip(
		apply_filters(
			'wvc_metro_pattern_options',
			array(
				'auto'      => esc_html__( 'Auto', 'wolf-visual-composer' ),
				'pattern-1' => sprintf( esc_html__( 'Pattern %1$d (loop of %2$d)', 'wolf-visual-composer' ), 1, 6 ),
				'pattern-2' => sprintf( esc_html__( 'Pattern %1$d (loop of %2$d)', 'wolf-visual-composer' ), 2, 8 ),
				'pattern-3' => sprintf( esc_html__( 'Pattern %1$d (loop of %2$d)', 'wolf-visual-composer' ), 3, 10 ),
				'pattern-4' => sprintf( esc_html__( 'Pattern %1$d (loop of %2$d)', 'wolf-visual-composer' ), 4, 8 ),
				'pattern-5' => sprintf( esc_html__( 'Pattern %1$d (loop of %2$d)', 'wolf-visual-composer' ), 5, 5 ),
				'pattern-6' => sprintf( esc_html__( 'Pattern %1$d (loop of %2$d)', 'wolf-visual-composer' ), 6, 5 ),
				'pattern-7' => sprintf( esc_html__( 'Pattern %1$d (loop of %2$d)', 'wolf-visual-composer' ), 7, 6 ),
			)
		)
	);
}

/**
 * Get metro image size
 *
 * Get image size depending on metro pattern
 *
 * @param string $pattern
 * @param int    $index
 * @return string $img_size
 */
function wvc_get_metro_img_size( $pattern = 'auto', $i = 0 ) {

	$img_size = 'medium';

	if ( 'auto' === $pattern ) {

		// if ( 0 === $i ) {
			$img_size = 'large';
		// }

	} elseif ( 'pattern-1' === $pattern ) {

		if ( 0 === $i || $i % 6 == 0 || $i % 6 == 3 ) {
			$img_size = 'large';
		}
	} elseif ( 'pattern-2' === $pattern ) {

		if ( 0 === $i || $i % 8 == 1 || $i % 8 == 2 || $i % 8 == 4 || $i % 8 == 5 ) {
			$img_size = 'large';
		}
	} elseif ( 'pattern-3' === $pattern ) {

		if ( $i % 10 === 4 || $i % 10 === 8 ) {
			$img_size = 'large';
		}
	} elseif ( 'pattern-4' === $pattern ) {

		if ( 0 === $i || $i % 8 === 0 || $i % 8 === 2 || $i % 8 === 6 || $i % 8 === 7 ) {
			$img_size = 'large';
		}
	} elseif ( 'pattern-5' === $pattern ) {

		if ( 0 === $i || $i % 5 === 0 ) {
			$img_size = 'large';
		}
	} elseif ( 'pattern-6' === $pattern ) {

		if ( 0 === $i || $i % 5 === 2 ) {
			$img_size = 'large';
		}
	} elseif ( 'pattern-7' === $pattern ) {

		if ( 0 === $i || $i % 6 === 0 || $i % 6 === 1 ) {
			$img_size = 'large';
		}
	}

	return $img_size;
}


/**
 * Get socials services
 *
 * @return array
 */
function wvc_get_socials() {

	$wvc_socials = array(
		'500px',
		'8tracks',
		'airbnb',
		'amazon',
		// 'amplement',
		'apple', // iTunes.
		'bandcamp',
		'bandsintown',
		'behance',
		// 'bitbucket',
		'codepen',
		'dailymotion',
		'deviantart',
		'digg',
		'dribbble',
		// 'dropbox',
		// 'email',
		'envato',
		'etsy',
		'facebook',
		'flickr',
		'foursquare',
		'github',
		'google',
		'twitter',
		'instagram',
		'linkedin',
		'youtube',
		'vimeo',
		'soundcloud',
		'spotify',
		'mailchimp',
		'medium',
		'messenger',
		'mixcloud',
		'imdb',
		'lastfm',
		// 'path',
		'pinterest',
		// 'jsfiddle',
		'tidal',
		'tiktok',
		'tumblr',
		'tripadvisor',
		'skype',
		'snapchat',
		'itunes',
		'delicious',
		'stumbleupon',
		// 'forrst',
		// 'evernote',
		// 'rss',
		'reddit',
		// 'stack-exchange',
		// 'stack-overflow',
		'residentadvisor',
		'reverbnation',
		'snapchat',
		'steam',
		'trello',
		'triplej',
		'viadeo',
		'vk',
		'telegram',
		'tiktok',
		'twitch',
		// 'qq',
		// 'wechat',
		// 'weibo',
		// 'weixin',
		// 'whatsapp',
		// 'windows',
		'wordpress',
		// 'renren',
		// 'tencent-weibo',
		// 'xing',
		'x',
		'yelp',
		'zomato',
		'zerply',
	);

	$wvc_socials = apply_filters( 'wvc_socials', $wvc_socials );

	sort( $wvc_socials );

	// Insert most used at the beggining.
	array_unshift( $wvc_socials, 'facebook', 'twitter', 'x', 'instagram', 'messenger', 'tiktok', 'flickr', 'behance', 'dribbble', 'linkedin', 'youtube', 'vimeo', 'bandcamp', 'spotify', 'soundcloud', 'bandsintown' );

	$wvc_socials[] = 'rss'; // push rss at the end.
	$wvc_socials[] = 'email'; // push email at the end.

	$wvc_socials = array_unique( $wvc_socials ); // remove duplicates.

	return $wvc_socials;
}

/**
 * Get socials services
 *
 * @return array
 */
function wvc_get_team_member_socials() {

	$wvc_team_member_socials = array(
		'facebook',
		'twitter',
		'x',
		'instagram',
		'pinterest',
		'google',
		'dribbble',
		'behance',
		'linkedin',
		'youtube',
		'vimeo',
		'github',
		'tumblr',
		'tiktok',
		'email',
	);

	$wvc_team_member_socials = apply_filters( 'wvc_team_member_socials', $wvc_team_member_socials );

	array_unique( $wvc_team_member_socials );

	return $wvc_team_member_socials;
}

/**
 * Get option
 *
 * Retrieve an option value from the plugin settings
 *
 * @param string $value
 * @param string $default
 * @return string
 */
function wolf_vc_get_option( $index, $name, $default = null ) {

	global $options;

	$wvc_settings = ( get_option( 'wvc_settings' ) && is_array( get_option( 'wvc_settings' ) ) ) ? get_option( 'wvc_settings' ) : array();

	if ( isset( $wvc_settings[ $index ] ) && is_array( $wvc_settings[ $index ] ) ) {

		if ( isset( $wvc_settings[ $index ][ $name ] ) && '' !== $wvc_settings[ $index ][ $name ] ) {

			return $wvc_settings[ $index ][ $name ];

		} elseif ( $default ) {

			return $default;
		}
	} elseif ( $default ) {

		return $default;
	}
}

/**
 * Locate a file and return the path for inclusion.
 *
 * Used to check if the file is in a theme folder of from the original plugin directory
 *
 * @param string $filename
 * @return string
 */
function wvc_locate_shortcode_template( $filename ) {

	if ( is_file( get_stylesheet_directory() . '/' . WVC()->shortcode_template_path() . '/' . untrailingslashit( $filename ) ) ) {

		$file = get_stylesheet_directory() . '/' . WVC()->shortcode_template_path() . '/' . untrailingslashit( $filename );

	} elseif ( is_file( get_template_directory() . '/' . WVC()->shortcode_template_path() . '/' . untrailingslashit( $filename ) ) ) {

		$file = get_template_directory() . '/' . WVC()->shortcode_template_path() . '/' . untrailingslashit( $filename );

	} else {
		$file = WVC()->plugin_path() . '/' . untrailingslashit( $filename );
	}

	// Return what we found
	return apply_filters( 'wvc_locate_shortcode_template', $file );
}

/**
 * Get the URL of an attachment from its id
 *
 * @param int    $id
 * @param string $size
 * @return string $url
 */
function wvc_get_url_from_attachment_id( $id, $size = 'thumbnail', $fallback = true ) {
	if ( is_numeric( $id ) ) {
		$src = wp_get_attachment_image_src( absint( $id ), $size );

		if ( isset( $src[0] ) ) {

			return esc_url( $src[0] );
		} else {
			return wvc_placeholder_img_url( $size );
		}
	}
}

/**
 * Get twitter username from plugin options
 */
function wvc_get_twitter_usename() {
	$default_twitter_username = wolf_vc_get_option( 'socials', 'twitter' );

	if ( $default_twitter_username ) {
		if ( preg_match( '/twitter.com\/[a-zA-Z0-9_]+/', $default_twitter_username, $match ) ) {
			$default_twitter_username = str_replace( 'twitter.com/', '', $match[0] );
			return $default_twitter_username;
		}
	}
}

/**
 * Output animated SVG image
 */
function wvc_animated_svg( $file, $args = array() ) {

	$args = wp_parse_args(
		$args,
		array(
			'class'              => '',
			'animation_duration' => '',
		)
	);

	wp_enqueue_script( 'vivus' );
	wp_enqueue_script( 'wvc-vivus' );

	extract( $args );

	$class .= ' wvc-vivus wvc-svg-icon';

	$rand = 'wvc-svg-' . rand( 0, 999999 ); // unique ID

	$output = '';

	$output .= '<span id="' . esc_attr( $rand ) . '" class="' . wvc_sanitize_html_classes( $class ) . '"
	data-file="' . esc_url( $file ) . '"';

	if ( $animation_duration ) {
		$output .= ' data-animation-duration="' . absint( $animation_duration ) . '"';
	}

	$output .= '></span>';

	return $output;
}

/**
 * wvc_get_current_post_type function.
 */
function wvc_get_current_post_type() {
	global $post, $typenow, $current_screen;

	if ( $post && $post->post_type ) {

		return $post->post_type;

	} elseif ( $typenow ) {

		return $typenow;

	} elseif ( $current_screen && $current_screen->post_type ) {

		return $current_screen->post_type;

	} elseif ( isset( $_REQUEST['post_type'] ) ) {

		return sanitize_key( $_REQUEST['post_type'] );

	} elseif ( isset( $_GET['post'] ) && $_GET['post'] != -1 ) {

		$current_post = get_post( $_GET['post'] );

		return $current_post->post_type;
	} else {
		return null;
	}
}

/**
 * Get hero image ID
 *
 * @return string URL
 */
function wvc_get_hero_image_id() {

	if ( is_random_header_image() ) {

		$data = _get_random_header_data();

	} else {
		// Get the header image data
		$data = get_theme_mod( 'header_image_data' );
	}

	$data = is_object( $data ) ? get_object_vars( $data ) : $data;

	// Now check to see if there is an id
	$header_img_id = is_array( $data ) && isset( $data['attachment_id'] ) ? $data['attachment_id'] : false;

	return $header_img_id;
}

/**
 * Get placeholder image URL
 */
function wvc_placeholder_img_url( $img_size ) {

	if ( in_array( $img_size, array( 'thumbnail', 'medium', 'large', 'wvc-XL', 'wvc-photo', 'full' ) ) ) {

		switch ( $img_size ) {
			case 'wvc-XL':
				$img_size = '2000x1500';
				break;
			case 'wvc-photo':
				$img_size = '500x500';
				break;
			case 'full':
				$img_size = '2000x1500';
				break;
			case 'thumbnail':
				$img_size = get_option( 'thumbnail_size_w' ) . 'x' . get_option( 'thumbnail_size_h' );
				break;
			case 'medium':
				$img_size = get_option( 'medium_size_w' ) . 'x' . get_option( 'medium_size_h' );
				break;
			case 'large':
				$img_size = get_option( 'large_size_w' ) . 'x' . get_option( 'large_size_h' );
				break;
		}
	}

	if ( $img_size ) {
		$formatted_size = str_replace( 'x', '/', $img_size );
		return 'https://unsplash.it/' . $formatted_size . '/?image=' . rand( 1, 1084 );
	}
}

/**
 * Get current page URL
 */
function wvc_get_current_url() {
	global $wp;
	return esc_url( home_url( add_query_arg( array(), $wp->request ) ) );
}

/**
 * Returns fallback from placeholder if image is missing
 */
function wvc_placeholder_img( $img_size, $class = '' ) {

	if ( wvc_placeholder_img_url( $img_size ) ) {
		return '<img class="' . wvc_sanitize_html_classes( $class ) . '" src="' . wvc_placeholder_img_url( $img_size ) . '" alt="placeholder" title="' . esc_html__( 'Image is missing', 'wolf-visual-composer' ) . '">';
	}
}

/**
 * Add to cart tag
 *
 * @param int    $product_id
 * @param string $text link text content
 * @param string $class button class
 * @return string
 */
function wvc_add_to_cart( $product_id, $classes = '', $text = '' ) {
	// <a rel="nofollow" href="/factory/retine/shop/shop-boxed/?add-to-cart=60" data-quantity="1" data-product_id="60" data-product_sku="" class="button product_type_simple add_to_cart_button ajax_add_to_cart"><span>Add to cart</span></a>
	$wc_url = untrailingslashit( wvc_get_current_url() ) . '/?add-to-cart=' . absint( $product_id );

	$classes .= ' product_type_simple add_to_cart_button ajax_add_to_cart';

	return '<a
		href="' . esc_url( $wc_url ) . '"
		rel="nofollow"
		data-quantity="1" data-product_id="' . absint( $product_id ) . '"
		class="' . wvc_sanitize_html_classes( $classes ) . '">' . $text . '</a>';
}

/**
 * Get the rev slider list
 *
 * @param string $alias
 * @return array $result
 * @see http://themeforest.net/forums/thread/add-rev-slider-to-theme-please-authors-reply/97711
 */
function wvc_get_revslider_id_by_alias( $alias ) {

	$slider_id = null;

	if ( class_exists( 'RevSlider' ) ) {

		$theslider = new RevSlider();

		$arrSliders = $theslider->getArrSliders();

		foreach ( $arrSliders as $slider ) {
			$current_alias = $slider->getAlias();
			// $current_title = $slider->getTitle();
			$current_id = $slider->getId();

			if ( esc_attr( $alias ) === $slider->getAlias() ) {
				$slider_id = $slider->getId();
				break;
			}
		}
	}

	return $slider_id;
}

/**
 * Get first revslider alias in the content
 *
 * @param string $content
 * @return string $alias
 */
function wvc_get_first_revslider_id( $content ) {
	if ( preg_match( '/\[rev_slider_vc alias="[a-zA-Z0-9_-]+"\]/', $content, $match ) ) {
		if ( isset( $match[0] ) ) {
			$params = shortcode_parse_atts( $match[0] );
			if ( isset( $params[1] ) ) {
				$alias = str_replace( array( 'alias="', '"]' ), '', $params );

				if ( isset( $alias[1] ) ) {
					return absint( wvc_get_revslider_id_by_alias( $alias[1] ) );
				}
			}
		}
	}
}

/**
 * Breadcrumb function
 */
function wvc_breadcrumb() {

	global $post, $wp_query;

	$output = '';

	if ( ! is_front_page() ) {

		$position  = 1;
		$delimiter = '<span class="wvc-breadcrumb-delimiter">' . apply_filters( 'wvc_breadcrumb_delimiter', '/' ) . '</span>';
		$before    = '';
		$after     = '';

		$output .= '<ol class="wvc-breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">';

		$output .= '<li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem"><a
       itemprop="item" href="';
		$output .= esc_url( home_url( '/' ) );
		$output .= '"><span itemprop="name">';
		// $output .= esc_html__( 'Home', 'wolf-visual-composer' );
		if ( get_option( 'page_on_front' ) ) {
			$output .= get_the_title( get_option( 'page_on_front' ) );
		} else {
			$output .= esc_html__( 'Home', 'wolf-visual-composer' );
		}
		$output .= "</span></a><meta itemprop='position' content='" . $position++ . "' /></li>$delimiter";

		if ( 'post' == get_post_type() && ! wvc_is_blog_index() ) {

			$output     .= '<li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . wvc_get_blog_url() . '"><span itemprop="name">' . get_the_title( get_option( 'page_for_posts' ) ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>';
				$output .= $delimiter;
		}

		if ( wvc_is_woocommerce_page() && is_shop() ) {
			$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><span itemprop="name">';
			$output .= get_the_title( wvc_get_woocommerce_shop_page_id() );
			$output .= '</span><meta itemprop="position" content="' . $position++ . '" /></li>';
		}

		if ( wvc_is_woocommerce_page() && is_product_category() ) {

			$shop_page_id = wc_get_page_id( 'shop' );

			$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '" ><span itemprop="name">' . get_the_title( $shop_page_id ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>' . $delimiter;

			$current_term = $wp_query->get_queried_object();
			$ancestors    = array_reverse( get_ancestors( $current_term->term_id, 'product_cat' ) );

			foreach ( $ancestors as $ancestor ) {

				$ancestor = get_term( $ancestor, 'product_cat' );

				$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_term_link( $ancestor ) . '"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>' . $delimiter;
			}

			$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><span itemprop="name">' . sanitize_text_field( $before . esc_html( $current_term->name ) . $after ) . '</span><meta itemprop="position" content="' . $position++ . '" /></li>';

		} elseif ( wvc_is_woocommerce_page() && is_product_tag() ) {

			$shop_page_id = wc_get_page_id( 'shop' );
			$output      .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_permalink( $shop_page_id ) . '"><span itemprop="name">' . get_the_title( $shop_page_id ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>' . $delimiter;

			$queried_object = $wp_query->get_queried_object();

			$output .= $before . esc_html__( 'Products tagged &ldquo;', 'wolf-visual-composer' ) . $queried_object->name . '&rdquo;' . $after;

		} elseif ( wvc_is_woocommerce_page() && ! is_singular( 'product' ) && ! is_shop() ) {

			$shop_page_id = wc_get_page_id( 'shop' );
			$output      .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '"><span itemprop="name">' . get_the_title( $shop_page_id ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>';
			$output      .= $delimiter;
		}

		if ( is_category() ) {

			$cat_obj       = $wp_query->get_queried_object();
			$this_category = get_category( $cat_obj->term_id );

			if ( 0 != $this_category->parent ) {
				$parent_category = get_category( $this_category->parent );
				if ( ( $parents = get_category_parents( $parent_category, true, $after . $delimiter . $before ) ) && ! is_wp_error( $parents ) ) {

					$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><span itemprop="name">' . sanitize_text_field( $before . rtrim( $parents, $after . $delimiter . $before ) . $after ) . '</span>
    <meta itemprop="position" content="' . $position++ . '" /></li>' . $delimiter;
				}
			}

			$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><span itemprop="name">' . sanitize_text_field( $before . single_cat_title( '', false ) . $after ) . '</span>
    <meta itemprop="position" content="' . $position++ . '" /></li>';

		} elseif ( is_tag() ) {

			$output .= get_the_tag_list( '', $delimiter );

		} elseif ( is_author() ) {

			$output .= get_the_author();

		} elseif ( is_day() ) {

			$output .= get_the_date();

		} elseif ( is_month() ) {

			$output .= get_the_date( 'F Y' );

		} elseif ( is_year() ) {

			$output .= get_the_date( 'Y' );

		} elseif ( is_tax( 'work_type' ) ) {

			$portfolio_page_id = wolf_portfolio_get_page_id();
			$output           .= '<a href="' . get_permalink( $portfolio_page_id ) . '">' . get_the_title( $portfolio_page_id ) . '</a>' . $delimiter;

			$the_tax = get_taxonomy( get_query_var( 'taxonomy' ) );
			if ( $the_tax && $wp_query && isset( $wp_query->queried_object->name ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><span itemprop="name">' . sanitize_text_field( $wp_query->queried_object->name ) . '</span>
    <meta itemprop="position" content="' . $position++ . '" />';
			}
		} elseif ( is_tax( 'gallery_type' ) ) {

			$albums_page_id = wolf_albums_get_page_id();
			$output        .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><a itemprop="item href="' . get_permalink( $albums_page_id ) . '"><span itemprop="name">' . get_the_title( $albums_page_id ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>' . $delimiter;

			$the_tax = get_taxonomy( get_query_var( 'taxonomy' ) );
			if ( $the_tax && $wp_query && isset( $wp_query->queried_object->name ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><span itemprop="name">' . sanitize_text_field( $wp_query->queried_object->name ) . '</span>
    <meta itemprop="position" content="' . $position++ . '" /></li>';
			}
		} elseif ( is_tax( 'video_type' ) ) {

			$videos_page_id = wolf_videos_get_page_id();
			$output        .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><a itemprop="item href="' . get_permalink( $videos_page_id ) . '"><span itemprop="name">' . get_the_title( $videos_page_id ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>' . $delimiter;

			$the_tax = get_taxonomy( get_query_var( 'taxonomy' ) );
			if ( $the_tax && $wp_query && isset( $wp_query->queried_object->name ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><span itemprop="name">' . sanitize_text_field( $wp_query->queried_object->name ) . '</span>
    <meta itemprop="position" content="' . $position++ . '" />';
			}
		} elseif ( is_tax( 'plugin_cat' ) ) {

			$plugins_page_id = wolf_plugins_get_page_id();
			$output         .= '<a href="' . get_permalink( $plugins_page_id ) . '">' . get_the_title( $plugins_page_id ) . '</a>' . $delimiter;

			$the_tax = get_taxonomy( get_query_var( 'taxonomy' ) );
			if ( $the_tax && $wp_query && isset( $wp_query->queried_object->name ) ) {

				$output .= sanitize_text_field( $wp_query->queried_object->name );

			}
		} elseif ( is_tax( 'theme_cat' ) ) {

			$themes_page_id = wolf_themes_get_page_id();
			$output        .= '<a href="' . get_permalink( $themes_page_id ) . '">' . get_the_title( $themes_page_id ) . '</a>' . $delimiter;

			$the_tax = get_taxonomy( get_query_var( 'taxonomy' ) );
			if ( $the_tax && $wp_query && isset( $wp_query->queried_object->name ) ) {

				$output .= esc_attr( $wp_query->queried_object->name );

			}
		} elseif ( is_tax() && ! is_tax( 'product_cat' ) && ! is_tax( 'product_tag' ) ) {

			$the_tax = get_taxonomy( get_query_var( 'taxonomy' ) );
			if ( $the_tax && $wp_query && isset( $wp_query->queried_object->name ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><span itemprop="name">' . esc_attr( $wp_query->queried_object->name ) . '</span>
    <meta itemprop="position" content="' . $position++ . '" /></li>';

			}
		} elseif ( is_search() ) {

			if ( wvc_is_woocommerce_page() ) {
				$output .= $delimiter;
			}

			// $output .= '<a href="' . get_permalink( $post->post_parent ) . '">';
			$output .= esc_html__( 'Search', 'wolf-visual-composer' );
		}

		if ( is_attachment() ) {

			esc_html_e( 'Attachment', 'wolf-visual-composer' );

			$output .= $delimiter;

			$output .= empty( $post->post_parent ) ? get_the_title() : '<a href="' . get_permalink( $post->post_parent ) . '">' . get_the_title( $post->post_parent ) . '</a>' . $delimiter . get_the_title();

		} elseif ( is_page() ) {

			if ( ! empty( $post->post_parent ) && ! wvc_is_woocommerce_page() ) {

				$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_permalink( $post->post_parent ) . '"><span itemprop="name">' . get_the_title( $post->post_parent ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>';
				$output .= $delimiter;

			}

			$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><span itemprop="name">' . get_the_title() . '</span><meta itemprop="position" content="' . $position++ . '" /></li>';

		} elseif ( is_search() ) {

			$output .= $delimiter;

			$output .= ( isset( $_GET['s'] ) ) ? esc_attr( $_GET['s'] ) : esc_html__( 'Search results', 'wolf-visual-composer' );
		}

		if ( is_single() ) {

			if ( is_singular( 'work' ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_permalink( wolf_portfolio_get_page_id() ) . '"><span itemprop="name">' . get_the_title( wolf_portfolio_get_page_id() ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>';
				$output .= $delimiter;

				$output .= get_the_term_list( $post->ID, 'work_type', '', $delimiter, '' );

				if ( has_term( '', 'work_type' ) ) {
					$output .= $delimiter;
				}
			} elseif ( is_singular( 'video' ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_permalink( wolf_videos_get_page_id() ) . '"><span itemprop="name">' . get_the_title( wolf_videos_get_page_id() ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>';
				$output .= $delimiter;

				$output .= get_the_term_list( $post->ID, 'video_type', '', $delimiter, '' );

				if ( has_term( '', 'video_type' ) ) {
					$output .= $delimiter;
				}
			} elseif ( is_singular( 'gallery' ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_permalink( wolf_albums_get_page_id() ) . '"><span itemprop="name"' . get_the_title( wolf_albums_get_page_id() ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>';

				$output .= $delimiter;

				$output .= get_the_term_list( $post->ID, 'gallery_type', '', $delimiter, '' );

				if ( has_term( '', 'gallery_type' ) ) {

					$output .= $delimiter;
				}
			} elseif ( is_singular( 'plugin' ) ) {

				$output .= '<a href="' . get_permalink( wolf_plugins_get_page_id() ) . '">' . get_the_title( wolf_plugins_get_page_id() ) . '</a>';
				$output .= $delimiter;

				$output .= get_the_term_list( $post->ID, 'plugin_cat', '', $delimiter, '' );

				// if ( has_term( '', 'plugin_cat' ) ) {
					$output .= $delimiter;
				// }

			} elseif ( is_singular( 'product' ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '"><span itemprop="name">' . get_the_title( wc_get_page_id( 'shop' ) ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>';

				$output .= $delimiter;

				if ( $terms = wc_get_product_terms(
					$post->ID,
					'product_cat',
					array(
						'orderby' => 'parent',
						'order'   => 'DESC',
					)
				) ) {
					$main_term = $terms[0];
					$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
					$ancestors = array_reverse( $ancestors );

					foreach ( $ancestors as $ancestor ) {
						$ancestor = get_term( $ancestor, 'product_cat' );

						if ( ! is_wp_error( $ancestor ) && $ancestor ) {
							$output .= '<li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_term_link( $ancestor ) . '"><span itemprop="name">' . $ancestor->name . '</span></a><meta itemprop="position" content="' . $position++ . '" /><li>' . $delimiter;
						}
					}

					$output .= '<li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_term_link( $main_term ) . '"><span itemprop="name">' . $main_term->name . '</span></a><meta itemprop="position" content="' . $position++ . '" /><li>' . $delimiter;
				}
			} elseif ( is_singular( 'event' ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem"><a itemprop="item href="' . get_permalink( wolf_events_get_page_id() ) . '"><span itemprop="name">' . get_the_title( wolf_events_get_page_id() ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /><li>';
				$output .= $delimiter;

				// $output .= '<a href="' . get_permalink( get_the_ID() ) . '">' . get_the_title( get_the_ID() ) . '</a>';
				// $output .= $delimiter;

				// $output .= get_the_term_list( $post->ID, 'gallery_type', '', $delimiter, '');

				// if ( has_term( '', 'gallery_type' ) )
				// $output .= $delimiter;

			} elseif ( is_singular( 'release' ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_permalink( wolf_discography_get_page_id() ) . '"><span itemprop="name">' . get_the_title( wolf_discography_get_page_id() ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /><li>';

				if ( has_term( '', 'band' ) ) {
					$output .= $delimiter;
				}

				$output .= get_the_term_list( $post->ID, 'band', '', $delimiter, '' );

				// if ( has_term( '', 'band' ) ) {
					$output .= $delimiter;
				// }

			} elseif ( is_singular( 'artist' ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . get_permalink( wolf_artists_get_page_id() ) . '"><span itemprop="name">' . get_the_title( wolf_artists_get_page_id() ) . '</span></a><meta itemprop="position" content="' . $position++ . '" /><li>';

				if ( has_term( '', 'artist_genre' ) ) {
					$output .= $delimiter;
				}

				$output .= get_the_term_list( $post->ID, 'artist_genre', '', $delimiter, '' );

				// if ( has_term( '', 'band' ) ) {
					$output .= $delimiter;
				// }

			} elseif ( is_singular( 'wvc_content_block' ) ) {

				$output .= '<li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem"><span itemprop="name">' . esc_html__( 'Content Block', 'wolf-visual-composer' ) . '</span>
    <meta itemprop="position" content="' . $position++ . '" /></li>';

				$output .= $delimiter;

			} elseif ( is_singular( 'wpm_playlist' ) ) {

				$output .= esc_html__( 'Playlists', 'wolf-visual-composer' );
				$output .= $delimiter;

			} else {
				$output .= '<li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . wvc_get_first_category_url() . '"><span itemprop="name">' . wvc_get_first_category() . '</span></a><meta itemprop="position" content="' . $position++ . '" /></li>';
				$output .= $delimiter;
			}

			$output .= '<li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem"><span itemprop="name">' . wvc_sample( get_the_title(), 10 ) . '</span><meta itemprop="position" content="' . $position++ . '" /></lI>';

		} elseif (
			$wp_query && isset( $wp_query->queried_object->ID )
			&& $wp_query->queried_object->ID == get_option( 'page_for_posts' )
		) {

			$output .= '<li itemprop="itemListElement" itemscope
      				itemtype="https://schema.org/ListItem"><span itemprop="name">' . sanitize_text_field( $wp_query->queried_object->post_title ) . '</span><meta itemprop="position" content="' . $position++ . '" /></lI>';
		}

		$output .= '</ol>';
	}

	return $output;
}

/**
 * Get lists of categories.
 *
 * @see js_composer/include/classes/vendors/class-vc-vendor-woocommerce.php
 *
 * @param $parent_id
 * @param array $array
 * @param $level
 * @param array $dropdown - passed by  reference
 */
function wvc_get_category_childs_full( $parent_id, $array, $level, &$dropdown ) {
	$keys = array_keys( $array );
	$i    = 0;
	while ( $i < count( $array ) ) {
		$key  = $keys[ $i ];
		$item = $array[ $key ];
		++$i;
		if ( $item->category_parent == $parent_id ) {
			$name       = str_repeat( '- ', $level ) . $item->name;
			$value      = $item->term_id;
			$dropdown[] = array(
				'label' => $name . ' (' . $item->term_id . ')',
				'value' => $value,
			);
			unset( $array[ $key ] );
			$array = wvc_get_category_childs_full( $item->term_id, $array, $level + 1, $dropdown );
			$keys  = array_keys( $array );
			$i     = 0;
		}
	}

	return $array;
}

/**
 * Get product category dropdown options
 */
function wvc_get_product_cat_dropdown_options() {

	$product_categories_dropdown = array();
	$product_cat_args            = array(
		'type'         => 'post',
		'child_of'     => 0,
		'parent'       => '',
		'orderby'      => 'name',
		'order'        => 'ASC',
		'hide_empty'   => false,
		'hierarchical' => 1,
		'exclude'      => '',
		'include'      => '',
		'number'       => '',
		'taxonomy'     => 'product_cat',
		'pad_counts'   => false,

	);

	$categories = get_categories( $product_cat_args );

	$product_categories_dropdown = array();
	wvc_get_category_childs_full( 0, $categories, 0, $product_categories_dropdown );

	return $product_categories_dropdown;
}

/**
 * Update option
 *
 * Update an option value from the plugin settings
 *
 * @param string $value
 * @param string $default
 * @return string
 */
function wvc_update_option( $index, $key, $value ) {

	$wvc_settings = ( get_option( 'wvc_settings' ) && is_array( get_option( 'wvc_settings' ) ) ) ? get_option( 'wvc_settings' ) : array();

	if ( ! isset( $wvc_settings[ $index ] ) ) {
		$wvc_settings[ $index ] = array();
	}

	$wvc_settings[ $index ][ $key ] = $value;

	update_option( 'wvc_settings', $wvc_settings );
}

if ( ! function_exists( 'dd' ) ) {
	function dd( $var ) {
		echo '<br><pre class="wvc-debug">';
		print_r( $var );
		echo '</pre>';
		die();
	}
}

/**
 * New MailChimp API wrapper
 */
function wvc_mailchimp_add_subscriber( $data ) {

	require_once WVC_DIR . '/vendor/autoload.php';

	$return = '';

	$mailchimp = new MailchimpMarketing\ApiClient();
	$api_key   = apply_filters( 'wvc_mailchimp_api_key', wolf_vc_get_option( 'mailchimp', 'mailchimp_api_key' ) );

	$list_id   = isset( $data['list_id'] ) ? esc_attr( $data['list_id'] ) : '';
	$email     = isset( $data['email'] ) ? esc_attr( $data['email'] ) : '';
	$firstname = isset( $data['firstname'] ) ? esc_attr( $data['firstname'] ) : '';
	$lastname  = isset( $data['lastname'] ) ? esc_attr( $data['lastname'] ) : '';

	$server_prefix = substr( $api_key, strpos( $api_key, '-' ) + 1 );

	if ( $firstname || $lastname ) {
		$send['merge_fields'] = array(
			'FNAME' => $firstname,
			'LNAME' => $lastname,
		);
	}

	$send = array(
		'apiKey' => esc_attr( $api_key ),
		'server' => esc_attr( $server_prefix ),
	);

	$mailchimp->setConfig( $send ); // Throws an Error object in PHP 7 or higger.
	try {
		$response = $mailchimp->lists->addListMember(
			$list_id,
			array(
				'email_address' => $email,
				'status'        => 'pending',
			)
		);
	} catch ( MailchimpMarketing\ApiException $e ) {
		// dd($e);
	}
}

// wvc_mailchimp_add_subscriber(
// array(
// 'email' => 'constantin.saguin@protonmail.com',
// 'list_id' => 'eb6ab49cd8',
// )
// );

/**
 * MailChimp add subscriber
 *
 * @param array $data
 * @return void
 * @link https://stackoverflow.com/questions/30481979/adding-subscribers-to-a-list-using-mailchimps-api-v3
 */
function wvc_sync_mailchimp( $data ) {

	$api_key = apply_filters( 'wvc_mailchimp_api_key', wolf_vc_get_option( 'mailchimp', 'mailchimp_api_key' ) );
	$list_id = esc_attr( $data['list_id'] );
	$email   = $data['email'];

	$status       = 'subscribed'; // we are going to talk about it in just a little bit
	$merge_fields = array(
		'FNAME' => $data['firstname'],
		'LNAME' => $data['lastname'],
	); // FNAME, LNAME or something else

	// start our Mailchimp connection
	$connection = curl_init();
	curl_setopt(
		$connection,
		CURLOPT_URL,
		'https://' . substr( $api_key, strpos( $api_key, '-' ) + 1 ) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5( strtolower( $email ) )
	);
	curl_setopt( $connection, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Authorization: Basic ' . base64_encode( 'user:' . $api_key ) ) );
	curl_setopt( $connection, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $connection, CURLOPT_CUSTOMREQUEST, 'PUT' );
	curl_setopt( $connection, CURLOPT_POST, true );
	curl_setopt( $connection, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt(
		$connection,
		CURLOPT_POSTFIELDS,
		json_encode(
			array(
				'apikey'        => $api_key,
				'email_address' => $email,
				'status'        => $status,
				'merge_fields'  => $merge_fields,
			// 'tags' => array( 'Coffee', 'Snowboard' ) // you can specify some tags here as well
			)
		)
	);

	$result = curl_exec( $connection );

	// echo 'OK';

	// var_dump( $result );
}
