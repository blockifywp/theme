<?php

declare( strict_types=1 );

namespace Blockify\Theme;

/**
 * Check if any of the given values in needles exist in the haystack array.
 *
 * @since 1.3.0
 *
 * @param array $haystack The array to search in.
 * @param array $needles  The values to search for.
 *
 * @return bool
 */
function array_contains_any( array $haystack, array $needles ): bool {
	foreach ( $needles as $needle ) {
		if ( in_array( $needle, $haystack, true ) ) {
			return true;
		}
	}

	return false;
}
