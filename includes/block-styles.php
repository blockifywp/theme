<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( SLUG . '_editor_script', NS . 'register_block_styles' );
/**
 * Adds default blocks styles.
 *
 * @since 1.0.0
 *
 * @param array $config Blockify editor config.
 *
 * @return array
 */
function register_block_styles( array $config ): array {
	$config['blockStyles'] = [
		'register'   => [
			[
				'type'  => 'core/code',
				'name'  => 'surface',
				'label' => __( 'Surface', 'blockify' ),
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

	return $config;
}
