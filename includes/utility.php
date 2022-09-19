<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const PHP_INT_MAX;
use function add_action;
use function apply_filters;
use function defined;
use function explode;
use function in_array;
use function implode;
use function libxml_clear_errors;
use function libxml_use_internal_errors;
use function mb_convert_encoding;
use function str_replace;
use function trim;
use DOMDocument;
use DOMElement;

const CAMEL_CASE    = 'camel';
const PASCAL_CASE   = 'pascal';
const SNAKE_CASE    = 'snake';
const ADA_CASE      = 'ada';
const MACRO_CASE    = 'macro';
const KEBAB_CASE    = 'kebab';
const TRAIN_CASE    = 'train';
const COBOL_CASE    = 'cobol';
const LOWER_CASE    = 'lower';
const UPPER_CASE    = 'upper';
const TITLE_CASE    = 'title';
const SENTENCE_CASE = 'sentence';
const DOT_CASE      = 'dot';

/**
 * Convert string case.
 *
 * camel    myNameIsBond
 * pascal   MyNameIsBond
 * snake    my_name_is_bond
 * ada      My_Name_Is_Bond
 * macro    MY_NAME_IS_BOND
 * kebab    my-name-is-bond
 * train    My-Name-Is-Bond
 * cobol    MY-NAME-IS-BOND
 * lower    my name is bond
 * upper    MY NAME IS BOND
 * title    My Name Is Bond
 * sentence My name is bond
 * dot      my.name.is.bond
 *
 * @since 0.0.2
 *
 * @param string $string The string to convert.
 * @param string $case   Defaults to title.
 *
 * @return string
 */
function convert_case( string $string, string $case = TITLE_CASE ): string {
	$delimiters = 'sentence' === $case ? [ ' ', '-', '_' ] : [ ' ', '-', '_', '.' ];
	$lower      = trim( str_replace( $delimiters, $delimiters[0], strtolower( $string ) ), $delimiters[0] );
	$upper      = trim( ucwords( $lower ), $delimiters[0] );
	$pieces     = explode( $delimiters[0], $lower );

	$cases = [
		CAMEL_CASE    => lcfirst( str_replace( ' ', '', $upper ) ),
		PASCAL_CASE   => str_replace( ' ', '', $upper ),
		SNAKE_CASE    => strtolower( implode( '_', $pieces ) ),
		ADA_CASE      => str_replace( ' ', '_', $upper ),
		MACRO_CASE    => strtoupper( implode( '_', $pieces ) ),
		KEBAB_CASE    => strtolower( implode( '-', $pieces ) ),
		TRAIN_CASE    => lcfirst( str_replace( ' ', '-', $upper ) ),
		COBOL_CASE    => strtoupper( implode( '-', $pieces ) ),
		LOWER_CASE    => strtolower( $string ),
		UPPER_CASE    => strtoupper( $string ),
		TITLE_CASE    => $upper,
		SENTENCE_CASE => ucfirst( $lower ),
		DOT_CASE      => strtolower( implode( '.', $pieces ) ),
	];

	$string = $cases[ $case ] ?? $string;
	$string = in_array( $string, [ 'Wordpress' ] ) ? 'WordPress' : $string;

	return apply_filters( 'blockify_convert_case', $string );
}

/**
 * Returns a formatted DOMDocument object from a given string.
 *
 * @since 0.0.2
 *
 * @param string $html
 *
 * @return \DOMDocument
 */
function dom( string $html ): DOMDocument {
	$dom = new DOMDocument();

	if ( ! $html ) {
		return $dom;
	}

	$libxml_previous_state   = libxml_use_internal_errors( true );
	$dom->preserveWhiteSpace = true;

	if ( defined( 'LIBXML_HTML_NOIMPLIED' ) && defined( 'LIBXML_HTML_NODEFDTD' ) ) {
		$options = LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD;
	} else if ( defined( 'LIBXML_HTML_NOIMPLIED' ) ) {
		$options = LIBXML_HTML_NOIMPLIED;
	} else if ( defined( 'LIBXML_HTML_NODEFDTD' ) ) {
		$options = LIBXML_HTML_NODEFDTD;
	} else {
		$options = 0;
	}

	$dom->loadHTML( mb_convert_encoding( $html, 'HTML-ENTITIES', 'UTF-8' ), $options );

	$dom->formatOutput = true;

	libxml_clear_errors();
	libxml_use_internal_errors( $libxml_previous_state );

	return $dom;
}

/**
 * Returns an HTML element with a replaced tag.
 *
 * @since 0.0.20
 *
 * @param DOMElement $node
 * @param string     $name
 *
 * @return DOMElement
 */
function change_tag_name( DOMElement $node, string $name ): DOMElement {
	$child_nodes = [];

	foreach ( $node->childNodes as $child ) {
		$child_nodes[] = $child;
	}

	$new_node = $node->ownerDocument->createElement( $name );

	foreach ( $child_nodes as $child ) {
		$child2 = $node->ownerDocument->importNode( $child, true );
		$new_node->appendChild( $child2 );
	}

	foreach ( $node->attributes as $attr_node ) {
		$attr_name  = $attr_node->nodeName;
		$attr_value = $attr_node->nodeValue;

		$new_node->setAttribute( $attr_name, $attr_value );
	}

	$node->parentNode->replaceChild( $new_node, $node );

	return $new_node;
}

/**
 * Converts array of CSS rules to string.
 *
 * @since 0.0.22
 *
 * @param array $styles
 *
 * @return string
 */
function css_array_to_string( array $styles ): string {
	$css = '';

	foreach ( $styles as $property => $value ) {
		if ( ! $value ) {
			continue;
		}

		$css .= "$property:$value;";
	}

	return $css;
}

/**
 * Converts string of CSS rules to an array.
 *
 * @since 0.0.2
 *
 * @param string $css
 *
 * @return array
 */
function css_string_to_array( string $css ): array {
	$array    = [];
	$elements = explode( ';', $css );

	foreach ( $elements as $element ) {
		$parts = explode( ':', $element, 2 );

		if ( isset( $parts[1] ) ) {
			$property = $parts[0];
			$value    = $parts[1];

			$array[ $property ] = $value;
		}
	}

	return $array;
}
