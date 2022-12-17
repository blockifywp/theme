<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function _wp_to_kebab_case;
use function array_key_last;
use function explode;
use function file_exists;
use function file_get_contents;
use function is_null;
use function sprintf;
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
		$css       .= $property . ':' . $value . $semicolon;
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
 * Conditionally adds CSS for utility classes
 *
 * @since 0.9.19
 *
 * @param string $content   Page Content.
 * @param bool   $is_editor Is editor page.
 *
 * @return string
 */
function get_position_styles( string $content, bool $is_editor ): string {
	$options = get_block_extra_options();
	$all     = '';
	$mobile  = '';
	$desktop = '';

	foreach ( $options as $key => $args ) {
		$property       = _wp_to_kebab_case( $key );
		$select_options = $args['options'] ?? [];

		foreach ( $select_options as $option ) {
			if ( ! $option['value'] ) {
				continue;
			}

			if ( $is_editor || str_contains( $content, " has-{$property}-{$option['value']}" ) ) {
				$all .= sprintf(
					'.has-%1$s-%2$s{%1$s:%2$s !important}',
					$property,
					$option['value'] ?? '',
				);
			}

			if ( $is_editor || str_contains( $content, " has-{$property}-{$option['value']}-mobile" ) ) {
				$mobile .= sprintf(
					'.has-%1$s-%2$s-mobile{%1$s:%2$s !important}',
					$property,
					$option['value'] ?? '',
				);
			}

			if ( $is_editor || str_contains( $content, " has-{$property}-{$option['value']}-desktop" ) ) {
				$desktop .= sprintf(
					'.has-%1$s-%2$s-desktop{%1$s:%2$s !important}',
					$property,
					$option['value'] ?? '',
				);
			}
		}

		// Has custom value.
		if ( ! $select_options ) {

			if ( $is_editor || str_contains( $content, " has-$property" ) ) {
				$all .= sprintf(
					'.has-%1$s{%1$s:var(--%1$s)}',
					$property
				);
			}

			if ( $is_editor || \str_contains( $content, "--$property-mobile" ) ) {
				$mobile .= sprintf(
					'.has-%1$s{%1$s:var(--%1$s-mobile,var(--%1$s))}',
					$property
				);
			}

			if ( $is_editor || str_contains( $content, "--$property-desktop" ) ) {
				$desktop .= sprintf(
					'.has-%1$s{%1$s:var(--%1$s-desktop,var(--%1$s))}',
					$property
				);
			}
		}
	}

	$css = '';

	if ( $all ) {
		$css .= $all;
	}

	if ( $mobile ) {
		$css .= sprintf( '@media(max-width:781px){%s}', $mobile );
	}

	if ( $desktop ) {
		$css .= sprintf( '@media(min-width:782px){%s}', $desktop );
	}

	return $css;
}

/**
 * Gets animations from stylesheet.
 *
 * @since 0.9.18
 *
 * @return array
 */
function get_animations(): array {

	$file = DIR . 'assets/css/extensions/animation.css';

	if ( ! file_exists( $file ) ) {
		return [];
	}

	$parts      = explode( '@keyframes', file_get_contents( $file ) );
	$animations = [];

	unset( $parts[0] );

	foreach ( $parts as $animation ) {
		$name = trim( explode( '{', $animation )[0] ?? '' );

		$animations[ $name ] = str_replace( $name, '', $animation );
	}

	return $animations;
}

/**
 * Returns inline styles for animations.
 *
 * @since 0.9.19
 *
 * @param string $content   Page content.
 * @param bool   $is_editor Is admin.
 *
 * @return string
 */
function get_animation_styles( string $content, bool $is_editor ): string {
	$animations = get_animations();
	$css        = '';

	foreach ( $animations as $name => $animation ) {
		if ( $is_editor || str_contains( $content, "--wp--custom--animation--name:{$name}" ) ) {
			$css .= "@keyframes $name" . trim( $animation );
		}
	}

	return $css;
}
