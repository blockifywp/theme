<?php

declare(strict_types=1);

namespace Blockify\Theme;

/**
 * Returns responsive settings config.
 *
 * @since 0.9.10
 *
 * @return array
 */
function get_block_extra_options(): array {
	return [
		'position'      => [
			'value'   => 'position',
			'label'   => __( 'Position', 'blockify' ),
			'options' => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => __( 'Relative', 'blockify' ),
					'value' => 'relative',
				],
				[
					'label' => __( 'Absolute', 'blockify' ),
					'value' => 'absolute',
				],
				[
					'label' => __( 'Sticky', 'blockify' ),
					'value' => 'sticky',
				],
				[
					'label' => __( 'Fixed', 'blockify' ),
					'value' => 'fixed',
				],
				[
					'label' => __( 'Static', 'blockify' ),
					'value' => 'static',
				],
			],
		],
		'top'           => [
			'value' => 'top',
			'label' => __( 'Top', 'blockify' ),
		],
		'right'         => [
			'value' => 'right',
			'label' => __( 'Right', 'blockify' ),
		],
		'bottom'        => [
			'value' => 'bottom',
			'label' => __( 'Bottom', 'blockify' ),
		],
		'left'          => [
			'value' => 'left',
			'label' => __( 'Left', 'blockify' ),
		],
		'zIndex'        => [
			'value' => 'z-index',
			'label' => __( 'Z-Index', 'blockify' ),
		],
		'display'       => [
			'value'   => 'display',
			'label'   => __( 'Display', 'blockify' ),
			'options' => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => __( 'None', 'blockify' ),
					'value' => 'none',
				],
				[
					'label' => __( 'Flex', 'blockify' ),
					'value' => 'flex',
				],
				[
					'label' => __( 'Inline Flex', 'blockify' ),
					'value' => 'inline-flex',
				],
				[
					'label' => __( 'Block', 'blockify' ),
					'value' => 'block',
				],
				[
					'label' => __( 'Inline Block', 'blockify' ),
					'value' => 'inline-block',
				],
				[
					'label' => __( 'Inline', 'blockify' ),
					'value' => 'inline',
				],
				[
					'label' => __( 'Grid', 'blockify' ),
					'value' => 'grid',
				],
				[
					'label' => __( 'Inline Grid', 'blockify' ),
					'value' => 'inline-grid',
				],
				[
					'label' => __( 'Contents', 'blockify' ),
					'value' => 'contents',
				],
			],
		],
		'order'         => [
			'value' => 'order',
			'label' => __( 'Order', 'blockify' ),
		],
		'overflow'      => [
			'value'   => 'overflow',
			'label'   => __( 'Overflow', 'blockify' ),
			'options' => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => __( 'Hidden', 'blockify' ),
					'value' => 'hidden',
				],
				[
					'label' => __( 'Visible', 'blockify' ),
					'value' => 'visible',
				],
			],
		],
		'pointerEvents' => [
			'value'   => 'pointer-events',
			'label'   => __( 'Pointer Events', 'blockify' ),
			'options' => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => __( 'None', 'blockify' ),
					'value' => 'none',
				],
				[
					'label' => __( 'All', 'blockify' ),
					'value' => 'all',
				],
			],
		],
		'width'         => [
			'value' => 'width',
			'label' => __( 'Width', 'blockify' ),
		],
		'maxWidth'      => [
			'value' => 'max-width',
			'label' => __( 'Max Width', 'blockify' ),
		],
	];
}
