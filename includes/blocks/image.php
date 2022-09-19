<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function str_contains;

add_filter( 'render_block_core/image', NS . 'render_image_block', 10, 2 );
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
function render_image_block( string $content, array $block ): string {
	$class_name = $block['attrs']['className'] ?? null;

	if ( $class_name && str_contains( $class_name, 'is-style-placeholder' ) ) {
		$border_color = $block['attrs']['style']['border']['color'] ?? null;

		if ( isset( $block['attrs']['borderColor'] ) ) {
			$border_color = 'var(--wp--preset--color--' . $block['attrs']['borderColor'] . ')';
		}

		$dom = dom( $content );

		if ( ! $dom->firstChild ) {
			return $content;
		}

		/* @var DOMElement $first_child Image element. */
		$first_child = $dom->getElementsByTagName( 'figure' )->item( 0 );

		$original = $first_child->getAttribute( 'style' );

		if ( $border_color ) {
			$first_child->setAttribute( 'style', $original . ( $original ? ';' : '' ) . '--wp--custom--border--color:' . $border_color );
		}

		$img = $first_child->getElementsByTagName( 'img' )->item( 0 );

		if ( $img ) {
			$first_child->removeChild( $img );
		}

		return $dom->saveHTML();
	}

	return $content;
}
