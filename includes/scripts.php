<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function add_filter;
use function apply_filters;
use function array_diff;
use function do_action;
use function filemtime;
use function function_exists;
use function get_option;
use function home_url;
use function remove_action;
use function remove_filter;
use function trailingslashit;
use function wp_add_inline_script;
use function wp_enqueue_script;
use function wp_get_theme;
use function wp_register_script;
use WP_Screen;

add_action( 'current_screen', NS . 'maybe_load_editor_assets' );
/**
 * Conditionally changes which action hook editor assets are enqueued.
 *
 * @since 0.0.19
 *
 * @param WP_Screen $screen Current screen.
 *
 * @return void
 */
function maybe_load_editor_assets( WP_Screen $screen ): void {
	add_action(
		$screen->base === 'site-editor' ? 'admin_enqueue_scripts' : 'enqueue_block_editor_assets',
		static fn() => do_action( 'blockify_editor_scripts' )
	);
}

add_action( 'blockify_editor_scripts', NS . 'enqueue_editor_scripts' );
/**
 * Enqueues editor assets.
 *
 * @since 0.0.14
 *
 * @return void
 */
function enqueue_editor_scripts(): void {
	$asset = require DIR . 'assets/js/editor.asset.php';
	$deps  = $asset['dependencies'];

	wp_register_script(
		'blockify-editor',
		get_url() . 'assets/js/editor.js',
		$deps,
		filemtime( DIR . 'assets/js/editor.js' ),
		true
	);

	wp_enqueue_script( SLUG . '-editor' );

	wp_localize_script(
		'blockify-editor',
		SLUG,
		get_editor_data()
	);
}

/**
 * Returns filtered editor data.
 *
 * @since 0.9.10
 *
 * @return mixed|void
 */
function get_editor_data() {
	$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	return apply_filters(
		SLUG . '_editor_script',
		[
			'url'        => get_url(),
			'siteUrl'    => trailingslashit(
				home_url()
			),
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'adminUrl'   => trailingslashit( admin_url() ),
			'nonce'      => wp_create_nonce( SLUG ),
			'icon'       => get_icon( 'social', SLUG ),
			'siteEditor' => $current_screen && $current_screen->base === 'site-editor',
		]
	);
}

add_action( 'wp_enqueue_scripts', NS . 'enqueue_custom_js' );
/**
 * Enqueues custom CSS.
 *
 * @since 0.0.27
 *
 * @return void
 */
function enqueue_custom_js(): void {
	$header = get_option( 'blockify_settings' )['custom_js_header'] ?? '';
	$footer = get_option( 'blockify_settings' )['custom_js_footer'] ?? '';

	if ( $header ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		add_action( 'wp_head', fn() => $header, 100 );
	}

	if ( $footer ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		add_action( 'wp_footer', fn() => $footer, 100 );
	}
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

/**
 * Register proxy handle for inline scripts.
 *
 * Called in styles.php to share page content string.
 *
 * @since 0.0.27
 *
 * @param string $content Page content.
 *
 * @return void
 */
function add_inline_scripts( string $content ): void {
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

