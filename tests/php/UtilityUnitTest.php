<?php

declare( strict_types=1 );

namespace Blockify\Tests;

use function Blockify\Theme\change_tag_name;
use function Blockify\Theme\css_array_to_string;
use function Blockify\Theme\css_string_to_array;
use function Blockify\Theme\dom;

require_once __DIR__ . '../../../includes/utility.php';

test( 'dom utility works', function () {

	$dom = dom( '<div>test</div>' );

	expect( trim( $dom->saveHTML() ) )
		->toBeString()
		->toEqual( '<div>test</div>' );


	$empty = dom( '' );

	expect( trim( $empty->saveHTML() ) )
		->toBeString()
		->toEqual( '' );

} );

test( 'change tag name works', function () {

	$dom = dom( '<div>test</div>' );

	/** @var \DOMElement $first */
	$first = $dom->firstChild;

	\expect( $first->tagName )->toEqual( 'div' );

	$first = change_tag_name( $first, 'span' );

	\expect( $first->tagName )->toEqual( 'span' );

	$saved = trim( $dom->saveHTML() );

	\expect( $saved )->toEqual( '<span>test</span>' );

} );

test( 'css type flipping works', function () {
	$css_array = [
		'color'     => 'red',
		'font-size' => '12px',
	];

	$css_string = 'color:red;font-size:12px;';

	\expect( css_array_to_string( $css_array ) )->toEqual( $css_string );
	\expect( css_string_to_array( $css_string ) )->toEqual( $css_array );
} );

