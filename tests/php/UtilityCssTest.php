<?php

declare( strict_types=1 );

namespace Blockify\Theme\Tests;

use function Blockify\Theme\css_array_to_string;
use function Blockify\Theme\css_string_to_array;

test( 'css files are not empty', function () {
	$editor_css = dirname(__DIR__, 2) . '/assets/css/editor.css';

	\expect( \file_exists( $editor_css ) )->toBeTrue();

	$editor_css_contents = \file_get_contents( $editor_css );

	\expect( $editor_css_contents )->toContain( '.editor-styles-wrapper' );
} );

test( 'css type flipping works', function () {
	$css_array = [
		'color'     => 'red',
		'font-size' => '12px',
	];

	$css_string = 'color:red;font-size:12px';

	\expect( css_array_to_string( $css_array ) )->toEqual( $css_string );
	\expect( css_string_to_array( $css_string ) )->toEqual( $css_array );
} );
