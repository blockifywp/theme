<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/template-part', NS . 'render_block_template_part', 10, 2 );
/**
 * Modifies the template part block.
 *
 * @since 0.7.1
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_block_template_part( string $html, array $block ): string {
	$dom   = dom( $html );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $html;
	}

	$attrs  = $block['attrs'] ?? [];
	$styles = css_string_to_array( $first->getAttribute( 'style' ) );
	$color  = $attrs['style']['color'] ?? [];

	if ( isset( $color['background'] ) ) {
		$styles['background'] = $color['background'];
	}

	if ( isset( $attrs['backgroundColor'] ) ) {
		$styles['background'] = 'var(--wp--preset--color--' . $attrs['backgroundColor'] . ')';
	}

	if ( isset( $color['gradient'] ) ) {
		$styles['background'] = $color['gradient'];
	}

	if ( isset( $attrs['gradient'] ) ) {
		$styles['background'] = 'var(--wp--preset--gradient--' . $attrs['gradient'] . ')';
	}

	if ( isset( $color['text'] ) ) {
		$styles['color'] = $color['text'];
	}

	if ( isset( $attrs['textColor'] ) ) {
		$styles['color'] = 'var(--wp--preset--color--' . $attrs['textColor'] . ')';
	}

	$styles = css_array_to_string( $styles );

	if ( $styles ) {
		$first->setAttribute( 'style', $styles );
	} else {
		$first->removeAttribute( 'style' );
	}

	if ( $first->tagName === 'header' ) {
		$first->setAttribute( 'role', 'banner' );
		$first->setAttribute( 'id', 'top' );
	}

	if ( $first->tagName === 'footer' ) {
		$first->setAttribute( 'role', 'contentinfo' );
	}

	return $dom->saveHTML();
}
