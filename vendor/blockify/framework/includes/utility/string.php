<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function preg_replace;
use function str_contains;
use function str_replace;
use function strlen;
use function strpos;
use function substr;

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
 * Removes non-alphanumeric characters from string.
 *
 * @since 1.0.0
 *
 * @param string $string
 *
 * @return string
 */
function remove_non_alphanumeric( string $string ): string {
	return preg_replace( '/[^A-Za-z0-9\-]/', '', $string );
}
