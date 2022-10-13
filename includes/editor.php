<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function add_editor_style;
use function basename;
use function dirname;
use function do_action;
use function file_get_contents;
use function filemtime;
use function get_option;
use function glob;
use function home_url;
use function trim;
use function wp_dequeue_style;
use function wp_enqueue_script;
use function wp_enqueue_style;
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
	$siteEditor = $screen->base === 'appearance_page_gutenberg-edit-site' || $screen->base === 'site-editor';

	add_action(
		$siteEditor ? 'admin_enqueue_scripts' : 'enqueue_block_editor_assets',
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
	$asset   = require DIR . 'assets/js/editor.asset.php';
	$deps    = $asset['dependencies'];
	$options = get_option( SLUG );

	wp_dequeue_style( 'wp-block-library-theme' );

	wp_register_script(
		'blockify-editor',
		get_url() . 'assets/js/editor.js',
		$deps,
		filemtime( DIR . 'assets/js/editor.js' ),
		true
	);

	wp_enqueue_script( SLUG . '-editor' );

	$config = apply_filters(
		SLUG . '_editor_script',
		[
			'url'                => get_url(),
			'siteUrl'            => trailingslashit(
				home_url()
			),
			'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
			'nonce'              => wp_create_nonce( SLUG ),
			'icon'               => trim(
				file_get_contents( DIR . 'assets/svg/social/blockify.svg' )
			),
			'darkMode'           => ( $options['darkMode'] ?? false ) === 'true',
			'removeEmojiScripts' => ( $options['removeEmojiScripts'] ?? null ) === 'true',
			'excerptLength'      => $options['excerptLength'] ?? 33,
			'conicGradient'      => $options['conicGradient'] ?? '',
		]
	);

	wp_localize_script(
		'blockify-editor',
		SLUG,
		$config
	);
}

add_action( 'admin_init', NS . 'add_editor_styles' );
/**
 * Always load all styles in editor.
 *
 * @since 0.0.2
 *
 * @return void
 */
function add_editor_styles(): void {
	$files = [
		// Load all CSS when in editor.
		...glob( DIR . 'assets/css/blocks/*.css' ),
		...glob( DIR . 'assets/css/elements/*.css' ),
		...glob( DIR . 'assets/css/components/*.css' ),
		...glob( DIR . 'assets/css/formats/*.css' ),
		...glob( DIR . 'assets/css/extensions/*.css' ),
		...glob( DIR . 'assets/css/utility/*.css' ),
		...glob( DIR . 'assets/css/plugins/*.css' ),
	];

	foreach ( $files as $file ) {
		add_editor_style( 'assets/css/' . basename( dirname( $file ) ) . DS . basename( $file ) );
	}
}

add_action( 'blockify_editor_scripts', NS . 'enqueue_editor_only_styles' );
/**
 * Enqueues editor assets.
 *
 * @since 0.3.3
 *
 * @return void
 */
function enqueue_editor_only_styles(): void {
	wp_enqueue_style(
		'blockify-editor',
		get_url() . 'assets/css/editor.css',
		[],
		filemtime( DIR . 'assets/css/editor.css' )
	);
}
