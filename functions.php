<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const DIRECTORY_SEPARATOR;
use function add_editor_style;
use function add_filter;
use function defined;
use function in_array;
use function register_block_pattern_category;
use function register_block_style;
use function add_action;
use function add_theme_support;
use function array_map;
use function basename;
use function dirname;
use function filemtime;
use function get_page_by_path;
use function get_template_directory_uri;
use function glob;
use function home_url;
use function is_null;
use function ob_get_clean;
use function ob_start;
use function str_contains;
use function str_replace;
use function strtolower;
use function tgmpa;
use function wp_enqueue_style;
use stdClass;
use WP_Post;
use WP_Block_Pattern_Categories_Registry;
use WP_Block_Patterns_Registry;

const FILE = __FILE__;
const DIR  = __DIR__ . DIRECTORY_SEPARATOR;
const NS   = __NAMESPACE__ . '\\';

require_once DIR . 'vendor/autoload.php';

add_theme_support( 'blockify' );

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
				'required' => false,
			],
			[
				'name'     => 'Gutenberg',
				'slug'     => 'gutenberg',
				'required' => false,
			],
		],
		[
			'is_automatic' => true,
		]
	);
}

add_action( 'after_setup_theme', NS . 'block_styles' );
/**
 * Registers block styles.
 *
 * @since 0.0.13
 *
 * @return void
 */
function block_styles(): void {
	if ( defined( 'Blockify\\SLUG' ) ) {
		return;
	}

	register_block_style( 'core/button', [
		'name'  => 'transparent',
		'label' => __( 'Transparent', 'blockify' ),
	] );
}

add_action( 'wp_enqueue_scripts', NS . 'enqueue_styles' );
/**
 * Enqueues main stylesheet.
 *
 * @since 0.0.1
 *
 * @return void
 */
function enqueue_styles(): void {
	if ( defined( 'Blockify\\SLUG' ) ) {
		return;
	}

	wp_enqueue_style(
		'blockify-theme',
		get_template_directory_uri() . '/style.css',
		[],
		filemtime( DIR . 'style.css' ),
	);
}

add_action( 'after_setup_theme', NS . 'editor_styles' );
/**
 * Adds editor styles.
 *
 * @since 0.0.13
 *
 * @return void
 */
function editor_styles(): void {
	add_editor_style( 'style.css' );
}


add_action( 'after_setup_theme', NS . 'theme_supports' );
/**
 * Handles theme supports.
 *
 * @since 0.0.2
 *
 * @return void
 */
function theme_supports(): void {
	remove_theme_support( 'core-block-patterns' );
}

add_action( 'after_setup_theme', NS . 'post_type_supports' );
/**
 * Handles post type supports.
 *
 * @since 0.0.2
 *
 * @return void
 */
function post_type_supports(): void {
	add_post_type_support( 'page', 'excerpt' );
	add_post_type_support( 'block_pattern', 'excerpt' );
	add_post_type_support( 'page', 'custom-fields' );
}

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

add_filter( 'the_posts', NS . 'generate_pattern_preview', -10 );
/**
 * Allows patterns to be previewed on front end without creating posts in the database.
 *
 * For demonstration purposes only.
 *
 * @since 0.0.9
 *
 * @param array $posts Original posts object.
 *
 * @return array
 */
