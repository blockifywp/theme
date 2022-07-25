<?php

declare( strict_types=1 );

namespace Blockify;

use DOMDocument;
use DOMElement;
use function content_url;
use function file_exists;
use function file_get_contents;
use function implode;
use function is_numeric;
use function str_contains;
use function str_replace;
use function str_split;
use function add_filter;
use function urldecode;

add_filter( 'render_block', NS . 'render_button_block', 10, 2 );
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
function render_button_block( string $content, array $block ): string {
	if ( 'core/button' !== $block['blockName'] ) {
		return $content;
	}

	if ( str_contains( $content, '-border-' ) ) {
		$dom = dom( $content );

		/**
		 * @var $button \DOMElement
		 */
		$button  = $dom->firstChild;
		$classes = \explode( ' ', $button->getAttribute( 'class' ) );
		$new     = [];

		foreach ( $classes as $class ) {
			if ( ! str_contains( $class, '-border-' ) ) {
				$new[] = $class;
			}
		}

		$button->setAttribute( 'class', implode( ' ', $new ) );
		$content = $dom->saveHTML();
	}

	return $content;
}
