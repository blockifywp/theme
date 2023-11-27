<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block_Patterns_Registry;
use function _cleanup_header_comment;
use function add_action;
use function apply_filters;
use function basename;
use function explode;
use function get_file_data;
use function get_stylesheet;
use function get_stylesheet_directory;
use function get_template;
use function get_template_directory;
use function glob;
use function implode;
use function in_array;
use function is_readable;
use function ksort;
use function ob_get_clean;
use function ob_start;
use function parse_blocks;
use function preg_match;
use function preg_quote;
use function register_block_pattern;
use function register_block_pattern_category;
use function remove_theme_support;
use function render_block;
use function serialize_block;
use function str_contains;
use function str_replace;
use function strip_core_block_namespace;
use function strtoupper;
use function ucwords;
use function wp_get_global_settings;

add_action( 'init', NS . 'remove_core_patterns', 9 );
/**
 * Removes core block patterns.
 *
 * @since 0.0.2
 *
 * @return void
 */
function remove_core_patterns(): void {
	remove_theme_support( 'core-block-patterns' );
}

add_action( 'init', NS . 'register_default_patterns' );
/**
 * Manually registers default patterns to avoid loading in child themes.
 *
 * @since 1.0.1
 *
 * @return void
 */
function register_default_patterns(): void {
	$default    = get_dir() . '/patterns/*';
	$template   = get_template_directory() . '/patterns/*';
	$stylesheet = get_stylesheet_directory() . '/patterns/*';
	$categories = [];

	$all_dirs = apply_filters( 'blockify_pattern_dirs', [
		...glob( $default, GLOB_ONLYDIR ),
		...glob( $template, GLOB_ONLYDIR ),
		...glob( $stylesheet, GLOB_ONLYDIR ),
	] );

	foreach ( $all_dirs as $dir ) {
		$files    = glob( $dir . '/*.php' );
		$category = basename( $dir );

		foreach ( $files as $file ) {
			$pattern = basename( $file, '.php' );

			if ( ! isset( $categories[ $category ] ) ) {
				$categories[ $category ] = [];
			}

			$categories[ $category ][ $pattern ] = $file;
		}
	}

	$categories = apply_filters( 'blockify_patterns', $categories );

	ksort( $categories );

	$registered_categories = [];
	$registered_slugs      = [];
	$all_patterns          = WP_Block_Patterns_Registry::get_instance()->get_all_registered() ?? [];

	foreach ( $all_patterns as $pattern ) {
		$registered_slugs[] = $pattern['slug'] ?? '';
	}

	foreach ( $categories as $category => $patterns ) {

		if ( ! in_array( $category, $registered_categories, true ) ) {

			if ( in_array( $category, [ 'cta', 'faq' ], true ) ) {
				$label = strtoupper( $category );
			} else {
				$label = ucwords( str_replace( '-', ' ', $category ) );
			}

			register_block_pattern_category(
				$category,
				[
					'label' => $label,
				]
			);

			$registered_categories[ $category ] = [];
		}

		foreach ( $patterns as $pattern => $file ) {
			$basename = basename( $file, '.php' );

			if ( in_array( $basename, $registered_categories[ $category ], true ) ) {
				continue;
			}

			$registered_categories[ $category ][] = $basename;

			$slug = $category . '-' . $basename;

			if ( in_array( $slug, $registered_slugs, true ) ) {
				continue;
			}

			register_block_pattern_from_file( $file );
		}
	}
}

/**
 * Parses a pattern file and returns the pattern data.
 *
 * @since 0.0.2
 *
 * @param string $file The file path.
 *
 * @return array
 */
