<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function gmdate;
use function implode;
use function str_replace;

add_filter( 'render_block_core/paragraph', NS . 'render_paragraph_block', 10, 2 );
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
function render_paragraph_block( string $content, array $block ): string {
	$tags = [
		'[year]' => gmdate( 'Y' ),
	];

	foreach ( $tags as $tag => $value ) {
		$content = str_replace( $tag, $value, $content );
	}

	$dom = dom( $content );
	$p   = get_dom_element( 'p', $dom );

	if ( $p ) {
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

		if ( $block['attrs']['curvedText'] ?? '' ) {
			$p->textContent = '';

			$svg_dom     = dom( $block['attrs']['curvedText']['svgString'] ?? $p->textContent );
			$svg_element = get_dom_element( 'svg', $svg_dom );

			if ( $svg_element ) {
				$imported = $dom->importNode( $svg_element, true );

				$p->appendChild( $imported );
			}
		}

		$content = $dom->saveHTML();
	}


	return $content;
}
