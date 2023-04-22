<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use Exception;
use function add_filter;
use function do_blocks;
use function get_the_content;
use function get_the_title;
use function in_array;
use function is_admin;
use function sanitize_title;

if ( ! is_admin() ) {
	add_filter( 'render_block_core/table-of-contents', NS . 'render_table_of_contents', 10, 2 );
}

/**
 * Render Table of Contents block.
 *
 * @param string $html  The block content.
 * @param array  $block The block.
 *
 * @throws Exception If the DOMDocument fails to load the HTML.
 *
 * @return string
 */
function render_table_of_contents( string $html, array $block ): string {
	$headings = $block['attrs']['headings'] ?? [];
	$sidebar  = false;

	foreach ( $headings as $heading ) {
		$content = $heading['content'] ?? '';

		if ( in_array(
			$content,
			[
				__( 'Table of Contents', 'blockify' ),
				__( 'Contents', 'blockify' ),
				__( 'Table of contents', 'blockify' ),
			],
			true
		) ) {
			$sidebar = true;
		}
	}

	if ( $sidebar ) {
		$content_headings = [
			get_the_title(),
		];
		$content_dom      = dom( do_blocks( get_the_content() ) );

		foreach ( $content_dom->getElementsByTagName( '*' ) as $element ) {
			if ( in_array(
				$element->tagName,
				[ 'h2', 'h3', 'h4', 'h5', 'h6' ],
				true
			) ) {
				$content_headings[] = $element->textContent;
			}
		}

		$dom = dom( $html );
		$nav = get_dom_element( 'nav', $dom );

		if ( ! $nav ) {
			return $html;
		}

		$nav->removeChild( $nav->firstChild );

		$ol = $dom->createElement( 'ol' );

		$nav->appendChild( $ol );

		foreach ( $content_headings as $content_heading ) {
			$link = $dom->createElement( 'a' );

			$link->setAttribute( 'href', '#' . sanitize_title( $content_heading ) );

			$link->textContent = $content_heading;

			$li = $dom->createElement( 'li' );

			$li->appendChild( $link );
			$ol->appendChild( $li );
		}

		$nav_styles = css_string_to_array( $nav->getAttribute( 'style' ) );

		$gap = $block['attrs']['style']['spacing']['blockGap'] ?? null;

		if ( $gap ) {
			$nav_styles['gap'] = format_custom_property( $gap );
		}

		$ol->setAttribute( 'style', css_array_to_string( $nav_styles ) );

		$nav->removeAttribute( 'style' );

		$html = $dom->saveHTML();
	}

	return $html;
}

