<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
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

	if ( ! $figure ) {
		return $html;
	}

	$figure_classes = explode( ' ', $figure->getAttribute( 'class' ) );

	$attrs         = $block['attrs'] ?? [];
	$shadow_preset = $attrs['shadowPreset'] ?? null;
	$hover_preset  = $attrs['shadowPresetHover'] ?? null;
	$use_custom    = $attrs['useCustomBoxShadow'] ?? null;
	$shadow_custom = $attrs['style']['boxShadow'] ?? null;
	$hover_custom  = $attrs['style']['boxShadow']['hover'] ?? null;

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

	if ( $figure_styles ) {
		$figure->setAttribute( 'style', css_array_to_string( $figure_styles ) );
	}

	$html = $dom->saveHTML();

	return $html;
}
