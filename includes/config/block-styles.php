<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function wp_get_global_settings;

add_filter( 'blockify_editor_data', NS . 'register_block_styles' );
/**
 * Adds default blocks styles.
 *
 * @since 0.9.10
 *
 * @param array $config Blockify editor config.
 *
 * @return array
 */
function register_block_styles( array $config ): array {
	$config['blockStyles'] = [
		'register'   => [
			[
				'type'  => 'core/buttons',
				'name'  => 'surface',
				'label' => __( 'Surface', 'blockify' ),
			],
			[
				'type'  => 'core/button',
				'name'  => 'ghost',
				'label' => __( 'Ghost', 'blockify' ),
			],
			[
				'type'  => 'core/code',
				'name'  => 'surface',
				'label' => __( 'Surface', 'blockify' ),
			],
			[
				'type'  => 'core/code',
				'name'  => 'light',
				'label' => __( 'Light', 'blockify' ),
			],
			[
				'type'  => 'core/code',
				'name'  => 'dark',
				'label' => __( 'Dark', 'blockify' ),
			],
			[
				'type'  => 'core/column',
				'name'  => 'dark',
				'label' => __( 'Dark', 'blockify' ),
			],
			[
				'type'  => 'core/column',
				'name'  => 'light',
				'label' => __( 'Light', 'blockify' ),
			],
			[
				'type'  => 'core/columns',
				'name'  => 'dark',
				'label' => __( 'Dark', 'blockify' ),
			],
			[
				'type'  => 'core/columns',
				'name'  => 'light',
				'label' => __( 'Light', 'blockify' ),
			],
			[
				'type'  => 'core/columns',
				'name'  => 'surface',
				'label' => __( 'Surface', 'blockify' ),
			],
			[
				'type'  => 'core/column',
				'name'  => 'surface',
				'label' => __( 'Surface', 'blockify' ),
			],
			[
				'type'  => 'core/group',
				'name'  => 'light',
				'label' => __( 'Light', 'blockify' ),
			],
			[
				'type'  => 'core/group',
				'name'  => 'dark',
				'label' => __( 'Dark', 'blockify' ),
			],
			[
				'type'  => 'core/group',
				'name'  => 'surface',
				'label' => __( 'Surface', 'blockify' ),
			],
			[
				'type'  => 'core/list',
				'name'  => 'checklist',
				'label' => __( 'Checklist', 'blockify' ),
			],
			[
				'type'  => 'core/list',
				'name'  => 'checklist-circle',
				'label' => __( 'Checklist Circle', 'blockify' ),
			],
			[
				'type'  => 'core/list',
				'name'  => 'square',
				'label' => __( 'Square', 'blockify' ),
			],
			[
				'type'  => 'core/list',
				'name'  => 'accordion',
				'label' => __( 'Accordion', 'blockify' ),
			],
			[
				'type'  => 'core/list-item',
				'name'  => 'surface',
				'label' => __( 'Surface', 'blockify' ),
			],
			[
				'type'  => 'core/navigation-submenu',
				'name'  => 'mega-menu',
				'label' => __( 'Mega Menu', 'blockify' ),
			],
			[
				'type'  => 'core/paragraph',
				'name'  => 'sub-heading',
				'label' => __( 'Sub Heading', 'blockify' ),
			],
			[
				'type'  => 'core/paragraph',
				'name'  => 'notice',
				'label' => __( 'Notice', 'blockify' ),
			],
			[
				'type'  => 'core/post-terms',
				'name'  => 'badges',
				'label' => __( 'Badges', 'blockify' ),
			],
			[
				'type'  => 'core/read-more',
				'name'  => 'button',
				'label' => __( 'Button', 'blockify' ),
			],
			[
				'type'  => 'core/spacer',
				'name'  => 'angle',
				'label' => __( 'Angle', 'blockify' ),
			],
			[
				'type'  => 'core/spacer',
				'name'  => 'curve',
				'label' => __( 'Curve', 'blockify' ),
			],
			[
				'type'  => 'core/spacer',
				'name'  => 'round',
				'label' => __( 'Round', 'blockify' ),
			],
			[
				'type'  => 'core/spacer',
				'name'  => 'wave',
				'label' => __( 'Wave', 'blockify' ),
			],
			[
				'type'  => 'core/spacer',
				'name'  => 'fade',
				'label' => __( 'Fade', 'blockify' ),
			],
			[
				'type'  => 'core/quote',
				'name'  => 'surface',
				'label' => __( 'Surface', 'blockify' ),
			],
		],

		'unregister' => [
			[
				'type' => 'core/image',
				'name' => [ 'rounded', 'default' ],
			],
			[
				'type' => 'core/site-logo',
				'name' => [ 'default', 'rounded' ],
			],
			[
				'type' => 'core/separator',
				'name' => [ 'wide', 'dots' ],
			],
		],
	];

	$button_secondary = wp_get_global_settings()['custom']['buttonSecondary'] ?? null;

	if ( $button_secondary ) {
		$config['blockStyles']['register'][] = [
			'type'  => 'core/button',
			'name'  => 'secondary',
			'label' => __( 'Secondary', 'blockify' ),
		];
	}

	return $config;
}
