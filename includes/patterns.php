<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block_Pattern_Categories_Registry;
use WP_Block_Patterns_Registry;
use function add_action;
use function file_exists;
use function glob;
use function html_entity_decode;
use function in_array;
use function is_string;
use function ob_get_clean;
use function ob_start;
use function register_block_pattern;
use function register_block_pattern_category;
use function str_contains;
use function str_replace;
use function ucfirst;
use function ucwords;
use function wp_list_pluck;
use function wp_remote_get;

add_action( 'init', NS . 'register_root_level_pattern_categories', 11 );
/**
 * Generates categories for patterns automatically registered by core.
 *
 * @since 0.0.2
 *
 * @return void
 */
function register_root_level_pattern_categories(): void {
	$block_patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();

	foreach ( $block_patterns as $block_pattern ) {
		if ( ! isset( $block_pattern['categories'] ) ) {
			continue;
		}

		foreach ( $block_pattern['categories'] as $category ) {
			$categories = wp_list_pluck( WP_Block_Pattern_Categories_Registry::get_instance()->get_all_registered(), 'name' );

			if ( in_array( $category, $categories ) ) {
				continue;
			}

			register_block_pattern_category( $category, [
				'label' => ucfirst( $category ),
			] );
		}
	}
}

add_filter( 'template_include', NS . 'single_block_pattern_template' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $template
 *
 * @return mixed|string
 */
function single_block_pattern_template( string $template ): string {
	if ( is_singular( 'block_pattern' ) ) {
		$template = locate_block_template( DIR . 'templates/blank.html', 'blank', [] );
	}

	return $template;
}

add_action( 'init', NS . 'unregister_unused_patterns' );
/**
 * Unregister patterns that aren't used by active style variation.
 *
 * @since 0.4.0
 *
 * @return void
 */
function unregister_unused_patterns(): void {
	$patterns = wp_list_pluck(
		WP_Block_Patterns_Registry::get_instance()->get_all_registered(),
		'slug'
	);

	$style_variation = get_style_variation();

	if ( ! $style_variation || 'default' === $style_variation ) {
		return;
	}

	foreach ( $patterns as $pattern ) {
		if ( ! str_contains( $pattern, $style_variation ) ) {
			unregister_block_pattern( $pattern );
		}
	}

	$pattern_names = [
		'blog-three-column',
		'blog-single-column',
	];

	foreach ( $pattern_names as $pattern_name ) {
		$transient_name = "blockify/$style_variation/$pattern_name";
		$transient      = get_transient( $transient_name ) ?? null;

		if ( 0 ) {
			$request = wp_remote_get(
				'https://raw.githubusercontent.com/blockifywp/theme/main/patterns/' . $pattern_name . '.php'
			);

			if ( ( $request['response']['code'] ?? null ) !== 200 ) {
				return;
			}

			$transient = html_entity_decode( $request['body'] );

			set_transient(
				$transient_name,
				$transient,
				24 * HOUR_IN_SECONDS
			);
		}

		if ( is_string( $transient ) ) {
			register_block_pattern_from_file( $transient );
		}
	}
}

/**
 * Parses and registers block pattern from PHP file with header comment.
 *
 * @since 0.0.8
 *
 * @param string $file Path to PHP file.
 *
 * @return void
 */
function register_block_pattern_from_file( string $file ): void {
	$content = $file;

	if ( file_exists( $file ) ) {
		$headers = get_file_data( $file, [
			'categories'  => 'Categories',
			'title'       => 'Title',
			'slug'        => 'Slug',
			'block_types' => 'Block Types',
		] );

		ob_start();
		include $file;
		$content = ob_get_clean();

	} else if ( str_contains( $file, 'Title: ' ) ) {
		$headers = [
			'title'       => 'Title',
			'slug'        => 'Slug',
			'categories'  => 'Categories',
			'block_types' => 'Block Types',
		];

		// @see get_file_data().
		foreach ( $headers as $field => $regex ) {
			if ( preg_match( '/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file, $match ) && $match[1] ) {
				$headers[ $field ] = _cleanup_header_comment( $match[1] );
			} else {
				$headers[ $field ] = '';
			}
		}
	}

	$categories = explode( ',', $headers['categories'] );

	$pattern = [
		'title'      => $headers['title'],
		'content'    => str_replace(
			str_between( '<?php', '?>', $content ),
			'',
			$content
		),
		'categories' => [ ...$categories ],
	];

	if ( $headers['block_types'] ) {
		$pattern['blockTypes'] = $headers['block_types'];
	}

	foreach ( $categories as $category ) {
		register_block_pattern_category( $category, [
			'label' => ucwords( $category ),
		] );
	}

	register_block_pattern( $headers['slug'], $pattern );
}
