<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;
use function is_array;

add_filter( 'render_block_core/paragraph', NS . 'render_paragraph_block', 11, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_paragraph_block( string $html, array $block ): string {
	$dom = dom( $html );
	$p   = get_dom_element( 'p', $dom );

	if ( ! $p ) {
		return $html;
	}

	$p->setAttribute(
		'class',
		implode(
			' ',
			[
				'wp-block-paragraph',
				...explode(
					' ',
					$p->getAttribute( 'class' )
				),
			]
		)
	);

	$p_styles = css_string_to_array( $p->getAttribute( 'style' ) );
	$padding  = $block['attrs']['style']['spacing']['padding'] ?? '';
	$margin   = $block['attrs']['style']['spacing']['margin'] ?? '';

	if ( is_array( $padding ) && ! empty( $padding ) ) {
		$p_styles = add_shorthand_property( $p_styles, 'padding', $padding );
	}

	if ( is_array( $margin ) && ! empty( $margin ) ) {
		$p_styles = add_shorthand_property( $p_styles, 'margin', $margin );
	}

	$p->setAttribute( 'style', css_array_to_string( $p_styles ) );

	$html       = $dom->saveHTML();
	$svg_string = $block['attrs']['curvedText']['svgString'] ?? '';

	if ( $svg_string ) {
		$html = render_curved_text( $html, $block, $svg_string );
	}

	if ( str_contains( $html, 'http://http' ) ) {
		$html = str_replace( 'http://http', 'http', $html );
	}

	return $html;
}

/**
 * Renders curved text.
 *
 * @since 1.3.2
 *
 * @param string $html       Block HTML.
 * @param array  $block      Block data.
 * @param string $svg_string SVG string.
 *
 * @return string
 */
function render_curved_text( string $html, array $block, string $svg_string ): string {
	$dom            = dom( $html );
	$p              = get_dom_element( 'p', $dom );
	$p->textContent = '';

	$svg_dom     = dom( $svg_string );
	$svg_element = get_dom_element( 'svg', $svg_dom );

	if ( ! $svg_element ) {
		return $html;
	}

	$svg_text_element = get_dom_element( 'text', $svg_element );

	if ( ! $svg_text_element ) {
		return $html;
	}

	$text_path_element = get_dom_element( '*', $svg_text_element );

	if ( $text_path_element ) {
		$text_path_element->textContent = $block['attrs']['curvedText']['content'] ?? '';
	}

	$svg_string      = $svg_dom->saveHTML( $svg_element );
	$new_svg_dom     = dom( $svg_string );
	$new_svg_element = get_dom_element( 'svg', $new_svg_dom );
	$imported        = $dom->importNode( $new_svg_element, true );

	$p->appendChild( $imported );

	return $dom->saveHTML( $p );
}
