<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function add_action;
use function apply_filters;
use function array_diff;
use function get_option;
use function remove_action;
use function remove_filter;
use function str_replace;

add_filter( 'render_block', NS . 'remove_duplicate_classes', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * Can't use DOMDocument because it breaks closing tags.
 *
 * @since 0.0.2
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function remove_duplicate_classes( string $content, array $block ): string {
	return str_replace(
		[ 'alignfull alignfull', 'alignwide alignwide' ],
		[ 'alignfull', 'alignwide' ],
		$content
	);
}

add_action( 'admin_init', NS . 'remove_emoji_scripts' );
add_action( 'after_setup_theme', NS . 'remove_emoji_scripts' );
/**
 * Removes unused emoji scripts.
 *
 * @since 0.0.21
 *
 * @return void
 */
function remove_emoji_scripts(): void {

	// Defaults to true for theme previews.
	if ( ! ( get_option( 'blockify', [] )['removeEmojiScripts'] ?? true ) ) {
		return;
	}

	$options = get_option( SLUG );
	$enabled = true;

	if ( isset( $options['removeEmojiScripts'] ) ) {
		$enabled = $options['removeEmojiScripts'] === 'true';
	}

	if ( ! $enabled ) {
		return;
	}

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'tiny_mce_plugins', fn( array $plugins = [] ) => array_diff(
		$plugins,
		[ 'wpemoji' ]
	) );
	add_filter( 'wp_resource_hints', function ( array $urls, string $relation_type ): array {
		if ( 'dns-prefetch' === $relation_type ) {
			$urls = array_diff( $urls, [ apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' ) ] );
		}

		return $urls;
	}, 10, 2 );
}
