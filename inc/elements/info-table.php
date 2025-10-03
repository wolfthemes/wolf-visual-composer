<?php
/**
 * Info Table
 *
 * @author WolfThemes
 * @category Core
 * @package WolfWPBakeryPageBuilderExtension/Elements
 * @version 3.2.8
 */

defined( 'ABSPATH' ) || exit;

// Audio
vc_map(
	array(
		'name'        => esc_html__( 'Info Table', 'wolf-visual-composer' ),
		'base'        => 'wvc_info_table',
		'description' => esc_html__( 'A simple two columns table to present an item', 'wolf-visual-composer' ),
		'icon'        => 'fa fa-th',
		'category'    => esc_html__( 'Content', 'wolf-visual-composer' ),
		'params'      => array(
			// params group
			array(
				'type'       => 'param_group',
				'value'      => '',
				'param_name' => 'values',
				// Note params is mapped inside param-group:
				'params'     => array(
					array(
						'type'       => 'textfield',
						'value'      => '',
						'heading'    => esc_html__( 'Label', 'wolf-visual-composer' ),
						'param_name' => 'label',
					),
					array(
						'type'       => 'textfield',
						'value'      => '',
						'heading'    => esc_html__( 'Value', 'wolf-visual-composer' ),
						'param_name' => 'value',
					),
				),
			),
		),
	)
);

class WPBakeryShortCode_Wvc_Info_Table extends WPBakeryShortCode {}
