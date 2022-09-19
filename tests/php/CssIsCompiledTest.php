<?php

declare( strict_types=1 );

namespace Blockify\Theme\Tests;

test( 'css files are not empty', function () {
	$editor_css = \get_template_directory() . '/assets/css/editor.css';

	\expect( \file_exists( $editor_css ) )->toBeTrue();

	$editor_css_contents = \file_get_contents( $editor_css );

	\expect( $editor_css_contents )->toContain( '.editor-styles-wrapper' );
} );
