<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function array_key_last;
use function explode;
use function is_null;
use function str_contains;
use function str_replace;

/**
 * Converts array of CSS rules to string.
 *
 * @since 0.0.22
 *
 * @param array $styles [ 'color' => 'red', 'background' => 'blue' ].
 * @param bool  $trim   Whether to trim the trailing semicolon.
 *
 * @return string
 */
function css_array_to_string( array $styles, bool $trim = false ): string {
	$css = '';

	foreach ( $styles as $property => $value ) {
		if ( is_null( $value ) ) {
			continue;
		}

		$semicolon = $trim && $property === array_key_last( $styles ) ? '' : ';';
		$css      .= $property . ':' . $value . $semicolon;
	}

	return $css;
}

/**
 * Converts string of CSS rules to an array.
 *
 * @since 0.0.2
 *
 * @param string $css 'color:red;background:blue'.
 *
 * @return array
 */
function css_string_to_array( string $css ): array {
	$array = [];

	// Prevent svg url strings from being split.
	$css = str_replace( 'xml;', 'xml$', $css );

	$elements = explode( ';', $css );

	foreach ( $elements as $element ) {
		$parts = explode( ':', $element, 2 );

		if ( isset( $parts[1] ) ) {
			$property = $parts[0];
			$value    = $parts[1];

			if ( $value !== '' && $value !== 'null' ) {
				$array[ $property ] = str_replace( 'xml$', 'xml;', $value );
			}
		}
	}

	return $array;
}

/**
 * Formats custom properties for unsupported blocks.
 *
 * @since 0.9.10
 *
 * @param string $custom_property Custom property value to format.
 *
 * @return string
 */
function format_custom_property( string $custom_property ): string {
	if ( ! str_contains( $custom_property, 'var:' ) ) {
		return $custom_property;
	}

	return str_replace(
		[
			'var:',
			'|',
		],
		[
			'var(--wp--',
			'--',
		],
		$custom_property . ')'
	);
}

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
