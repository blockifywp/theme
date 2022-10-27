<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const DIRECTORY_SEPARATOR;
use const PHP_VERSION;
use function add_action;
use function array_map;
use function class_exists;
use function defined;
use function function_exists;
use function glob;
use function is_readable;
use function str_contains;
use function tgmpa;
use function version_compare;
use WPTRT\AdminNotices\Notices;

const SLUG = 'blockify';
const NAME = 'Blockify';
const NS   = __NAMESPACE__ . '\\';
const DS   = DIRECTORY_SEPARATOR;
const DIR  = __DIR__ . DS;
const FILE = __FILE__;

if ( ! version_compare( '7.4.0', PHP_VERSION, '<=' ) ) {
	return;
}

add_action( 'after_setup_theme', NS . 'setup', 9 );
/**
 * Sets up theme and allows child themes to override.
 *
 * @since 0.4.0
 *
 * @return void
 */
function setup(): void {
	array_map(
		static fn( string $file ) => is_readable( $file ) ? require_once $file : null,
		[
			DIR . 'vendor/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php',
			...glob( DIR . 'vendor/wptrt/admin-notices/src/*.php' ),
			...glob( DIR . 'includes/utility/*.php' ),
			DIR . 'includes/block-styles.php',
			DIR . 'includes/block-supports.php',
			DIR . 'includes/fonts.php',
			DIR . 'includes/optimization.php',
			DIR . 'includes/patterns.php',
			DIR . 'includes/scripts.php',
			DIR . 'includes/styles.php',
			...glob( DIR . 'includes/block-filters/*.php' ),
			...glob( DIR . 'includes/extensions/*.php' ),
		]
	);
}

add_action( 'after_setup_theme', NS . 'load_dependencies' );
/**
 * Load theme dependencies.
 *
 * @since 0.4.2
 *
 * @return void
 */
function load_dependencies(): void {
	if ( ! function_exists( 'tgmpa' ) || ! class_exists( 'WPTRT\\AdminNotices\\Notices' ) ) {
		return;
	}

	global $wp_version;

	$min_wp_version = '6.1';

	if ( str_contains( $wp_version, $min_wp_version ) || version_compare( $wp_version, $min_wp_version, '>=' ) ) {
		if ( ! defined( 'GUTENBERG_VERSION' ) ) {
			return;
		}

		$notice = new Notices();

		$notice->add(
			'blockify_deactivate_gutenberg',
			__( 'Please deactivate Gutenberg', 'blockify' ),
			__( 'Gutenberg is no longer required to use Blockify with WordPress 6.1 and higher. ', 'blockify' ) . '<a href="' . admin_url( 'plugins.php' ) . '">' . __( 'Go to plugins page →', 'blockify' ) . '</a>',
			[]
		);

		$notice->boot();

		return;
	}

	tgmpa(
		[
			[
				'name'     => 'Gutenberg',
				'slug'     => 'gutenberg',
				'required' => false,
			],
		]
	);
}
