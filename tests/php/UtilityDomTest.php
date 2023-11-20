<?php

declare( strict_types=1 );

namespace Blockify\Theme\Tests;

use DOMDocument;
use DOMElement;
use function Blockify\Theme\change_tag_name;
use function Blockify\Theme\dom;
use function Blockify\Theme\get_dom_element;
use function Blockify\Theme\get_elements_by_class_name;

it( 'returns a formatted DOMDocument object from a given string', function () {
	$html = '<div>Hello, <strong>World!</strong></div>';
	$dom  = dom( $html );

	expect( $dom )->toBeInstanceOf( DOMDocument::class );
	expect( preg_replace( '/\s+/', '', $dom->saveHTML() ) )->toBe( preg_replace( '/\s+/', '', $html ) );
} );

it( 'returns a formatted DOMElement object from a DOMDocument object', function () {
	$html    = '<div>Hello, <strong>World!</strong></div>';
	$dom     = dom( $html );
	$element = get_dom_element( 'div', $dom );

	expect( $element )->toBeInstanceOf( DOMElement::class );
	expect( $element->tagName )->toBe( 'div' );
	expect( $element->textContent )->toBe( 'Hello, World!' );
} );

it( 'returns null when no DOMElement found', function () {
	$html    = '<div>Hello, <strong>World!</strong></div>';
	$dom     = dom( $html );
	$element = get_dom_element( 'span', $dom );

	expect( $element )->toBeNull();
} );

it( 'returns null when trying to cast non-element node', function () {
	$html    = '<div>Hello, <strong>World!</strong></div>';
	$dom     = dom( $html );
	$node    = $dom->createTextNode( 'Hello, World!' );
	$element = get_dom_element( 'div', $node );

	expect( $element )->toBeNull();
} );

it( 'returns an HTML element with a replaced tag', function () {
	$html       = '<div class="my-class">Hello, World!</div>';
	$dom        = dom( $html );
	$element    = get_dom_element( 'div', $dom );
	$newElement = change_tag_name( 'span', $element );

	expect( $newElement )->toBeInstanceOf( DOMElement::class );
	expect( $newElement->tagName )->toBe( 'span' );
	expect( $newElement->textContent )->toBe( 'Hello, World!' );
	expect( $newElement->getAttribute( 'class' ) )->toBe( 'my-class' );
} );

it( 'returns an array of dom elements by class name', function () {
	$html     = '<div class="my-class">Element 1</div><div class="my-class">Element 2</div><div>Element 3</div>';
	$dom      = dom( $html );
	$elements = get_elements_by_class_name( 'my-class', $dom );

	expect( $elements )->toBeArray();
	expect( count( $elements ) )->toBe( 2 );
	expect( $elements[0] )->toBeInstanceOf( DOMElement::class );
	expect( $elements[0]->firstChild->nodeValue )->toBe( 'Element 1' );
	expect( $elements[1] )->toBeInstanceOf( DOMElement::class );
	expect( $elements[1]->firstChild->nodeValue )->toBe( 'Element 2' );
} );

