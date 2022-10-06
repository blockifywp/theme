<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_contains;

add_filter( 'render_block_core/image', NS . 'render_image_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_image_block( string $content, array $block ): string {
	$id   = $block['attrs']['id'] ?? '';
	$icon = str_contains( $content, 'is-style-icon' ) || isset( $block['attrs']['className'] ) && str_contains( $block['attrs']['className'], 'is-style-icon' );

	// Placeholder.
	if ( ! $id && ! $icon ) {
		$content = get_image_placeholder( $content, $block );
	}

	// Icon.
	if ( $icon ) {
		$content = get_icon_html( $content, $block );
	}

	return $content;
}

/**
 * Returns placeholder HTML element string.
 *
 * @since 1.0.0
 *
 * @param string $html  Block content.
 * @param array  $block Block attributes.
 *
 * @return string
 */
function get_image_placeholder( string $html, array $block ): string {
	$attributes  = $block['attrs'] ?? [];
	$html        = ! $html ? '<figure class="wp-block-image"><img src="" alt=""/></figure>' : $html;
	$dom         = dom( $html );
	$figure      = get_dom_element( 'figure', $dom );
	$styles      = css_array_to_string( add_block_support_color( [], $attributes ) );
	$svg         = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" class="wp-block-image__placeholder" style="' . $styles . '"><path vector-effect="non-scaling-stroke" d="M60 60 0 0"></path></svg>';
	$svg_dom     = dom( $svg );
	$svg_element = get_dom_element( 'svg', $svg_dom );
	$result      = $dom->importNode( $svg_element, true );
	$img         = get_dom_element( 'img', $figure );

	if ( $img ) {
		$figure->removeChild( $img );
	}

	if ( $figure ) {
		$figure->appendChild( $result );
		$figure->setAttribute( 'class', $figure->getAttribute( 'class' ) . ' is-placeholder' );

		$css = [
			'width'         => $attributes['width'] ?? '',
			'height'        => $attributes['height'] ?? '',
			'margin-top'    => $attributes['style']['spacing']['margin']['top'] ?? '',
			'margin-right'  => $attributes['style']['spacing']['margin']['right'] ?? '',
			'margin-bottom' => $attributes['style']['spacing']['margin']['bottom'] ?? '',
			'margin-left'   => $attributes['style']['spacing']['margin']['left'] ?? '',
		];

		$figure->setAttribute( 'style', css_array_to_string( $css ) . ';' . $figure->getAttribute( 'style' ) );
	}

	return $dom->saveHTML();
}
