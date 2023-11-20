<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function apply_filters;
use function filemtime;
use function function_exists;
use function get_current_screen;
use function get_template;
use function home_url;
use function is_admin;
use function remove_action;
use function trailingslashit;
use function wp_add_inline_script;
use function wp_enqueue_script;
use function wp_get_theme;
use function wp_localize_script;
use function wp_register_script;

/**
 * Returns inline scripts.
 *
 * @since 1.3.2
 *
 * @param string $content Page content.
 * @param bool   $all     Whether to return all inline scripts.
 *
 * @return string
 */
function get_inline_scripts( string $content, bool $all ): string {

	/**
	 * Filters inline scripts.
	 *
	 * @since 0.0.27
	 *
	 * @param string $js      Inline scripts.
	 * @param string $content Template HTML.
	 * @param bool   $all     Whether to return all inline scripts.
	 */
	$js = apply_filters(
		'blockify_inline_js',
		'',
		$content,
		$all
	);

	return reduce_whitespace( trim( $js ) );
}

add_action( 'wp_enqueue_scripts', NS . 'enqueue_scripts' );
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
		get_inline_scripts(
			(string) ( $GLOBALS['template_html'] ?? '' ),
			false
		),
	);

	wp_enqueue_script( $handle );
}

add_action( 'enqueue_block_assets', NS . 'enqueue_editor_scripts' );
/**
 * Enqueues editor assets.
 *
 * @since 0.0.14
 *
 * @return void
 */
function enqueue_editor_scripts(): void {
	if ( ! is_admin() ) {
		return;
	}

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
 * @return array
 */
function get_editor_data(): array {
	$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	/**
	 * Filters editor data.
	 *
	 * @since 0.9.10
	 *
	 * @param array $data Editor data.
	 */
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