function generate_pattern_preview( array $posts ): array {
	global $wp, $wp_query;
	static $static = null;

	if ( ! is_null( $static ) ) {
		return $posts;
	}

	$pages = array_map( fn( $file ) => str_replace(
		dirname( $file ) . DIRECTORY_SEPARATOR,
		'',
		basename( $file, '.php' )
	), glob( DIR . 'patterns/*.php' ) );

	foreach ( $pages as $page ) {
		if ( strtolower( $wp->request ) !== $page ) {
			continue;
		}

		if ( get_page_by_path( $page, OBJECT, [ 'post', 'page', 'block_pattern' ] ) ) {
			continue;
		}

		$static  = true;
		$is_page = str_contains( $page, 'page-' );

		preg_match_all( '!\d+!', $page, $matches );

		add_action( 'wp_head', fn() => print '<meta name="robots" content="noindex,nofollow">' );

		ob_start();

		if ( $is_page ) {
			include DIR . 'patterns/header-' . ( $matches[0][0] ?? 1 ) . '.php';
		}

		include DIR . 'patterns/' . $page . '.php';

		if ( $is_page ) {
			include DIR . 'patterns/footer-' . ( $matches[0][0] ?? 1 ) . '.php';
		}

		$content = ob_get_clean();

		/**
		 * @var $post WP_Post
		 */
		$post                          = new stdClass;
		$post->post_author             = 1;
		$post->post_name               = $page;
		$post->guid                    = home_url() . '/' . $page;
		$post->post_title              = ucwords( str_replace( '-', ' ', $page ) );
		$post->post_content            = $content;
		$post->ID                      = -999;
		$post->post_type               = 'block_pattern';
		$post->post_status             = 'private';
		$post->comment_status          = 'closed';
		$post->ping_status             = 'closed';
		$post->comment_count           = 0;
		$post->post_date               = current_time( 'mysql' );
		$post->post_date_gmt           = current_time( 'mysql', 1 );
		$posts                         = null;
		$posts[]                       = $post;
		$wp_query->is_page             = true;
		$wp_query->is_singular         = true;
		$wp_query->is_home             = false;
		$wp_query->is_archive          = false;
		$wp_query->is_category         = false;
		$wp_query->query_vars['error'] = '';
		$wp_query->is_404              = false;
		unset( $wp_query->query['error'] );
		$static = true;
	}

	return $posts;
}

add_filter( 'blockify', NS . 'add_config' );
/**
 * Adds theme config.
 *
 * @since 0.0.13
 *
 * @param array $defaults
 *
 * @return array
 */
function add_config( array $defaults ): array {

	$defaults['blockStyles']['unregister'] = [
		[
			'type' => 'core/button',
			'name' => [ 'fill', 'outline' ],
		],
		[
			'type' => 'core/separator',
			'name' => [ 'wide', 'dots' ],
		],
	];

	$defaults['blockStyles']['register'] = [
		[
			'type'      => 'core/button',
			'name'      => 'primary',
			'label'     => __( 'Primary', 'blockify' ),
			'isDefault' => true,
		],
		[
			'type'  => 'core/button',
			'name'  => 'secondary',
			'label' => __( 'Secondary', 'blockify' ),
		],
		[
			'type'  => 'core/button',
			'name'  => 'outline',
			'label' => __( 'Outline', 'blockify' ),
		],
		[
			'type'  => 'core/button',
			'name'  => 'transparent',
			'label' => __( 'Transparent', 'blockify' ),
		],
		[
			'type'  => 'core/list',
			'name'  => 'numbered',
			'label' => __( 'Numbered', 'blockify' ),
		],
		[
			'type'  => 'core/list',
			'name'  => 'checklist',
			'label' => __( 'Checklist', 'blockify' ),
		],
		[
			'type'  => 'core/list',
			'name'  => 'square',
			'label' => __( 'Square', 'blockify' ),
		],
	];

	$defaults['darkMode'] = [
		'neutral-900' => 'neutral-100',
		'neutral-800' => 'neutral-200',
		'neutral-700' => 'neutral-200',
		'neutral-600' => 'neutral-300',
		'neutral-500' => 'neutral-300',
		'neutral-400' => 'neutral-400',
		'neutral-300' => 'neutral-500',
		'neutral-200' => 'neutral-500',
		'neutral-100' => 'neutral-600',
		'neutral-50'  => 'neutral-700',
		'neutral-25'  => 'neutral-800',
		'white'       => 'neutral-900',
	];

	return $defaults;
}
