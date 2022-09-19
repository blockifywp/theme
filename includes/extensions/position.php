<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;

add_filter( 'render_block', NS . 'render_block_position', 10, 2 );
/**
 * Renders image icon styles on front end.
 *
 * @since 0.2.0
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_block_position( string $content, array $block ): string {
	$position = $block['attrs']['position'] ?? null;
	$inset    = $block['attrs']['inset'] ?? null;
	$zIndex   = $block['attrs']['zIndex'] ?? null;

	if ( $position ) {
		$dom = dom( $content );

		/* @var \DOMElement $first First element. */
		$first = $dom->getElementsByTagName( 'div' )->item( 0 );

		if ( ! $first ) {
			return $content;
		}

		$styles = css_string_to_array( $first->getAttribute( 'style' ) );

		if ( ! ( $styles['position'] ?? null ) ) {
			$styles['position'] = $position;
		}

		$first->setAttribute( 'style', css_array_to_string( $styles ) );

		$content = $dom->saveHTML();
	}

	if ( $zIndex ) {
		$dom = dom( $content );

		if ( ! $dom->firstChild ) {
			return $content;
		}

		/* @var DOMElement $first First element. */
		$first = $dom->getElementsByTagName( 'div' )->item( 0 );

		if ( ! $first ) {
			return $content;
		}

		$styles            = css_string_to_array( $first->getAttribute( 'style' ) );
		$styles['z-index'] = $zIndex;

		$first->setAttribute( 'style', css_array_to_string( $styles ) );

		$content = $dom->saveHTML();
	}

	if ( $inset ) {
		$dom = dom( $content );

		/* @var DOMElement $first First element. */
		$first = $dom->getElementsByTagName( 'div' )->item( 0 );

		if ( ! $first ) {
			return $content;
		}

		$styles = css_string_to_array( $first->getAttribute( 'style' ) );

		$styles['top']    = $inset['top'] ?? null;
		$styles['right']  = $inset['right'] ?? null;
		$styles['bottom'] = $inset['bottom'] ?? null;
		$styles['left']   = $inset['left'] ?? null;

		$first->setAttribute( 'style', css_array_to_string( $styles ) );

		$content = $dom->saveHTML();
	}

	return $content;
}
