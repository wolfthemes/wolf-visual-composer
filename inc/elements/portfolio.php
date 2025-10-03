<?php
/**
 * Portfolio plugin
 *
 * @author WolfThemes
 * @category Core
 * @package WolfWPBakeryPageBuilderExtension/Elements
 * @version 3.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Wolf_Portfolio' ) ) {
	return;
}

vc_map(
	array(
		'name'        => esc_html__( 'Portfolio', 'wolf-visual-composer' ),
		'description' => esc_html__( 'Display your last works from the portfolio plugin', 'wolf-visual-composer' ),
		'base'        => 'wolf_last_works',
		'category'    => esc_html__( 'Content', 'wolf-visual-composer' ),
		'icon'        => 'dashicons-before dashicons-admin-customizer',
		'params'      => array(

			array(
				'type'       => 'wvc_textfield',
				'heading'    => esc_html__( 'Count', 'wolf-visual-composer' ),
				'param_name' => 'count',
				'value'      => 3,
			),

			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Columns', 'wolf-visual-composer' ),
				'param_name' => 'col',
				'value'      => array( 3, 2, 4 ),
				'dependency' => array(
					'element' => 'layout',
					'value'   => array( 'classic', 'grid', 'grid-square', 'masonry' ),
				),
			),

			array(
				'type'        => 'wvc_textfield',
				'heading'     => esc_html__( 'Category', 'wolf-visual-composer' ),
				'param_name'  => 'category',
				'description' => esc_html__( 'Include only one or several categories. Paste category slug(s) separated by a comma', 'wolf-visual-composer' ),
			),
		),
	)
);
