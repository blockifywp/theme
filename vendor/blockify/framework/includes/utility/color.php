<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

/**
 * Applies block color attributes.
 *
 * @since 0.9.10
 *
 * @param array $styles Array of styles.
 * @param array $attrs  Array of attributes.
 *
 * @return array
 */
function add_block_support_color( array $styles, array $attrs ): array {
	$color = $attrs['style']['color'] ?? [];

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

	return $styles;
}
