<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function array_unshift;
use function esc_attr;
use function explode;
use function method_exists;
use function str_contains;

add_filter( 'render_block_core/list', NS . 'render_list_block', 11, 2 );
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
function render_list_block( string $html, array $block ): string {
	$dom = dom( $html );
	$ul  = get_dom_element( 'ul', $dom );
	$ol  = get_dom_element( 'ol', $dom );

	if ( ! $ul && ! $ol ) {
		return $html;
	}

	$block_gap       = $block['attrs']['style']['spacing']['blockGap'] ?? null;
	$justify_content = $block['attrs']['layout']['justifyContent'] ?? '';
	$margin          = $block['attrs']['style']['spacing']['margin'] ?? [];
	$list            = $ul ?? $ol;
	$styles          = css_string_to_array( $list->getAttribute( 'style' ) );

	if ( $block_gap === '0' || $block_gap ) {
		$styles['gap'] = format_custom_property( $block_gap );
	}

	if ( $justify_content ) {
		$styles['display']         = 'flex';
		$styles['flex-wrap']       = 'wrap';
		$styles['justify-content'] = esc_attr( $justify_content );
	}

	$styles = add_shorthand_property( $styles, 'margin', $margin );

	if ( $styles ) {
		$list->setAttribute( 'style', css_array_to_string( $styles ) );
	}

	$classes = explode( ' ', $list->getAttribute( 'class' ) );

	array_unshift( $classes, 'wp-block-list' );

	$list->setAttribute( 'class', implode( ' ', $classes ) );

	$html = $dom->saveHTML();

	if ( str_contains( $html, 'is-style-accordion' ) ) {
		$html = render_list_block_accordion( $html );
	}

	return $html;
}

/**
 * Renders list block as accordion.
 *
 * @since 0.9.19
 *
 * @param string $html Block html.
 *
 * @return string
 */
function render_list_block_accordion( string $html ): string {
	$dom = dom( $html );
	$ul  = get_dom_element( 'ul', $dom );
	$ol  = get_dom_element( 'ol', $dom );

	if ( ! $ul && ! $ol ) {
		return $html;
	}

	$list = $ul ?? $ol;

	$classes = explode( ' ', $list->getAttribute( 'class' ) );

	// Move `wp-block-list` class to the start of the array.
	$classes = [
		'wp-block-list',
		...( array_diff( $classes, [ 'wp-block-list' ] ) ),
	];

	$list->setAttribute( 'class', implode( ' ', $classes ) );

	$div = '<div>';

	foreach ( $list->getElementsByTagName( 'li' ) as $li ) {
		$inner = $dom->saveHTML( $li );

		if ( ! $li instanceof DOMElement ) {
			continue;
		}

		if ( ! str_contains( $inner, '<br>' ) ) {
			continue;
		}

		$details = create_element( 'details', $dom );

		foreach ( $li->attributes as $attribute ) {
			$details->setAttribute(
				esc_attr( $attribute->name ),
				esc_attr( $attribute->value )
			);
		}

		$summary = create_element( 'summary', $dom );
		$section = create_element( 'section', $dom );
		$explode = explode( '<br>', $inner );

		$details->textContent = '';

		$title_dom = dom( $explode[0] );
		$list_item = get_dom_element( 'li', $title_dom );

		foreach ( $list_item->childNodes as $child_node ) {
			$imported = $dom->importNode( $child_node, true );
			$summary->appendChild( $imported );
		}

		// If third arg present then second will be unused closing html tags.
		$section->textContent = strip_tags( $explode[2] ?? $explode[1], '' );
		$details->appendChild( $summary );

		$li_style = $li->getAttribute( 'style' );

		$has_border = str_contains_any( $li_style, 'border-width', 'border-style', 'border-color' ) && ! str_contains( $li_style, 'border-width:0' );

		if ( $has_border ) {
			$details->appendChild( create_element( 'hr', $dom ) );
		}

		$details->appendChild( $section );

		$styles  = css_string_to_array( $details->getAttribute( 'style' ) );
		$padding = [];

		foreach ( $styles as $key => $value ) {
			if ( str_contains( $key, 'padding' ) ) {
				unset( $styles[ $key ] );

				$padding[ $key ] = $value;
			}
		}

		if ( $padding ) {
			$summary->setAttribute(
				'style',
				css_array_to_string(
					$padding
				)
			);

			if ( ! $has_border ) {
				unset( $padding['padding-top'] );
			}

			$section->setAttribute(
				'style',
				css_array_to_string(
					$padding
				)
			);
		}

		$details->setAttribute(
			'style',
			css_array_to_string(
				$styles
			)
		);

		if ( ! $styles ) {
			$details->removeAttribute( 'style' );
		}

		$icon = create_element( 'span', $dom );
		$icon->setAttribute( 'class', 'accordion-toggle' );
		$summary->appendChild( $icon );

		$div .= $dom->saveHTML( $details );
	}

	$div_dom  = dom( $div . '</div>' );
	$imported = $dom->importNode( $div_dom->documentElement, true );

	foreach ( $list->attributes as $attribute ) {
		if ( ! method_exists( $imported, 'setAttribute' ) ) {
			continue;
		}

		$imported->setAttribute( $attribute->localName, $attribute->nodeValue );
	}

	$dom->removeChild( $list );
	$dom->appendChild( $imported );

	return $dom->saveHTML();
}
