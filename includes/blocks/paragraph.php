<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function get_bloginfo;
use function gmdate;
use function implode;
use function str_replace;

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
	$tags = [
		'{year}'       => gmdate( 'Y' ),
		'{site_title}' => get_bloginfo( 'name' ),
	];

	foreach ( $tags as $tag => $value ) {
		$html = str_replace( $tag, $value, $html );
	}

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

	$html       = $dom->saveHTML();
	$svg_string = $block['attrs']['curvedText']['svgString'] ?? '';

	if ( ! $svg_string ) {
		return $html;
	}

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
