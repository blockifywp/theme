<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;
use function method_exists;

add_filter( 'render_block_core/group', NS . 'render_block_layout', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.20
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_block_layout( string $content, array $block ): string {
	$dom = dom( $content );
	$div = get_dom_element( 'div', $dom );

	if ( $div && $div->tagName === 'main' ) {
		$div->setAttribute(
			'class',
			'wp-site-main ' . $div->getAttribute( 'class' )
		);
	}

	if ( $block['attrs']['minHeight'] ?? null ) {
		$div->setAttribute(
			'style',
			$div->getAttribute( 'style' ) . ';min-height:' . $block['attrs']['minHeight']
		);
	}

	$content = $dom->saveHTML();

	if ( ( $block['attrs']['layout']['orientation'] ?? null ) === 'marquee' ) {
		$content = render_marquee_block_variation( $content, $block );
	}

	return $content;
}

/**
 * Render marquee block variation.
 *
 * @since 1.0.0
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_marquee_block_variation( string $content, array $block ): string {
	$dom = dom( $content );
	$div = get_dom_element( 'div', $dom );

	if ( ! $div ) {
		return $content;
	}

	$repeat  = $block['attrs']['repeatItems'] ?? 2;
	$wrap    = $dom->createElement( 'div' );
	$styles  = css_string_to_array( $div->getAttribute( 'style' ) );
	$classes = explode( ' ', $div->getAttribute( 'class' ) );

	unset( $classes[ array_search( 'is-marquee', $classes ) ] );

	$gap = $block['attrs']['style']['spacing']['blockGap'] ?? null;

	if ( $gap || $gap === '0' ) {
		$styles['--marquee-gap'] = $gap;
	}

	$div->setAttribute( 'class', implode( ' ', $classes ) );
	$div->setAttribute( 'style', css_array_to_string( $styles ) );

	$wrap->setAttribute( 'class', 'is-marquee' );

	$count = $div->childNodes->count();

	for ( $i = 0; $i < $count; $i++ ) {
		$item = $div->childNodes->item( $i );

		if ( ! $item || ! method_exists( $item, 'setAttribute' ) ) {
			continue;
		}

		$wrap->appendChild( $item );

		for ( $j = 0; $j < $repeat; $j++ ) {
			$clone = dom_element( $item->cloneNode( true ) );

			if ( ! $clone ) {
				continue;
			}

			$clone->setAttribute( 'aria-hidden', 'true' );
			$classes   = explode( ' ', $clone->getAttribute( 'class' ) );
			$classes[] = 'is-cloned';
			$clone->setAttribute( 'class', implode( ' ', $classes ) );
			$wrap->appendChild( $clone );
		}
	}

	$div->insertBefore( $wrap, $div->firstChild );

	return $dom->saveHTML();
}
