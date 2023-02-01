<?php

declare( strict_types=1 );

namespace Blockify\Theme;

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
 * @return string
 */
function render_image_placeholder( string $html, array $block ): string {
	$dom    = dom( $html );
	$figure = get_dom_element( 'figure', $dom );
	$img    = get_dom_element( 'img', $figure );

	if ( $img && $img->getAttribute( 'src' ) ) {
		return $html;
	}

	$attributes  = $block['attrs'] ?? [];
	$html        = ! $html ? '<figure class="wp-block-image"><img src="" alt=""/></figure>' : $html;
	$dom         = dom( $html );
	$styles      = css_array_to_string( add_block_support_color( [], $attributes ) );
	$svg         = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" class="wp-block-image__placeholder" style="' . $styles . '"><path vector-effect="non-scaling-stroke" d="M60 60 0 0"></path></svg>';
	$svg_dom     = dom( $svg );
	$svg_element = get_dom_element( 'svg', $svg_dom );

	if ( ! $svg_element ) {
		return $html;
	}

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
		'margin-bottom'              => $block['style']['spacing']['margin']['bottom'] ?? 'var(--wp--preset--spacing--sm)',
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
