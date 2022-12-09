<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function file_exists;
use function get_option;
use function tgmpa;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup', 8 );
/**
 * Setup theme.
 *
 * @since 1.0.0
 *
 * @return void
 */
function setup(): void {
	$active_plugins  = get_option( 'active_plugins' ) ?? [];
	$plugin_basename = 'blockify/blockify.php';

	if ( isset( $active_plugins[ $plugin_basename ] ) ) {
		return;
	}

	if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
		return;
	}

	require_once __DIR__ . '/vendor/autoload.php';

	tgmpa(
		[
			[
				'name'     => 'Blockify',
				'slug'     => 'blockify',
				'required' => false,
			],
		],
		[
			'id'           => 'blockify',
			'is_automatic' => true,
			'has_notices'  => true,
			'parent_slug'  => 'themes.php',
			'menu'         => 'install-required-plugins',
		]
	);
}
