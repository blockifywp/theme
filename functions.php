<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const DIRECTORY_SEPARATOR;
use function add_action;
use function add_theme_support;
use function array_map;
use function basename;
use function dirname;
use function get_page_by_path;
use function get_post;
use function glob;
use function home_url;
use function is_null;
use function ob_get_clean;
use function ob_start;
use function str_contains;
use function str_replace;
use function strtolower;
use function tgmpa;
use function wp_add_inline_style;
use function wp_insert_post;
use stdClass;
use WP_Post;

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

add_action( 'wp_enqueue_scripts', NS . 'enqueue_styles' );
/**
 * Enqueues main stylesheet.
 *
 * @since 0.0.1
 *
 * @return void
 */
function enqueue_styles(): void {
	wp_add_inline_style(
		'global-styles',
		'html { -webkit-font-smoothing: antialiased }'
	);
}

add_action( 'after_switch_theme', NS . 'activate' );
/**
 * Sets up site. (For demonstration purposes only).
 *
 * @since 0.0.5
 *
 * @return void
 */
function activate(): void {
	if ( ! get_post( 999 ) ) {
		$home_url = home_url();

		wp_insert_post( [
			'import_id'    => 999,
			'post_title'   => __( 'Default', 'blockify' ),
			'post_type'    => 'wp_navigation',
			'post_status'  => 'publish',
			'post_content' => <<<HTML
<!-- wp:navigation-link {"label":"Home","type":"custom","url":"$home_url/page-landing-1","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"About","url":"$home_url/page-about-1","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Pricing","url":"$home_url/page-pricing-1","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Blog","url":"$home_url/page-blog-1","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Contact","url":"$home_url/page-contact-1","kind":"custom","isTopLevelLink":true} /-->
HTML,
		] );
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
