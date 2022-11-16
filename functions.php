<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const DIRECTORY_SEPARATOR;
use const PHP_VERSION;
use function add_action;
use function array_map;
use function glob;
use function is_readable;
use function version_compare;

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
			...glob( DIR . 'includes/utility/*.php' ),
			...glob( DIR . 'includes/config/*.php' ),
			...glob( DIR . 'includes/*.php' ),
			...glob( DIR . 'includes/blocks/*.php' ),
			...glob( DIR . 'includes/extensions/*.php' ),
			...glob( DIR . 'includes/plugins/*.php' ),
		]
	);
}
