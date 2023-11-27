<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_diff;
use function explode;
use function implode;
use function in_array;
use function method_exists;

add_filter( 'render_block_core/group', NS . 'render_group_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 1.3.0
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_group_block( string $html, array $block ): string {
	if ( ( $block['attrs']['layout']['orientation'] ?? null ) === 'marquee' ) {
		$html = render_marquee_block_variation( $html, $block );
	}

	$dom   = dom( $html );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $html;
	}

	if ( $block['attrs']['minHeight'] ?? null ) {
		$first->setAttribute(
			'style',
			$first->getAttribute( 'style' ) . ';min-height:' . $block['attrs']['minHeight']
		);
	}

	$margin  = $block['attrs']['style']['spacing']['margin'] ?? [];
	$padding = $block['attrs']['style']['spacing']['padding'] ?? [];

	$div_styles = css_string_to_array( $first->getAttribute( 'style' ) );

	foreach ( [ 'top', 'right', 'bottom', 'left' ] as $side ) {
		if ( ( $margin[ $side ] ?? null ) !== null ) {
			$div_styles["margin-$side"] = format_custom_property( $margin[ $side ] );
		}

		if ( ( $padding[ $side ] ?? null ) !== null ) {
			$div_styles["padding-$side"] = format_custom_property( $padding[ $side ] );
		}
	}

	if ( $div_styles ) {
		$first->setAttribute( 'style', css_array_to_string( $div_styles ) );
	}

	$tag = $block['attrs']['tagName'] ?? 'div';

	if ( $tag === 'main' ) {
		$first->setAttribute( 'role', 'main' );

		$classes = explode( ' ', $first->getAttribute( 'class' ) );

		if ( in_array( 'site-main', $classes, true ) ) {
			$classes = [
				'site-main',
				...( array_diff( $classes, [ 'site-main' ] ) ),
			];
		}

		$first->setAttribute( 'class', implode( ' ', $classes ) );
	}

	return $dom->saveHTML();
}

/**
 * Render marquee block variation.
 *
 * @since 1.0.0
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_marquee_block_variation( string $html, array $block ): string {
	$dom   = dom( $html );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $html;
	}

	$repeat  = $block['attrs']['repeatItems'] ?? 2;
	$wrap    = create_element( 'div', $dom );
	$styles  = css_string_to_array( $first->getAttribute( 'style' ) );
	$classes = explode( ' ', $first->getAttribute( 'class' ) );
	$classes = array_diff( $classes, [ 'is-marquee' ] );

	$gap = $block['attrs']['style']['spacing']['blockGap'] ?? null;

	if ( $gap || $gap === '0' ) {
		$styles['--marquee-gap'] = format_custom_property( $gap );
	}

	$first->setAttribute( 'class', implode( ' ', $classes ) );
	$first->setAttribute( 'style', css_array_to_string( $styles ) );

	$wrap->setAttribute( 'class', 'is-marquee' );

	$count = $first->childNodes->count();

	for ( $i = 0; $i < $count; $i++ ) {
		$item = $first->childNodes->item( $i );

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

	$first->insertBefore( $wrap, $first->firstChild );

	return $dom->saveHTML();
}
