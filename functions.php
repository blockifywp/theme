<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const DIRECTORY_SEPARATOR;
use function add_action;
use function get_file_data;
use function get_post;
use function glob;
use function is_plugin_active;
use function ob_get_clean;
use function ob_start;
use function register_block_pattern;
use function register_block_pattern_category;
use function str_replace;
use function tgmpa;
use function ucwords;
use function wp_insert_post;

const FILE = __FILE__;
const DIR  = __DIR__ . DIRECTORY_SEPARATOR;
const NS   = __NAMESPACE__ . '\\';

require_once DIR . 'vendor/autoload.php';

add_action( 'after_setup_theme', NS . 'register_required_plugins' );
/**
 * Sets up theme.
 *
 * @since 0.0.1
 *
 * @return void
 */
function register_required_plugins(): void {
	tgmpa(
		[
			[
				'name'     => 'Blockify',
				'slug'     => 'blockify',
				'required' => true,
			],
			[
				'name'     => 'Gutenberg',
				'slug'     => 'gutenberg',
				'required' => true,
			],
		],
		[
			'is_automatic' => true,
		]
	);
}

add_action( 'after_setup_theme', NS . 'create_default_nav_menu' );
/**
 * Attempts to create a default navigation menu for pattern content.
 *
 * @since 0.0.5
 *
 * @return void
 */
function create_default_nav_menu(): void {
	if ( ! get_post( 999 ) ) {
		wp_insert_post( [
			'import_id'    => 999,
			'post_title'   => __( 'Default', 'blockify' ),
			'post_type'    => 'wp_navigation',
			'post_status'  => 'publish',
			'post_content' => '<!-- wp:navigation-link {"label":"Home","type":"custom","url":"#","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"About","url":"#","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Pricing","url":"#","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Blog","url":"#","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Contact","url":"#","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Log In","url":"#","kind":"custom","isTopLevelLink":true} /-->',
		] );
	}
}

add_action( 'after_setup_theme', NS . 'register_default_patterns' );
/**
 * Registers patterns on front end to support pattern block.
 *
 * @since 0.0.5
 *
 * @return void
 */
function register_default_patterns() {
	if ( is_plugin_active( 'blockify/blockify.php' ) ) {
		return;
	}

	$categories = [];
	$patterns   = [];

	foreach ( glob( DIR . 'patterns/default/*.php' ) as $file ) {
		$headers = get_file_data( $file, [
			'categories'  => 'Categories',
			'title'       => 'Title',
			'slug'        => 'Slug',
			'block_types' => 'Block Types',
		] );

		$category = str_replace( 'blockify/', '', $headers['categories'] );

		ob_start();
		include $file;
		$content = ob_get_clean();

		$patterns[ $headers['slug'] ] = [
			'title'      => $headers['title'],
			'categories' => [ $category ],
			'content'    => $content,
		];

		if ( $headers['block_types'] ) {
			$patterns[ $headers['slug'] ]['blockTypes'] = $headers['block_types'];
		}

		$categories[ $category ] = [
			'label' => ucwords( $category ),
		];
	}

	foreach ( $categories as $category_name => $args ) {
		register_block_pattern_category( $category_name, $args );
	}

	foreach ( $patterns as $pattern_name => $args ) {
		register_block_pattern( $pattern_name, $args );
	}
}
