<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Screen;
use function add_action;
use function apply_filters;
use function do_action;
use function filemtime;
use function function_exists;
use function get_current_screen;
use function get_template;
use function home_url;
use function remove_action;
use function trailingslashit;
use function wp_add_inline_script;
use function wp_enqueue_script;
use function wp_get_theme;
use function wp_localize_script;
use function wp_register_script;

add_action( 'current_screen', NS . 'add_editor_scripts_hook', 10, 1 );
/**
 * Conditionally changes which action hook editor assets are enqueued.
 *
 * @since 0.0.19
 *
 * @param WP_Screen $screen Current screen.
 *
 * @return void
 */
function add_editor_scripts_hook( WP_Screen $screen ): void {
	$site_editor = $screen->base === 'site-editor';

	if ( ! $site_editor && function_exists( 'is_gutenberg_page' ) && ! \is_gutenberg_page() ) {
		return;
	}

	if ( ! $site_editor && ! $screen->is_block_editor() ) {
		return;
	}

	add_action(
		$site_editor ? 'admin_enqueue_scripts' : 'enqueue_block_editor_assets',
		static fn() => do_action( 'blockify_editor_scripts', $screen )
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
	$dir   = get_dir();
	$asset = $dir . 'assets/js/editor.asset.php';

	if ( ! file_exists( $asset ) ) {
		return;
	}

	$asset  = require $asset;
	$handle = 'blockify-editor';
	$file   = 'assets/js/editor.js';

	wp_register_script(
		$handle,
		get_uri() . $file,
		$asset['dependencies'] ?? [],
		filemtime( $dir . $file ),
		true
	);

	wp_enqueue_script( $handle );

	wp_localize_script(
		$handle,
		'blockify',
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
		'blockify_editor_data',
		[
			'url'           => get_uri(),
			'siteUrl'       => trailingslashit( home_url() ),
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'adminUrl'      => trailingslashit( admin_url() ),
			'nonce'         => wp_create_nonce( 'blockify' ),
			'icon'          => get_icon( 'social', 'blockify' ),
			'siteEditor'    => $current_screen && $current_screen->base === 'site-editor',
			'excerptLength' => apply_filters( 'excerpt_length', 55 ),
		]
	);
}

// Prevent special characters converting to emojis (arrows, lines, etc.).
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

add_action( 'wp_enqueue_scripts', NS . 'enqueue_scripts', 10 );
/**
 * Register proxy handle for inline frontend scripts.
 *
 * Called in styles.php to share page content string.
 *
 * @since 0.0.27
 *
 * @return void
 */
function enqueue_scripts(): void {
	$handle = get_template();

	wp_register_script(
		$handle,
		'',
		[],
		wp_get_theme()->get( 'version' ),
		true
	);

	wp_add_inline_script(
		$handle,
		reduce_whitespace(
			trim(
				apply_filters(
					'blockify_inline_js',
					'',
					(string) ( $GLOBALS['template_html'] ?? '' )
				)
			)
		)
	);

	wp_enqueue_script( $handle );
}
