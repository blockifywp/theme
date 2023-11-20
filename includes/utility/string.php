<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function preg_replace;
use function str_contains;
use function str_replace;
use function strlen;
use function strpos;
use function trim;
use function ucwords;
use const PHP_EOL;

/**
 * Check if any of the given needles are in the haystack.
 *
 * @since 0.9.10
 *
 * @param string $haystack   The string to search.
 * @param mixed  ...$needles The strings to search for.
 *
 * @return bool
 */
function str_contains_any( string $haystack, ...$needles ): bool {
	foreach ( $needles as $needle ) {
		if ( str_contains( $haystack, $needle ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Replaces multiple whitespace with single.
 *
 * @since 0.9.10
 *
 * @param string $string The string to search.
 *
 * @return string
 */
function reduce_whitespace( string $string ): string {
	return preg_replace( '/\s+/', ' ', $string );
}

/**
 * Removes line breaks from a string.
 *
 * @since 1.0.1
 *
 * @param string $string The string to search.
 *
 * @return string
 */
function remove_line_breaks( string $string ): string {
	return str_replace(
		[
			"\r",
			"\n",
			PHP_EOL,
		],
		'',
		trim( $string )
	);
}

/**
 * Returns parts of a string between two strings using regular expressions.
 *
 * @since 0.1.2
 *
 * @param string $start  Start string.
 * @param string $end    End string.
 * @param string $string String content.
 * @param bool   $omit   Omit start and end strings.
 * @param bool   $all    Return all occurrences.
 *
 * @return string|array
 */
function str_between( string $start, string $end, string $string, bool $omit = false, bool $all = false ) {
	$pattern = '/' . preg_quote( $start, '/' ) . '(.*?)' . preg_quote( $end, '/' ) . '/s';
	preg_match_all( $pattern, $string, $matches );

	$selected_matches = $omit ? $matches[1] : $matches[0];
	$first_match      = $selected_matches[0] ?? '';

	return $all ? $selected_matches : $first_match;
}

/**
 * Removes non-alphanumeric characters from string.
 *
 * @since 1.0.0
 *
 * @param string $string String to sanitize.
 *
 * @return string
 */
function remove_non_alphanumeric( string $string ): string {
	return preg_replace( '/[^A-Za-z0-9\-]/', '', $string );
}

/**
 * Replace first occurrence of a string.
 *
 * @since 1.2.9
 *
 * @param string $needle      The string to search for.
 * @param string $replacement The string to replace with.
 * @param string $haystack    The string to search.
 *
 * @return string
 */
function str_replace_first( string $needle, string $replacement, string $haystack ): string {
	if ( ! $needle || ! $haystack ) {
		return $haystack;
	}

	$position = strpos( $haystack, $needle );

	if ( $position !== false ) {
		$haystack = substr_replace( $haystack, $replacement, $position, strlen( $needle ) );
	}

	return $haystack;
}

/**
 * Converts a string to title case.
 *
 * @param string   $string The string to convert.
 * @param string[] $search Characters to replace (optional).
 *
 * @return string
 */
function to_title_case( string $string, array $search = [ '-', '_' ] ): string {
	return trim( ucwords( str_replace( $search, ' ', $string ) ) );
}
