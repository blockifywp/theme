<?php

declare( strict_types=1 );

namespace Blockify\Theme;


use DOMElement;
use function implode;
use function wp_parse_args;

add_filter( 'render_block', NS . 'render_block_position', 10, 2 );
/**
 * Renders image icon styles on front end.
 *
 * @since 0.2.0
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_block_position( string $content, array $block ): string {
	$position = $block['attrs']['position'] ?? null;
	$inset    = $block['attrs']['inset'] ?? null;
	$zIndex   = $block['attrs']['zIndex'] ?? null;

	if ( $position ) {
		$dom = dom( $content );

		if ( ! $dom->firstChild ) {
			return $content;
		}

		/** @var DOMElement $first_child */
		$first_child = $dom->firstChild;

		$first_child->setAttribute( 'style', 'position:' . $position . ';' . $first_child->getAttribute( 'style' ) );

		$content = $dom->saveHTML();
	}

	if ( $inset ) {
		$inset = wp_parse_args( $inset, [
			'top'    => 'auto',
			'right'  => 'auto',
			'bottom' => 'auto',
			'left'   => 'auto',
		] );

		$dom = dom( $content );

		if ( ! $dom->firstChild ) {
			return $content;
		}

		/** @var DOMElement $first_child */
		$first_child = $dom->firstChild;

		$first_child->setAttribute( 'style', 'inset:' . implode( ' ', $inset ) . ';' . $first_child->getAttribute( 'style' ) );

		$content = $dom->saveHTML();
	}

	return $content;
}
