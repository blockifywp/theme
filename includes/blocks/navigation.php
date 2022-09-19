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
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_navigation_block( string $content, array $block ): string {

	// Fix relative URLs.
	$content = str_replace( 'http://./', './', $content );
	$dom     = dom( $content );

	/**
	 * @var \DOMElement|null $nav
	 */
	$nav = $dom->getElementsByTagName( 'nav' )->item( 0 );

	if ( ! $nav ) {
		return $content;
	}

	/* @var \DOMElement|null $button */
	$button = $nav->getElementsByTagName( 'button' )->item( 0 );

	if ( ! $button ) {
		return $content;
	}

	$fragment = $button->ownerDocument->createDocumentFragment();

	$fragment->appendXML( '<span>Menu</span>' );
	$button->appendChild( $fragment );

	/* @var \DOMElement $label */
	$label = $button->getElementsByTagName( 'span' )->item( 0 );

	$label->setAttribute( 'class', 'screen-reader-text' );

	/* @var \DOMElement $svg */
	$svg = $button->getElementsByTagName( 'svg' )->item( 0 );

	/* @var \DOMElement $first_child */
	$first_child = $svg->getElementsByTagName( 'rect' )->item( 0 );

	/* @var \DOMElement $last_child */
	$last_child = $svg->getElementsByTagName( 'rect' )->item( 1 );

	$first_child->setAttribute( 'y', '6' );

	// Workaround to avoid setting attribute on DOMNode.
	// Set last child value to 18 for clone.
	$last_child->setAttribute( 'y', '18' );

	/* @var \DOMElement $clone */
	$clone = $last_child->cloneNode( true );

	// Set last child value back to 12.
	$last_child->setAttribute( 'y', '12' );

	$svg->appendChild( $clone );

	return $dom->saveHTML();
}
