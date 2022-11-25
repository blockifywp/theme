<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function str_contains;

/**
 * Check if any of the given needles are in the haystack.
 *
 * @since 0.9.10
 *
 * @param string   $haystack The string to search.
 * @param string[] $needles  The strings to search for.
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
