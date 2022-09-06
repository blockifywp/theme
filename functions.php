<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const DIRECTORY_SEPARATOR;
use const PHP_VERSION;
use function array_map;
use function file_exists;
use function function_exists;
use function glob;
use function str_replace;
use function version_compare;

if ( ! version_compare( '7.4.0', PHP_VERSION, '<=' ) ) {
	return;
}

const SLUG = 'blockify';
const NS   = __NAMESPACE__ . '\\';
const DS   = DIRECTORY_SEPARATOR;
const DIR  = __DIR__ . DS;
const FILE = __FILE__;

if ( ! file_exists( DIR . 'vendor/autoload.php' ) ) {
	return;
}

require_once DIR . 'vendor/autoload.php';

array_map(
	fn( $file ) => require_once $file,
	glob( DIR . 'includes/*.php' )
);

array_map(
	fn( $file ) => require_once $file,
	glob( DIR . 'includes/blocks/*.php' )
);

array_map(
	fn( $file ) => require_once $file,
	glob( DIR . 'includes/extensions/*.php' )
);

foreach ( glob( DIR . 'build/blocks/**/*.php' ) as $build ) {
	$src = str_replace( '/build/', '/src/', $build );

	if ( file_exists( $src ) ) {
		require_once $src;
	} else {
		require_once $build;
	}
}

if ( ! function_exists( 'wptt_get_webfont_url' ) ) {
	require_once DIR . 'vendor/wptt/webfont-loader/wptt-webfont-loader.php';
}
