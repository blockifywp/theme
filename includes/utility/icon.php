<?php

declare( strict_types=1 );

namespace Blockify\Theme;

/**
 * Renders image icon styles on front end.
 *
 * @since 0.2.0
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function get_icon_html( string $content, array $block ): string {
	$content = ! $content ? '<figure class="wp-block-image is-style-icon"><img src="" alt=""/></figure>' : $content;
	$dom     = dom( $content );
	$figure  = get_dom_element( 'figure', $dom );
	$img     = get_dom_element( 'img', $figure );

	if ( ! $figure || ! $img ) {
		return $content;
	}

	$link    = get_dom_element( 'a', $figure );
	$span    = change_tag_name( $img, 'span' );
	$classes = explode( ' ', $figure->getAttribute( 'class' ) );
	$styles  = css_string_to_array( $figure->getAttribute( 'style' ) );
	$classes = array_diff( $classes, [ 'wp-block-image', 'is-style-icon' ] );

	// phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
	$icon_set  = $block['attrs']['iconSet'] ?? 'wordpress';
	$icon_name = $block['attrs']['iconName'] ?? 'star-empty';

	$classes = [
		'wp-block-image__icon',
		'blockify-icon',
		'blockify-icon-' . $icon_set . '-' . $icon_name,
		...$classes,
	];

	$color = $styles['----wp--custom--icon--color'] ?? 'currentColor';

	if ( $color && $color !== 'currentColor' ) {
		$styles['--wp--custom--icon--color'] = $color;
	}

	$aria_label = $img->getAttribute( 'alt' ) ? $img->getAttribute( 'alt' ) : str_replace( '-', ' ', $icon_name ) . __( ' icon', 'blockify' );

	$span->setAttribute( 'class', implode( ' ', $classes ) );

	if ( $styles ) {
		$span->setAttribute( 'style', css_array_to_string( $styles ) );
	}

	$span->setAttribute( 'title', $block['attrs']['title'] ?? $aria_label ?? __( 'SVG Icon' ) );

	if ( ! ( $block['attrs']['title'] ?? null ) ?? ! $aria_label ) {
		$span->setAttribute( 'aria-label', $aria_label );
		$span->setAttribute( 'role', 'presentation' );
	}

	$span->removeAttribute( 'src' );
	$span->removeAttribute( 'alt' );

	$figure->setAttribute( 'class', 'wp-block-image is-style-icon' );
	$figure->setAttribute( 'style', '' );

	if ( $link ) {
		$link->appendChild( $span );
	} else {
		$figure->appendChild( $span );
	}

	return $dom->saveHTML();
}


