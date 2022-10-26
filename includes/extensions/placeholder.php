<?php

declare( strict_types=1 );

namespace Blockify\Theme;

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
			'width'         => $attributes['width'] ?? null,
			'height'        => $attributes['height'] ?? null,
			'margin-top'    => $attributes['style']['spacing']['margin']['top'] ?? null,
			'margin-right'  => $attributes['style']['spacing']['margin']['right'] ?? null,
			'margin-bottom' => $attributes['style']['spacing']['margin']['bottom'] ?? 'var(--wp--preset--spacing--sm)',
			// TODO: Get from theme.json.
			'margin-left'   => $attributes['style']['spacing']['margin']['left'] ?? null,
		];

		$figure->setAttribute( 'style', css_array_to_string( $css ) . ';' . $figure->getAttribute( 'style' ) );
	}

	return $dom->saveHTML();
}
