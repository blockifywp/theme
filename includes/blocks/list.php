<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function array_unshift;
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
	$ol  = get_dom_element( 'ol', $dom );

	if ( ! $ul && ! $ol ) {
		return $html;
	}

	$list   = $ul ?? $ol;
	$styles = css_string_to_array( $list->getAttribute( 'style' ) );

	if ( $block_gap === '0' || $block_gap ) {
		$styles['gap'] = format_custom_property( $block_gap );
	}

	if ( $justify_content ) {
		$styles['display']         = 'flex';
		$styles['flex-wrap']       = 'wrap';
		$styles['justify-content'] = $justify_content;
	}

	$list->setAttribute( 'style', css_array_to_string( $styles ) );

	$classes = explode( ' ', $list->getAttribute( 'class' ) );

	array_unshift( $classes, 'wp-block-list' );

	$list->setAttribute( 'class', implode( ' ', $classes ) );

	$html = $dom->saveHTML();

	if ( str_contains( $html, 'is-style-accordion' ) ) {
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
	$ol  = get_dom_element( 'ol', $dom );

	if ( ! $ul && ! $ol ) {
		return $html;
	}

	$list = $ul ?? $ol;

	$classes = explode( ' ', $list->getAttribute( 'class' ) );

	if ( ! in_array( 'wp-block-list', $classes, true ) ) {
		$classes = [
			'wp-block-list',
			...$classes,
		];
	}

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

		$li_style = $li->getAttribute( 'style' );

		$has_border = str_contains_any( $li_style, 'border-width', 'border-style', 'border-color' ) && ! str_contains( $li_style, 'border-width:0' );

		if ( $has_border ) {
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

		$icon = $dom->createElement( 'span' );
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

add_filter( 'blockify_inline_js', NS . 'add_accordion_js', 10, 2 );
/**
 * Adds accordion js.
 *
 * @since 0.9.19
 *
 * @param string $js      Inline JS.
 * @param string $content Page HTML content.
 *
 * @return string
 */
function add_accordion_js( string $js, string $content ): string {
	if ( str_contains( $content, 'is-style-accordion' ) ) {
		$js .= file_get_contents( get_dir() . 'assets/js/accordion.js' );
	}

	return $js;
}
