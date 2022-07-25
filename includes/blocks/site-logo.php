<?php

declare( strict_types=1 );

namespace Blockify;

use function file_exists;
use function file_get_contents;
use function str_contains;
use function str_replace;
use function stream_context_create;

add_filter( 'render_block_site_logo', NS . 'render_site_logo_block', 10, 2 );
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
function render_site_logo_block( string $content, array $block ): string {
	$img  = str_between( '<img ', '/>', $content );
	$src  = get_attr( 'src', $img );
	$svg  = false;
	$file = str_replace(
		content_url(),
		WP_CONTENT_DIR,
		$src
	);

	if ( file_exists( $file ) ) {
		$svg = file_get_contents(
			$file,
			false,
			stream_context_create( [
				"ssl" => [
					"verify_peer"      => false,
					"verify_peer_name" => false,
				],
			] )
		);
	}

	if ( $svg && str_contains( '.svg', $src ) ) {
		$content = str_replace(
			$img,
			$svg,
			$content
		);

		$content = str_replace(
			' height="250"',
			'',
			$content
		);
	}

	return $content;
}
