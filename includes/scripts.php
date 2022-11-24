<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function add_filter;
use function apply_filters;
use function array_diff;
use function get_option;
use function remove_action;
use function remove_filter;
use function wp_add_inline_script;
use function wp_enqueue_script;
use function wp_get_theme;
use function wp_register_script;

add_action( 'wp_enqueue_scripts', NS . 'enqueue_scripts', 10 );
/**
 * Register proxy handle for inline scripts.
 *
 * Called in styles.php to share page content string.
 *
 * @since 0.0.27
 *
 * @return void
 */
function enqueue_scripts(): void {
	$content = get_page_content();

	wp_register_script( SLUG, '', [], wp_get_theme()->get( 'version' ), true );

	wp_add_inline_script(
		SLUG,
		reduce_whitespace(
			trim(
				apply_filters(
					'blockify_inline_js',
					'',
					$content
				)
			)
		)
	);

	wp_enqueue_script( SLUG );
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
	$options = get_option( SLUG );
	$enabled = ( $options['removeEmojiScripts'] ?? 'true' ) === 'true';

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
	add_filter(
		'tiny_mce_plugins',
		fn( array $plugins = [] ) => array_diff(
			$plugins,
			[ 'wpemoji' ]
		)
	);
	add_filter(
		'wp_resource_hints',
		function ( array $urls, string $relation_type ): array {
			if ( $relation_type === 'dns-prefetch' ) {
				$urls = array_diff( $urls, [ apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' ) ] );
			}

			return $urls;
		},
		10,
		2
	);
}
