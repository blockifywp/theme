<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function file_exists;
use function file_get_contents;
use function str_contains;

add_filter( 'render_block_core/details', NS . 'render_details_block', 10, 2 );
/**
 * Renders the details block.
 *
 * @since 0.0.1
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_details_block( string $html, array $block ): string {
	$dom     = dom( $html );
	$details = get_dom_element( 'details', $dom );

	if ( ! $details ) {
		return $html;
	}

	$summary = get_dom_element( 'summary', $details );
	$padding = $block['attrs']['style']['spacing']['padding'] ?? [];

	if ( $summary && $padding ) {
		$summary_styles = css_string_to_array( $summary->getAttribute( 'style' ) );

		$summary_styles['padding-top']    = $padding['top'] ?? '';
		$summary_styles['padding-bottom'] = $padding['bottom'] ?? '';
		$summary_styles['padding-left']   = $padding['left'] ?? '';
		$summary_styles['margin-top']     = 'calc(0px - ' . ( $padding['top'] ?? '' ) . ')';
		$summary_styles['margin-bottom']  = 'calc(0px - ' . ( $padding['bottom'] ?? '' ) . ')';
		$summary_styles['margin-left']    = 'calc(0px - ' . ( $padding['left'] ?? '' ) . ')';

		$summary->setAttribute( 'style', css_array_to_string( $summary_styles ) );
	}

	return $dom->saveHTML();
}

add_filter( 'blockify_inline_js', NS . 'add_details_js', 10, 3 );
/**
 * Adds JS for the details block.
 *
 * @since 0.0.1
 *
 * @param string $js      Inline JS.
 * @param string $content Template HTML.
 * @param bool   $all     Whether to include all JS.
 *
 * @return string
 */
function add_details_js( string $js, string $content, bool $all ): string {

	if ( $all || str_contains( $content, 'wp-block-details' ) ) {
		$file = get_dir() . 'assets/js/details.js';

		if ( file_exists( $file ) ) {
			$js .= file_get_contents( $file );
		}
	}

	return $js;
}
