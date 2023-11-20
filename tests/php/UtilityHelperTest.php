<?php

declare( strict_types=1 );

namespace Blockify\Theme\Tests;

use Blockify\Theme;
use Brain\Monkey;
use Brain\Monkey\Functions;
use const Blockify\Theme\DS;

beforeEach( function () {
	Monkey\setUp();

	Functions\when( 'get_template' )->justReturn( 'blockify' );
	Functions\when( 'get_template_directory' )->justReturn( get_dir_mock() );
	Functions\when( 'get_template_directory_uri' )->justReturn( 'https://blockify.local/wp-content/themes/blockify' );
	Functions\when( 'plugin_dir_url' )->justReturn( 'https://blockify.local/wp-content/plugins/blockify' );
} );

afterEach( function () {
	Monkey\tearDown();
} );

function get_dir_mock( string $type = 'default' ): string {
	$dirs = [
		'default'   => dirname( __DIR__, 2 ) . DS,
		'plugin'    => dirname( __DIR__, 3 ) . '/plugins/',
		'framework' => dirname( __DIR__, 2 ) . '/vendor/blockify/theme/',
	];

	return $dirs[ $type ];
}

function get_uri_mock( string $type = 'default' ): string {
	$dirs = [
		'default'   => 'https://blockify.local/wp-content/themes/blockify/',
		'plugin'    => 'https://blockify.local/wp-content/plugins/blockify/',
		'framework' => 'https://blockify.local/wp-content/themes/blockify/',
	];

	return $dirs[ $type ];
}

test( 'is_framework function checks if Blockify is installed as framework', function () {
	expect( Theme\is_framework() )->toBeFalse();
} );

test( 'is_plugin function checks if Blockify is installed as plugin', function () {
	expect( Theme\is_plugin() )->toBeFalse();
} );

test( 'get_dir function returns path to theme directory when installed as framework', function () {
	expect( Theme\get_dir() )->toBe( get_dir_mock() );
} );

test( 'get_dir function returns path to theme directory when installed as plugin', function () {
	// TODO: Test with plugin active.
	expect( Theme\get_dir() )->toBe( get_dir_mock() );
} );

test( 'get_uri function returns URI to theme directory when installed as framework', function () {
	expect( Theme\get_uri() )->toBe( get_uri_mock( 'framework' ) );
} );

test( 'get_uri function returns URI to theme directory when installed as plugin', function () {

	// TODO: Test with plugin installed.
	expect( Theme\get_uri() )->toBe( get_uri_mock( 'default' ) );
} );
