<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function esc_attr;
use function explode;
use function implode;

add_filter( 'render_block_core/post-featured-image', NS . 'render_post_featured_image_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 1.3.0
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_post_featured_image_block( string $html, array $block ): string {
	$dom    = dom( $html );
	$figure = get_dom_element( 'figure', $dom );
	$img    = get_dom_element( 'img', $figure );

	if ( ! $figure ) {
		return $html;
	}

	$figure_classes = explode( ' ', $figure->getAttribute( 'class' ) );
	$attrs          = $block['attrs'] ?? [];
	$shadow_preset  = esc_attr( $attrs['shadowPreset'] ?? '' );
	$hover_preset   = esc_attr( $attrs['shadowPresetHover'] ?? '' );
	$use_custom     = $attrs['useCustomBoxShadow'] ?? null;
	$shadow_custom  = $attrs['style']['boxShadow'] ?? null;
	$hover_custom   = $attrs['style']['boxShadow']['hover'] ?? null;
	$border_radius  = $attrs['style']['border']['radius'] ?? null;

	if ( $shadow_preset ) {
		$figure_classes[] = 'has-shadow';
		$figure_classes[] = "has-{$shadow_preset}-shadow";
	}

	if ( $hover_preset ) {
		$figure_classes[] = "has-{$hover_preset}-shadow-hover";
	}

	$figure->setAttribute( 'class', implode( ' ', $figure_classes ) );

	$figure_styles = css_string_to_array( $figure->getAttribute( 'style' ) );

	if ( $use_custom && $shadow_custom ) {
		$color = $shadow_custom['color'] ?? null;

		if ( $color ) {
			$figure_styles['--wp--custom--box-shadow--color'] = format_custom_property( $color );
		}
	}

	if ( $use_custom && $hover_custom ) {
		$color = $hover_custom['color'] ?? null;

		if ( $color ) {
			$figure_styles['--wp--custom--box-shadow--hover--color'] = format_custom_property( $color );
		}
	}

	if ( $border_radius ) {
		$figure_styles['border-radius'] = $border_radius;
	}

	$img_classes = $img ? explode( ' ', $img->getAttribute( 'class' ) ) : [];

	$transform       = $attrs['style']['transform'] ?? [];
	$transform_units = [
		'rotate'    => 'deg',
		'skew'      => 'deg',
		'scale'     => '',
		'translate' => '',
	];

	if ( ! empty( $transform ) && is_array( $transform ) ) {
		$transform_value = '';

		foreach ( $transform as $key => $value ) {
			$unit            = $transform_units[ $key ] ?? '';
			$transform_value .= "{$key}({$value}{$unit}) ";
		}

		if ( ! in_array( 'has-transform', $img_classes, true ) ) {
			$figure_styles['transform'] = $transform_value;
		}
	}

	$filter = $attrs['style']['filter'] ?? [];

	if ( ! empty( $filter ) && is_array( $filter ) ) {
		$filter_options = get_filter_options();
		$filter_value   = '';

		foreach ( $filter as $key => $value ) {
			$unit         = $filter_options[ $key ]['unit'] ?? '';
			$filter_value .= "{$key}({$value}{$unit}) ";
		}

		if ( ! in_array( 'has-filter', $img_classes, true ) ) {
			$figure_styles['filter'] = $filter_value;
		}
	}

	if ( $figure_styles ) {
		$figure->setAttribute( 'style', css_array_to_string( $figure_styles ) );
	}

	return $dom->saveHTML();
}
