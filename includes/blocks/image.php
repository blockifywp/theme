<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block;
use function add_filter;
use function in_array;

add_filter( 'render_block', NS . 'render_image_block', 12, 3 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string   $html   Block HTML.
 * @param array    $block  Block data.
 * @param WP_Block $object Block object.
 *
 * @return string
 */
function render_image_block( string $html, array $block, WP_Block $object ): string {
	$name = $block['blockName'] ?? '';

	if ( ! in_array( $name, [ 'core/image', 'core/post-featured-image', 'blockify/image-compare' ], true ) ) {
		return $html;
	}

	$attrs           = $block['attrs'] ?? [];
	$id              = $attrs['id'] ?? '';
	$icon            = ( $attrs['iconSet'] ?? '' ) && ( $attrs['iconName'] ?? '' ) || ( $attrs['iconSvgString'] ?? '' );
	$style           = $attrs['style'] ?? [];
	$svg             = $style['svgString'] ?? '';
	$use_placeholder = $attrs['usePlaceholder'] ?? true;

	// Placeholder.
	if ( $use_placeholder && ! $id && ! $icon && ! $svg ) {
		$html = render_image_placeholder( $html, $block, $object );
	}

	// Icon.
	if ( $icon ) {
		$html = get_icon_html( $html, $block );
	}

	// Image options.
	if ( ! $icon && ! $svg ) {

		if ( $name === 'core/image' ) {
			$html = add_responsive_classes( $html, $block, get_image_options(), (bool) $id );
		}

		if ( $name === 'core/post-featured-image' && ! $html ) {
			$html = add_responsive_classes( $html, $block, get_image_options(), (bool) $id );
		}
	}

	$margin = $style['spacing']['margin'] ?? '';

	if ( $margin ) {
		$dom    = dom( $html );
		$figure = get_dom_element( 'figure', $dom );

		if ( $figure ) {
			$styles = css_string_to_array( $figure->getAttribute( 'style' ) );

			$styles = add_shorthand_property( $styles, 'margin', $style['spacing']['margin'] ?? [] );

			$figure->setAttribute(
				'style',
				css_array_to_string( $styles )
			);
		}

		$html = $dom->saveHTML();
	}

	return $html;
}
