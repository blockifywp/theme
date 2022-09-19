<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const DIRECTORY_SEPARATOR;
use const PHP_VERSION;

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

array_map(
	static fn( $file ) => require_once $file,
	[
		...glob( DIR . 'includes/*.php' ),
		...glob( DIR . 'includes/blocks/*.php' ),
		...glob( DIR . 'includes/extensions/*.php' ),
	]
);
