<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function str_replace;

add_filter( 'render_block_core/navigation', NS . 'render_navigation_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_navigation_block( string $content, array $block ): string {

	// Fix relative URLs.
	$content = str_replace( 'http://./', './', $content );
	$dom     = dom( $content );

	/* @var \DOMElement $nav Navigation menu. */
	$nav = $dom->getElementsByTagName( 'nav' )->item( 0 );

	if ( ! $nav ) {
		return $content;
	}

	/* @var \DOMElement $button Button element. */
	$button = $nav->getElementsByTagName( 'button' )->item( 0 );

	if ( ! $button ) {
		return $content;
	}

	$fragment = $button->ownerDocument->createDocumentFragment();

	$fragment->appendXML( '<span>Menu</span>' );
	$button->appendChild( $fragment );

	/* @var \DOMElement $label Label element. */
	$label = $button->getElementsByTagName( 'span' )->item( 0 );

	$label->setAttribute( 'class', 'screen-reader-text' );

	/* @var \DOMElement $svg SVG element. */
	$svg = $button->getElementsByTagName( 'svg' )->item( 0 );

	/* @var \DOMElement $first_child First element. */
	$first_child = $svg->getElementsByTagName( 'rect' )->item( 0 );

	/* @var \DOMElement $last_child Last element. */
	$last_child = $svg->getElementsByTagName( 'rect' )->item( 1 );

	$first_child->setAttribute( 'y', '6' );

	// Workaround to avoid setting attribute on DOMNode.
	// Set last child value to 18 for clone.
	$last_child->setAttribute( 'y', '18' );

	$clone = $last_child->cloneNode( true );

	// Set last child value back to 12.
	$last_child->setAttribute( 'y', '12' );

	$svg->appendChild( $clone );

	return $dom->saveHTML();
}
