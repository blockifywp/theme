<?php

declare( strict_types=1 );

namespace Blockify\Theme;

add_filter( SLUG . '_editor_script', NS . 'register_responsive_settings', 11 );
/**
 * Add default block supports.
 *
 * @since 1.0.0
 *
 * @param array $config Blockify editor config.
 *
 * @return array
 */
function register_responsive_settings( array $config = [] ): array {
	$config['responsiveSettings'] = get_responsive_settings();

	return $config;
}

/**
 * Returns responsive settings config.
 *
 * @since 1.0.0
 *
 * @return array
 */
function get_responsive_settings(): array {
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
		'inset'         => [
			'value' => 'inset',
			'label' => __( 'Inset', 'blockify' ),
		],
		'zIndex'        => [
			'value' => 'z-index',
			'label' => __( 'Z-Index', 'blockify' ),
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
					'label' => __( 'Auto', 'blockify' ),
					'value' => 'auto',
				],
				[
					'label' => __( 'Visible', 'blockify' ),
					'value' => 'visible',
				],
				[
					'label' => __( 'Hidden', 'blockify' ),
					'value' => 'hidden',
				],
				[
					'label' => __( 'Clip', 'blockify' ),
					'value' => 'clip',
				],
				[
					'label' => __( 'Initial', 'blockify' ),
					'value' => 'initial',
				],
				[
					'label' => __( 'Inherit', 'blockify' ),
					'value' => 'inherit',
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
					'label' => __( 'Auto', 'blockify' ),
					'value' => 'auto',
				],
				[
					'label' => __( 'None', 'blockify' ),
					'value' => 'none',
				],
				[
					'label' => __( 'Visible Painted', 'blockify' ),
					'value' => 'visiblePainted',
				],
				[
					'label' => __( 'Visible Fill', 'blockify' ),
					'value' => 'visibleFill',
				],
				[
					'label' => __( 'Visible Stroke', 'blockify' ),
					'value' => 'visibleStroke',
				],
				[
					'label' => __( 'Visible', 'blockify' ),
					'value' => 'visible',
				],
				[
					'label' => __( 'Painted', 'blockify' ),
					'value' => 'painted',
				],
				[
					'label' => __( 'Fill', 'blockify' ),
					'value' => 'fill',
				],
				[
					'label' => __( 'Stroke', 'blockify' ),
					'value' => 'stroke',
				],
				[
					'label' => __( 'All', 'blockify' ),
					'value' => 'all',
				],
				[
					'label' => __( 'Inherit', 'blockify' ),
					'value' => 'inherit',
				],
				[
					'label' => __( 'Initial', 'blockify' ),
					'value' => 'initial',
				],
				[
					'label' => __( 'Revert', 'blockify' ),
					'value' => 'revert',
				],
				[
					'label' => __( 'Revert Layer', 'blockify' ),
					'value' => 'revert-layer',
				],
				[
					'label' => __( 'Unset', 'blockify' ),
					'value' => 'unset',
				],
			],
		],
		'order'         => [
			'value' => 'order',
			'label' => __( 'Order', 'blockify' ),
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
					'label' => __( 'Table', 'blockify' ),
					'value' => 'table',
				],
			],
		],
		'flexDirection' => [
			'value'   => 'flex-direction',
			'label'   => __( 'Flex Direction', 'blockify' ),
			'options' => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => __( 'Row', 'blockify' ),
					'value' => 'row',
				],
				[
					'label' => __( 'Row Reverse', 'blockify' ),
					'value' => 'row-reverse',
				],
				[
					'label' => __( 'Column', 'blockify' ),
					'value' => 'column',
				],
				[
					'label' => __( 'Column Reverse', 'blockify' ),
					'value' => 'column-reverse',
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

