<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function array_merge_recursive;
use function add_action;
use function do_action;
use function file_get_contents;
use function filemtime;
use function get_option;
use function wp_enqueue_script;
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
	$hookName   = $siteEditor ? 'admin_enqueue_scripts' : 'enqueue_block_editor_assets';

	add_action( $hookName, fn() => do_action( 'blockify_editor_scripts' ) );
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

	wp_enqueue_script( 'blockify-editor' );

	$svg = trim( file_get_contents( DIR . 'assets/svg/social/blockify.svg' ) );

	$remove_emojis = get_option( SLUG )['removeEmojiScripts'] ?? null;

	wp_localize_script(
		'blockify-editor',
		SLUG,
		array_merge_recursive(
			[
				'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
				'nonce'              => wp_create_nonce( SLUG ),
				'icon'               => $svg,
				'removeEmojiScripts' => $remove_emojis === 'true',
				'excerptLength'      => get_option( SLUG )['excerptLength'] ?? 33,
			],
			get_config()
		)
	);
}
