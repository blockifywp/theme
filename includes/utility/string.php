<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function str_contains;
use function str_replace;
use function strlen;
use function strpos;
use function substr;

/**
 * Returns part of string between two strings.
 *
 * @since 0.4.0
 *
 * @param string $start  The start string.
 * @param string $end    The end string.
 * @param string $string The string to search.
 * @param bool   $omit   Whether to omit the start and end strings.
 *
 * @return string
 */
function str_between( string $start, string $end, string $string, bool $omit = false ): string {
	$string  = ' ' . $string;
	$initial = strpos( $string, $start );

	if ( $initial === 0 ) {
		return '';
	}

	$initial = $initial + strlen( $start );

	if ( ! str_contains( $string, (string) $initial ) ) {
		return $string;
	}

	$len    = strpos( $string, $end, $initial ) - $initial;
	$string = $start . substr( $string, $initial, $len ) . $end;

	if ( $omit ) {
		$string = str_replace( [ $start, $end ], '', $string );
	}

	return $string;
}

/**
 * Replaces double quotes with single and single quotes with double.
 *
 * @since 0.4.0
 *
 * @param string $string String to modify.
 *
 * @return string
 */
function reverse_quotes( string $string ): string {
	return str_replace( [ "'", '"' ], [ '"', "'" ], $string );
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $haystack
 * @param array  $needles
 *
 * @return bool
 */
function str_contains_any( string $haystack, array $needles ): bool {
	foreach ( $needles as $needle ) {
		if ( str_contains( $haystack, $needle ) ) {
			return true;
		}
	}

	return false;
}
