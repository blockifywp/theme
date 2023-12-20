<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block;
use function add_filter;
use function apply_filters;
use function rtrim;
use function str_contains;
use function strval;
use function trim;

add_filter( 'render_block', NS . 'render_block_onclick_attribute', 11, 3 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string   $html   Block HTML.
 * @param array    $block  Block data.
 * @param WP_Block $object Block args.
 *
 * @return string
 */
function render_block_onclick_attribute( string $html, array $block, WP_Block $object ): string {
	$js = strval( $block['attrs']['onclick'] ?? '' );

	if ( ! $js ) {
		return $html;
	}

	$js       = render_template_tags( $js, $block, $object );
	$on_click = format_inline_js( $js );
	$link     = null;
	$name     = $block['blockName'] ?? '';

	// Groups and buttons.
	if ( $on_click && $html ) {
		$dom  = dom( $html );
		$div  = get_dom_element( 'div', $dom );
		$link = get_dom_element( 'a', $div );

		if ( $link && $name === 'core/button' ) {
			$link->setAttribute( 'onclick', $on_click );
		} else if ( $div ) {
			$div->setAttribute( 'onclick', $on_click );
		}

		$html = $dom->saveHTML();
	}

	// Icon.
	if ( $on_click && $html && $link === null ) {
		$dom    = dom( $html );
		$figure = get_dom_element( 'figure', $dom );
		$img    = get_dom_element( 'img', $figure );

		if ( $img && ! str_contains( $figure->getAttribute( 'class' ), 'wp-block-post-featured-image' ) ) {
			$img->setAttribute( 'onclick', $on_click );
		}

		$html = $dom->saveHTML();
	}

	return $html;
}

/**
 * Formats inline JS.
 *
 * @since 1.2.9
 *
 * @param string $js JS.
 *
 * @return string
 */
function format_inline_js( string $js ): string {
	$js = str_replace( '"', "'", $js );
	$js = trim( rtrim( $js, ';' ) );
	$js = reduce_whitespace( $js );
	$js = remove_line_breaks( $js );

	return apply_filters( 'blockify_format_inline_js', $js );
}
