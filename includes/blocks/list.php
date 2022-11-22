<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function explode;
use function in_array;
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
	$block_gap       = $block['attrs']['style']['spacing']['blockGap'] ?? null;
	$justify_content = $block['attrs']['layout']['justifyContent'] ?? '';

	$dom = dom( $html );
	$ul  = get_dom_element( 'ul', $dom );

	if ( ! $ul ) {
		return $html;
	}

	$styles = css_string_to_array( $ul->getAttribute( 'style' ) );

	if ( $block_gap === '0' || $block_gap ) {
		$styles['--wp--style--block-gap'] = format_custom_property( $block_gap );
	}

	if ( $justify_content ) {
		$styles['display']         = 'flex';
		$styles['flex-wrap']       = 'wrap';
		$styles['justify-content'] = $justify_content;
	}

	$ul->setAttribute( 'style', css_array_to_string( $styles ) );

	$classes = explode( ' ', $ul->getAttribute( 'class' ) );
	$html    = $dom->saveHTML();

	if ( in_array( 'is-style-accordion', $classes, true ) ) {
		$html = render_list_block_accordion( $html, $block );
	}

	return $html;
}

/**
 * Renders list block as accordion.
 *
 * @since 0.9.19
 *
 * @param string $html  Block html.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_list_block_accordion( string $html, array $block ): string {
	$dom = dom( $html );
	$ul  = get_dom_element( 'ul', $dom );

	if ( ! $ul ) {
		return $html;
	}

	$classes = explode( ' ', $ul->getAttribute( 'class' ) );

	if ( ! in_array( 'wp-block-list', $classes, true ) ) {
		$classes = [
			'wp-block-list',
			...$classes,
		];
	}

	$ul->setAttribute( 'class', implode( ' ', $classes ) );

	$div = '<div>';

	foreach ( $ul->getElementsByTagName( 'li' ) as $li ) {
		$inner = $dom->saveHTML( $li );

		if ( ! $li instanceof DOMElement ) {
			continue;
		}

		if ( ! str_contains( $inner, '<br>' ) ) {
			continue;
		}

		$details = $dom->createElement( 'details' );

		foreach ( $li->attributes as $attribute ) {
			$details->setAttribute( $attribute->name, $attribute->value );
		}

		$summary = $dom->createElement( 'summary' );
		$section = $dom->createElement( 'section' );
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

		if ( str_contains( $li->getAttribute( 'style' ), 'border-' ) ) {
			$details->appendChild( $dom->createElement( 'hr' ) );
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

		$icon = $dom->createElement( 'span' );
		$icon->setAttribute( 'class', 'accordion-toggle' );
		$summary->appendChild( $icon );

		$div .= $dom->saveHTML( $details );
	}

	$div_dom  = dom( $div . '</div>' );
	$imported = $dom->importNode( $div_dom->documentElement, true );

	foreach ( $ul->attributes as $attribute ) {
		if ( ! method_exists( $imported, 'setAttribute' ) ) {
			continue;
		}

		$imported->setAttribute( $attribute->localName, $attribute->nodeValue );
	}

	$dom->removeChild( $ul );
	$dom->appendChild( $imported );

	return $dom->saveHTML();
}
