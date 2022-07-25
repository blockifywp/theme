<?php

declare( strict_types=1 );

namespace Blockify;

use DOMDocument;
use DOMElement;
use function apply_filters;
use function array_key_exists;
use function array_merge_recursive;
use function array_values;
use function basename;
use function defined;
use function dirname;
use function explode;
use function file_exists;
use function filemtime;
use function get_template_directory_uri;
use function get_theme_support;
use function in_array;
use function implode;
use function json_encode;
use function libxml_clear_errors;
use function libxml_use_internal_errors;
use function mb_convert_encoding;
use function php_uname;
use function plugin_dir_url;
use function preg_match;
use function preg_replace;
use function str_replace;
use function trim;
use function wp_enqueue_script;
use function wp_enqueue_style;
use const DIRECTORY_SEPARATOR;

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
 * @param string $string
 * @param string $case
 *
 * @return string
 */
function convert_case( string $string, string $case ): string {
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
 * Returns part of string between two strings.
 *
 * @since 0.0.2
 *
 * @param string $start
 * @param string $end
 * @param string $string
 * @param bool   $omit
 *
 * @return string
 */
function str_between( string $start, string $end, string $string, bool $omit = false ): string {
	$string = ' ' . $string;
	$ini    = strpos( $string, $start );

	if ( $ini == 0 ) {
		return '';
	}

	$ini    += strlen( $start );
	$len    = strpos( $string, $end, $ini ) - $ini;
	$string = $start . substr( $string, $ini, $len ) . $end;

	if ( $omit ) {
		$string = str_replace( [ $start, $end ], '', $string );
	}

	return $string;
}

/**
 * Replaces first occurrence of a string within a string.
 *
 * @since 0.0.2
 *
 * @param string $haystack
 * @param string $needle
 * @param string $replace
 *
 * @return string
 */
function str_replace_first( string $haystack, string $needle, string $replace ): string {
	$pos = strpos( $haystack, $needle );

	if ( $pos !== false ) {
		$haystack = substr_replace( $haystack, $replace, $pos, strlen( $needle ) );
	}

	return $haystack;
}

/**
 * Replaces last occurrence of a string within a string.
 *
 * @since 0.0.2
 *
 * @param string $haystack
 * @param string $needle
 * @param string $replace
 *
 * @return string
 */
function str_replace_last( string $haystack, string $needle, string $replace ): string {
	$pos = strrpos( $haystack, $needle );

	if ( $pos !== false ) {
		$haystack = substr_replace( $haystack, $replace, $pos, strlen( $needle ) );
	}

	return $haystack;
}

/**
 * Returns a formatted DOMDocument object from a given string.
 *
 * @since 0.0.2
 *
 * @param string $html
 *
 * @return string
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
 * Quick and dirty way to mostly minify CSS.
 *
 * @author Gary Jones
 *
 * @since  0.0.2
 *
 * @param string $css CSS to minify
 *
 * @return string
 */
function minify_css( string $css ): string {
	$css = preg_replace( '/\s+/', ' ', $css );
	$css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );
	$css = preg_replace( '~/\*(?![!|*])(.*?)\*/~', '', $css );
	$css = preg_replace( '/;(?=\s*})/', '', $css );
	$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
	$css = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $css );
	$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
	$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
	$css = preg_replace( '/0 0 0 0/', '0', $css );
	$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );

	return trim( $css );
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
function css_rules_to_array( string $css ): array {
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

/**
 * Returns an attribute value from a HTML element string, with fallback.
 *
 * @since 0.0.2
 *
 * @param string $name
 * @param string $html
 * @param string $default
 *
 * @return string
 */
function get_attr( string $name, string $html, string $default = '' ): string {
	preg_match( '/' . $name . '="(.+?)"/', $html, $matches );

	return $matches[1] ?? $default;
}

/**
 * Removes HTML comments from string.
 *
 * @since 0.0.2
 *
 * @param string $content
 *
 * @return string
 */
function remove_html_comments( string $content = '' ): string {
	return preg_replace( '/<!--(.|\s)*?-->/', '', $content );
}

/**
 * Replaces a HTML elements tag.
 *
 * @since 0.0.2
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
 * Returns a random hex code.
 *
 * @since 0.0.2
 *
 * @param bool $hashtag
 *
 * @return string
 */
function random_hex( bool $hashtag = true ): string {
	return ( $hashtag ? '#' : '' ) . str_pad( dechex( mt_rand( 0, 0xFFFFFF ) ), 6, '0', STR_PAD_LEFT );
}

/**
 * Attempts to log WordPress PHP data to console.
 *
 * @since    0.0.2
 *
 * @param mixed $data
 *
 * @return void
 */
function console_log( $data ): void {
	$data   = json_encode( $data );
	$script = "<script class='console-log'>console.log($data);</script>";

	print $script;
}

/**
 * Enqueues a compiled asset from build directory.
 *
 * @since 0.0.2
 *
 * @param string $base File basename relative to build directory. E.g style.css.
 * @param array  $args Default args to merge.
 *
 * @return void
 */
function enqueue_asset( string $base, array $args = [] ): void {
	$explode = explode( '.', $base );
	$name    = str_replace( '/', '-', $explode[0] );
	$type    = str_replace( '.', '', $explode[1] ) ?? '';

	$args = [
		'handle'  => 'blockify-' . $name,
		'src'     => get_asset_url() . 'build/' . $base,
		'deps'    => [ ...( $args['deps'] ?? [] ), ...get_asset_deps( $name ) ],
		'version' => $args['version'] ?? get_asset_version( $name ),
	];

	if ( $type === 'css' ) {
		wp_enqueue_style( ...array_values( $args ) );

		$inline = apply_filters( "blockify_{$name}_inline", '' );

		if ( $inline ) {
			wp_add_inline_style( "blockify-$name", $inline );
		}

	} else if ( $type === 'js' ) {
		wp_enqueue_script( ...array_values( $args ) );
	}
}

/**
 * Returns either a theme or plugin asset URL.
 *
 * @since 1.0.0
 *
 * @return string
 */
function get_asset_url(): string {
	$is_plugin = basename( dirname( DIR ) ) === 'plugins';

	return $is_plugin ? plugin_dir_url( FILE ) : get_template_directory_uri() . DS;
}

/**
 * Returns PHP asset file.
 *
 * @since 0.0.9
 *
 * @param string $name
 *
 * @return array
 */
function get_asset_file( string $name ): array {
	static $files = [];

	if ( ! array_key_exists( $name, $files ) ) {
		$file           = DIR . 'build/' . $name . '.asset.php';
		$files[ $name ] = file_exists( $file ) ? require $file : [
			'dependencies' => [],
			'version'      => (string) filemtime( DIR ),
		];
	}

	return $files[ $name ];
}

/**
 * Returns asset dependencies.
 *
 * @since 0.0.9
 *
 * @param string $name
 *
 * @return array
 */
function get_asset_deps( string $name ): array {
	return get_asset_file( $name )['dependencies'];
}

/**
 * Returns asset version.
 *
 * @since 0.0.9
 *
 * @param string $name
 *
 * @return string
 */
function get_asset_version( string $name ): string {
	return get_asset_file( $name )['version'];
}

/**
 * Parses and registers PHP from file.
 *
 * @since 0.0.8
 *
 * @param string $file
 *
 * @return void
 */
function register_block_pattern_from_file( string $file ): void {
	$headers = get_file_data( $file, [
		'categories'  => 'Categories',
		'title'       => 'Title',
		'slug'        => 'Slug',
		'block_types' => 'Block Types',
	] );

	$categories = explode( ',', $headers['categories'] );

	ob_start();
	include $file;
	$content = ob_get_clean();

	$pattern = [
		'title'      => $headers['title'],
		'content'    => $content,
		'categories' => [ ...$categories ],
	];

	if ( $headers['block_types'] ) {
		$pattern['blockTypes'] = $headers['block_types'];
	}

	foreach ( $categories as $category ) {
		register_block_pattern_category( $category, [
			'label' => ucwords( $category ),
		] );
	}

	register_block_pattern( $headers['slug'], $pattern );
}

/**
 * Detects the current operating system.
 *
 * @since 0.0.9
 *
 * @return string
 */
function get_os(): string {
	$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

	if ( ! $user_agent ) {
		return php_uname( 's' );
	}

	if ( preg_match( '/linux/i', $user_agent ) ) {
		$os = 'linux';
	} elseif ( preg_match( '/macintosh|mac os x|mac_powerpc/i', $user_agent ) ) {
		$os = 'mac';
	} elseif ( preg_match( '/windows|win32|win98|win95|win16/i', $user_agent ) ) {
		$os = 'windows';
	} elseif ( preg_match( '/ubuntu/i', $user_agent ) ) {
		$os = 'ubuntu';
	} elseif ( preg_match( '/iphone/i', $user_agent ) ) {
		$os = 'iphone';
	} elseif ( preg_match( '/ipod/i', $user_agent ) ) {
		$os = 'ipod';
	} elseif ( preg_match( '/ipad/i', $user_agent ) ) {
		$os = 'ipad';
	} elseif ( preg_match( '/android/i', $user_agent ) ) {
		$os = 'android';
	} elseif ( preg_match( '/blackberry/i', $user_agent ) ) {
		$os = 'blackberry';
	} elseif ( preg_match( '/webos/i', $user_agent ) ) {
		$os = 'mobile';
	}

	return $os ?? '';
}

/**
 * Returns the final merged config.
 *
 * @since 0.0.9
 *
 * @return array
 */
function get_config(): array {
	$defaults = require __DIR__ . '/config.php';
	$theme    = get_theme_support( SLUG )[0] ?? [];

	return apply_filters( SLUG, array_merge_recursive( $defaults, $theme ) );
}

/**
 * Returns sub config.
 *
 * @since 0.0.14
 *
 * @param string $sub_config
 * @param null   $default
 *
 * @return array
 */
function get_sub_config( string $sub_config, $default = null ): array {
	return get_config()[ $sub_config ] ?? $default;
}