function parse_pattern_file( string $file ): array {
	if ( ! $file ) {
		return [];
	}

	$content         = '';
	$default_headers = [
		'categories'  => 'Categories',
		'title'       => 'Title',
		'slug'        => 'Slug',
		'block_types' => 'Block Types',
		'inserter'    => 'Inserter',
		'ID'          => 'ID',
		'theme'       => 'Theme',
	];

	if ( is_readable( $file ) ) {
		$headers = get_file_data( $file, $default_headers );

		ob_start();
		$global_settings = wp_get_global_settings();

		include $file;
		$content = ob_get_clean();

	} else {
		if ( str_contains( $file, 'Title: ' ) ) {
			$content = $file;
			$headers = $default_headers;

			// Use regex from get_file_data().
			foreach ( $headers as $field => $regex ) {
				if ( preg_match( '/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file, $match ) && $match[1] ) {
					$headers[ $field ] = _cleanup_header_comment( $match[1] );
				} else {
					$headers[ $field ] = '';
				}
			}
		}
	}

	if ( ! isset( $headers['title'], $headers['slug'], $headers['categories'] ) ) {
		return [];
	}

	$categories = explode( ',', $headers['categories'] );

	$theme = $headers['theme'] ?? null;

	if ( ! $theme ) {
		$stylesheet_dir = get_stylesheet_directory();
		$template_dir   = get_template_directory();

		if ( $stylesheet_dir === $template_dir ) {
			$theme = get_template();
		} else if ( str_contains( $file, $stylesheet_dir ) ) {
			$theme = get_stylesheet();
		} else if ( str_contains( $file, $template_dir ) ) {
			$theme = get_template();
		}
	}

	$pattern = [
		'slug'          => $categories[0] . '-' . $headers['slug'],
		'title'         => $headers['title'],
		'content'       => str_replace_first(
			str_between( '<?php', '?>', $content ),
			'',
			$content
		),
		'categories'    => [ ...$categories ],
		'description'   => $headers['description'] ?? '',
		'viewportWidth' => $headers['viewport_width'] ?? null,
		'blockTypes'    => $headers['block_types'] ?? [],
		'ID'            => $headers['ID'] ?? null,
		'theme'         => $theme,
	];

	if ( ( $headers['inserter'] ?? null ) === 'false' ) {
		$pattern['inserter'] = false;
	}

	return $pattern;
}

/**
 * Parses and registers block pattern from PHP file with header comment.
 *
 * @since 0.0.8
 *
 * @param string $file Path to PHP file or content.
 *
 * @return void
 */
function register_block_pattern_from_file( string $file ): void {
	$pattern    = parse_pattern_file( $file );
	$categories = $pattern['categories'] ?? [];

	foreach ( $categories as $category ) {

		if ( in_array( $category, [ 'cta', 'faq' ], true ) ) {
			$label = strtoupper( $category );
		} else {
			$label = ucwords( str_replace( '-', ' ', $category ) );
		}

		register_block_pattern_category(
			$category,
			[
				'label' => $label,
			]
		);
	}

	register_block_pattern( $pattern['slug'], $pattern );
}

/**
 * Get block HTML.
 *
 * @since 1.3.0
 *
 * @param array $block  Block.
 * @param bool  $render Whether to render the block.
 *
 * @return string
 */
function get_block_html( array $block, bool $render = false ): string {
	$block['innerContent'] = $block['innerContent'] ?? [];
	$block['innerHTML']    = $block['innerHTML'] ?? '';
	$block['innerBlocks']  = $block['innerBlocks'] ?? [];
	$name                  = strip_core_block_namespace( $block['blockName'] ?? '' );

	if ( ! $name || empty( $block['innerBlocks'] ) ) {
		return serialize_block( $block );
	}

	$classes = array_filter( [
		'wp-block-' . $name,
		$block['attrs']['className'] ?? null,
		isset( $block['attrs']['fontSize'] ) ? 'has-' . $block['attrs']['fontSize'] . '-font-size' : null,
		isset( $block['attrs']['textColor'] ) ? 'has-' . $block['attrs']['textColor'] . '-color' : null,
		isset( $block['attrs']['backgroundColor'] ) ? 'has-' . $block['attrs']['backgroundColor'] . '-background-color' : null,
	] );

	$styles = array_filter( [
		'gap' => $block['attrs']['style']['spacing']['blockGap'] ?? null,
	] );

	$tag     = $block['tagName'] ?? $block['attrs']['tagName'] ?? 'div';
	$opening = sprintf( '<%s class="%s" style="%s">', $tag, implode( ' ', $classes ), css_array_to_string( $styles ) );
	$closing = sprintf( '</%s>', $tag );

	$inner_content = $block['innerContent'];
	array_unshift( $inner_content, $opening );
	$inner_content[] = $closing;

	foreach ( $block['innerBlocks'] as $inner_block ) {
		$inner_content[] = get_block_html( $inner_block );
	}

	$block['innerContent'] = $inner_content;
	$block['innerHTML']    = implode( '', $inner_content );

	$serialized   = serialize_block( $block );
	$parsed_block = parse_blocks( $serialized )[0];

	return $render ? render_block( $parsed_block ) : $serialized;
}
