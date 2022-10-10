<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'blockify_editor_script', NS . 'add_block_styles' );
/**
 * Add block styles config.
 *
 * @todo  Use rest api instead of inline.
 *
 * @since 0.4.0
 *
 * @param array $config Default config.
 *
 * @return array
 */
function add_block_styles( array $config ): array {
	$config['blockStyles']['register'] = [
		[
			'type'  => 'core/columns',
			'name'  => 'boxed',
			'label' => __( 'Boxed', 'blockify' ),
		],
		[
			'type'  => 'core/column',
			'name'  => 'boxed',
			'label' => __( 'Boxed', 'blockify' ),
		],
		[
			'type'  => 'core/group',
			'name'  => 'boxed',
			'label' => __( 'Boxed', 'blockify' ),
		],
		[
			'type'  => 'core/list',
			'name'  => 'numbered',
			'label' => __( 'Numbered', 'blockify' ),
		],
		[
			'type'  => 'core/list',
			'name'  => 'checklist',
			'label' => __( 'Checklist', 'blockify' ),
		],
		[
			'type'  => 'core/list',
			'name'  => 'checklist',
			'label' => __( 'Check Circle', 'blockify' ),
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
			'type'  => 'core/search',
			'name'  => 'toggle',
			'label' => __( 'Toggle', 'blockify' ),
		],
		[
			'type'  => 'core/paragraph',
			'name'  => 'sub-heading',
			'label' => __( 'Sub Heading', 'blockify' ),
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
	];

	$config['blockStyles']['unregister'] = [
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
	];

	return $config;
}
