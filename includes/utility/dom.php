<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use WP_Error;
use function bin2hex;
use function current;
use function iconv;
use function is_a;
use function is_null;
use function libxml_clear_errors;
use function libxml_use_internal_errors;
use function ltrim;
use function preg_replace_callback;
use function sprintf;
use function strtoupper;

/**
 * Returns a formatted DOMDocument object from a given string.
 *
 * @since 0.0.2
 *
 * @param string $html HTML string to convert to DOM.
 *
 * @return DOMDocument
 */
function dom( string $html ): DOMDocument {
	$dom = new DOMDocument();

	if ( ! $html ) {
		return $dom;
	}

	$libxml_previous_state   = libxml_use_internal_errors( true );
	$dom->preserveWhiteSpace = false;

	if ( defined( 'LIBXML_HTML_NOIMPLIED' ) && defined( 'LIBXML_HTML_NODEFDTD' ) ) {
		$options = LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD;
	} else {
		if ( defined( 'LIBXML_HTML_NOIMPLIED' ) ) {
			$options = LIBXML_HTML_NOIMPLIED;
		} else {
			if ( defined( 'LIBXML_HTML_NODEFDTD' ) ) {
				$options = LIBXML_HTML_NODEFDTD;
			} else {
				$options = 0;
			}
		}
	}

	// @see https://stackoverflow.com/questions/13280200/convert-unicode-to-html-entities-hex.
	$html = preg_replace_callback(
		'/[\x{80}-\x{10FFFF}]/u',
		static fn( array $matches ): string => sprintf(
			'&#x%s;',
			ltrim(
				strtoupper(
					bin2hex(
						iconv(
							'UTF-8',
							'UCS-4',
							current( $matches )
						)
					)
				),
				'0'
			)
		),
		$html
	);

	$dom->loadHTML( $html, $options );
	$dom->formatOutput = true;

	libxml_clear_errors();
	libxml_use_internal_errors( $libxml_previous_state );

	return $dom;
}

/**
 * Returns a formatted DOMElement object from a DOMDocument object.
 *
 * @since 0.4.0
 *
 * @param string $tag            HTML tag.
 * @param mixed  $dom_or_element DOMDocument or DOMElement.
 * @param int    $index          Index of element to return.
 *
 * @return ?DOMElement
 */
function get_dom_element( string $tag, $dom_or_element, int $index = 0 ): ?DOMElement {
	if ( ! is_a( $dom_or_element, DOMDocument::class ) && ! is_a( $dom_or_element, DOMElement::class ) ) {
		return null;
	}

	$element = $dom_or_element->getElementsByTagName( $tag )->item( $index );

	if ( ! $element ) {
		return null;
	}

	return dom_element( $element );
}

/**
 * Casts a DOMNode to a DOMElement.
 *
 * @since 0.4.0
 *
 * @param mixed $node DOMNode to cast to DOMElement.
 *
 * @return ?DOMElement
 */
function dom_element( $node ): ?DOMElement {
	if ( $node->nodeType === XML_ELEMENT_NODE ) {
		/* @var DOMElement $node DOM Element node */
		return $node;
	}

	return null;
}

/**
 * Returns an HTML element with a replaced tag.
 *
 * @since 0.0.20
 *
 * @param string     $name    Tag name, e.g: 'div'.
 * @param DOMElement $element DOM Element to change.
 *
 * @return ?DOMElement
 */
function change_tag_name( string $name, DOMElement $element ): ?DOMElement {
	if ( ! $element->ownerDocument ) {
		return null;
	}

	$child_nodes = [];

	foreach ( $element->childNodes as $child ) {
		$child_nodes[] = $child;
	}

	$new_element = $element->ownerDocument->createElement( $name );

	foreach ( $child_nodes as $child ) {
		$child2 = $element->ownerDocument->importNode( $child, true );
		$new_element->appendChild( $child2 );
	}

	foreach ( $element->attributes as $attr_node ) {
		$attr_name  = $attr_node->nodeName;
		$attr_value = $attr_node->nodeValue;

		$new_element->setAttribute( $attr_name, $attr_value );
	}

	if ( $element->parentNode ) {
		$element->parentNode->replaceChild( $new_element, $element );
	}

	return $new_element;
}

/**
 * Returns an array of DOM elements by class name.
 *
 * @since 0.9.26
 *
 * @param DOMDocument $dom        DOM document or element.
 * @param string      $class_name Element class name.
 * @param string      $tag        Element tag name (optional).
 *
 * @return array
 */
function get_elements_by_class_name( string $class_name, DOMDocument $dom, string $tag = '*' ): array {
	$xpath    = new DOMXPath( $dom );
	$query    = sprintf( "//%s[contains(concat(' ', normalize-space(@class), ' '), ' %s ')]", $tag, $class_name );
	$nodes    = $xpath->query( $query );
	$elements = [];

	if ( $nodes !== false ) {
		foreach ( $nodes as $node ) {
			if ( $node instanceof DOMElement ) {
				$elements[] = $node;
			}
		}
	}

	return $elements;
}

/**
 * Creates a DOMElement to avoid unhandled exceptions.
 *
 * @since 1.3.0
 *
 * @param string      $tag HTML tag.
 * @param DOMDocument $dom DOM object.
 *
 * @return ?DOMElement
 */
function create_element( string $tag, DOMDocument $dom ): ?DOMElement {
	$element = null;

	try {
		$element = $dom->createElement( $tag );
	} catch ( Exception $e ) {
		new WP_Error( 'invalid_dom_tag', $e->getMessage() );
	}

	if ( is_null( $element ) ) {
		return null;
	}

	return dom_element( $element );
}
