<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_keys;
use function array_map;
use function explode;
use function implode;
use function str_replace;

add_filter( 'render_block', NS . 'render_responsive_block_css', 10, 2 );
/**
 * Renders image icon styles on front end.
 *
 * @since 0.2.0
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_responsive_block_css( string $content, array $block ): string {
	$properties  = [];
	$camel_cases = array_keys( get_responsive_settings() );

	foreach ( $camel_cases as $camel_case ) {
		$split        = preg_split( '/(?=[A-Z])/', $camel_case );
		$properties[] = implode( '-', array_map( 'strtolower', $split ) );
	}

	$style             = $block['attrs']['style'] ?? [];
	$custom_properties = [];
	$classes           = [];

	foreach ( $properties as $property ) {
		$value = $style[ "--$property" ] ?? null;

		if ( $value ?? '' ) {
			$custom_properties[ "--$property" ] = $value;

			if ( ! in_array( "has-$property", $classes, true ) ) {
				$classes[] = "has-$property";
			}
		}

		if ( $style[ "--$property-desktop" ] ?? '' ) {
			$custom_properties[ "--$property-desktop" ] = $style[ "--$property-desktop" ];

			if ( ! in_array( "has-$property", $classes, true ) ) {
				$classes[] = "has-$property";
			}
		}
	}

	if ( ! empty( $classes ) ) {
		$dom   = dom( $content );
		$first = get_dom_element( '*', $dom );

		if ( ! $first ) {
			return $content;
		}

		$class = implode(
			' ',
			[
				...explode(
					' ',
					$first->getAttribute( 'class' )
				),
				...$classes,
			]
		);

		$class = str_replace(
			[ 'undefined', '  ' ],
			[ '', ' ' ],
			$class
		);

		$first->setAttribute( 'class', $class );

		$first->setAttribute(
			'style',
			css_array_to_string(
				array_merge(
					css_string_to_array(
						$first->getAttribute( 'style' )
					),
					$custom_properties
				)
			)
		);

		$content = $dom->saveHTML();

	}

	return $content;
}


add_filter( SLUG . '_editor_script', NS . 'register_responsive_settings', 11 );
/**
 * Add default block supports.
 *
 * @since 0.9.10
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
 * @since 0.9.10
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
