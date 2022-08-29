<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function explode;
use function implode;

add_filter( 'render_block_core/post-featured-image', NS . 'render_featured_image_block', 10, 2 );
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
function render_featured_image_block( string $content, array $block ): string {

	if ( ! $content ) {
		$attrs = $block['attrs'] ?? [];
		$dom   = dom( '<span></span>' );

		/** @var DOMElement $span */
		$span    = $dom->getElementsByTagName( 'span' )->item( 0 );
		$classes = explode( ' ', $span->getAttribute( 'class' ) );
		$styles  = explode( ';', $span->getAttribute( 'style' ) );

		$classes[] = 'wp-block-post-featured-image__placeholder';
		$classes[] = 'is-style-placeholder';

		if ( isset( $block['attrs']['border'] ) ) {
			$classes[] = 'has-border';
		}
		$css = '';

		if ( $attrs['style']['spacing']['margin']['bottom'] ?? null ) {
			$css .= 'margin-bottom:' . $attrs['style']['spacing']['margin']['bottom'] . ';';
		}

		$span->setAttribute( 'class', implode( ' ', $classes ) );

		foreach ( $styles as $property => $value ) {
			if ( $value ) {
				$css .= "$property:$value;";
			}
		}

		$span->setAttribute( 'style', $css );

		$content = $dom->saveHTML();
	}

	return $content;
}
