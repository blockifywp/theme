<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const DIRECTORY_SEPARATOR;
use const PHP_VERSION;
use function array_map;
use function function_exists;
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

require_once DIR . 'vendor/autoload.php';
require_once DIR . 'includes/utility.php';
require_once DIR . 'includes/blocks.php';
require_once DIR . 'includes/patterns.php';
require_once DIR . 'includes/settings.php';
require_once DIR . 'includes/assets.php';

array_map(
	fn( $file ) => require_once $file,
	glob( DIR . 'includes/blocks/*.php' )
);

array_map(
	fn( $file ) => require_once $file,
	glob( DIR . 'includes/extensions/*.php' )
);

if ( ! function_exists( 'wptt_get_webfont_url' ) ) {
	require_once DIR . 'vendor/wptt/webfont-loader/wptt-webfont-loader.php';
}

