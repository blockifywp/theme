<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const DIRECTORY_SEPARATOR;
use const PHP_VERSION;

use function add_action;
use function array_map;
use function glob;
use function version_compare;

if ( ! version_compare( '7.4.0', PHP_VERSION, '<=' ) ) {
	return;
}

const SLUG = 'blockify';
const NS   = __NAMESPACE__ . '\\';
const DS   = DIRECTORY_SEPARATOR;
const DIR  = __DIR__ . DS;
const FILE = __FILE__;

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
		static fn( string $file ) => require_once $file,
		[
			DIR . 'vendor/autoload.php',
			...glob( DIR . 'includes/utility/*.php' ),
			...glob( DIR . 'includes/config/*.php' ),
			...glob( DIR . 'includes/*.php' ),
			...glob( DIR . 'includes/blocks/*.php' ),
			...glob( DIR . 'includes/extensions/*.php' ),
		]
	);

	$dirs = glob( DIR . 'patterns/*', GLOB_ONLYDIR );

	$cleaned = [];

	foreach ( $dirs as $dir ) {

		$category_slug = \basename( $dir );

		$files = glob( $dir . '/*.php' );

		foreach ( $files as $file ) {
			$pattern_slug = \basename( $file, '.php' );
			$content      = file_get_contents( $file );

			$clean = \str_replace(
				str_between( "<?php\n", '?>', $content ),
				'',
				$content
			);

			\file_put_contents( $file, $clean );


			$cleaned[] = $clean;

		}
	}

	d( $cleaned );

}
