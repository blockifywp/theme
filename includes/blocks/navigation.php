<?php

declare( strict_types=1 );

namespace Blockify;

use DOMElement;
use function str_replace;

add_filter( 'render_block', NS . 'render_navigation_block', 10, 2 );
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
	if ( 'core/navigation' !== $block['blockName'] ) {
		return $content;
	}

	// Fix relative URLs.
	$content = str_replace( 'http://./', './', $content );
	$dom     = dom( $content );

	/**
	 * @var $nav DOMElement
	 */
	$nav = $dom->firstChild;

	if ( ! $nav ) {
		return $content;
	}

	/**
	 * @var $button DOMElement
	 */
	$button = $nav->getElementsByTagName( 'button' )->item( 0 );

	if ( ! $button ) {
		return $content;
	}

	$fragment = $button->ownerDocument->createDocumentFragment();

	$fragment->appendXML( '<span>Menu</span>' );
	$button->appendChild( $fragment );

	/**
	 * @var $label DOMElement
	 */
	$label = $button->getElementsByTagName( 'span' )->item( 0 );

	$label->setAttribute( 'class', 'screen-reader-text' );

	/**
	 * @var $svg DOMElement
	 */
	$svg = $button->getElementsByTagName( 'svg' )->item( 0 );

	$svg->firstChild->setAttribute( 'y', '6' );
	$svg->lastChild->setAttribute( 'y', '12' );

	$clone = $svg->lastChild->cloneNode( true );
	$clone->setAttribute( 'y', '18' );

	$svg->appendChild( $clone );

	return $dom->saveHTML();
}

