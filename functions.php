<?php

declare( strict_types=1 );

namespace Blockify;

use const DIRECTORY_SEPARATOR;
use const PHP_VERSION;
use function array_map;
use function glob;
use function version_compare;

const SLUG = 'blockify';
const FILE = __FILE__;
const NS   = __NAMESPACE__ . '\\';
const DS   = DIRECTORY_SEPARATOR;
const DIR  = __DIR__ . DS;

if ( ! version_compare( '7.4.0', PHP_VERSION, '<=' ) ) {
	return;
}

require_once DIR . 'vendor/autoload.php';
require_once DIR . 'includes/utility.php';
require_once DIR . 'includes/settings.php';
require_once DIR . 'includes/patterns.php';
require_once DIR . 'includes/assets.php';

array_map(
	fn( $file ) => require_once $file,
	glob( DIR . 'includes/blocks/*.php' )
);
