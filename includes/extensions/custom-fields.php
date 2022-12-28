<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function get_post_field;
use function get_the_ID;
use function preg_match;
use function shortcode_exists;
use function str_replace;

add_filter( 'render_block', NS . 'render_custom_fields' );
/**
 * Allow custom field strings to be rendered in blocks.
 *
 * @since 0.9.34
 *
 * @param string $html  Block HTML.
 *
 * @return string
 */
function render_custom_fields( string $html ): string {
	preg_match( '#\[(.*?)]#', $html, $matches );

	if ( ! isset( $matches[1] ) ) {
		return $html;
	}

	if ( shortcode_exists( $matches[1] ) ) {
		return $html;
	}

	$custom_field = get_post_field( $matches[1], get_the_ID() );

	if ( $custom_field ) {
		$html = str_replace( $matches[0], $custom_field, $html );
	}

	return $html;
}
