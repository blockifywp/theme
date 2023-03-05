<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMException;
use function array_merge;
use function explode;
use function implode;
use function in_array;

/**
 * Returns placeholder HTML element string.
 *
 * @since 0.9.10
 *
 * @param string $html  Block content.
 * @param array  $block Block attributes.
 *
 * @throws DOMException If the HTML is invalid.
 *
 * @return string
 */
function render_image_placeholder( string $html, array $block ): string {
	$dom    = dom( $html );
	$figure = get_dom_element( 'figure', $dom );
	$img    = get_dom_element( 'img', $figure );

	if ( $img && $img->getAttribute( 'src' ) ) {
		return $html;
	}

	$html = ! $html ? '<figure class="wp-block-image"><img src="" alt=""/></figure>' : $html;
	$dom  = dom( $html );

	// @phpcs:disable WordPress.WP.CapitalPDangit.Misspelled
	$svg         = get_icon( 'wordpress', 'image', 30 );
	$svg_dom     = dom( $svg );
	$svg_element = get_dom_element( 'svg', $svg_dom );

	if ( ! $svg_element ) {
		return $html;
	}

	$svg_classes = explode( ' ', $svg_element->getAttribute( 'class' ) );

	$svg_classes[] = 'wp-block-image__placeholder-icon';

	$svg_element->setAttribute( 'class', implode( ' ', $svg_classes ) );

	$svg_element->setAttribute( 'fill', 'currentColor' );

	$result = $dom->importNode( $svg_element, true );
	$figure = get_dom_element( 'figure', $dom );

	if ( ! $figure ) {
		return $html;
	}

	$img = get_dom_element( 'img', $figure );

	if ( $img ) {
		$figure->removeChild( $img );
	}

	$figure->appendChild( $result );
	$classes = explode( ' ', $figure->getAttribute( 'class' ) );

	if ( ! in_array( 'is-placeholder', $classes, true ) ) {
		$classes[] = 'is-placeholder';
	}

	if ( $block['align'] ?? null ) {
		$classes[] = 'align' . $block['align'];
	}

	$figure->setAttribute( 'class', implode( ' ', $classes ) );

	$styles = [
		'width'                      => $block['width'] ?? null,
		'height'                     => $block['height'] ?? null,
		'margin-top'                 => $block['style']['spacing']['margin']['top'] ?? null,
		'margin-right'               => $block['style']['spacing']['margin']['right'] ?? null,
		'margin-bottom'              => $block['style']['spacing']['margin']['bottom'] ?? null,
		// TODO: Get from theme.json.
		'margin-left'                => $block['style']['spacing']['margin']['left'] ?? null,
		'border-top-left-radius'     => $block['style']['border']['radius']['topLeft'] ?? null,
		'border-top-right-radius'    => $block['style']['border']['radius']['topRight'] ?? null,
		'border-bottom-left-radius'  => $block['style']['border']['radius']['bottomLeft'] ?? null,
		'border-bottom-right-radius' => $block['style']['border']['radius']['bottomRight'] ?? null,
	];

	$figure->setAttribute(
		'style',
		css_array_to_string(
			array_merge(
				css_string_to_array( $figure->getAttribute( 'style' ) ),
				$styles,
			)
		)
	);

	return $dom->saveHTML();
}
