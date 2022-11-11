<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function in_array;

add_filter( 'render_block_core/paragraph', NS . 'render_curved_text', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_curved_text( string $content, array $block ): string {

	$class_name = $block['attrs']['className'] ?? '';
	$curved     = in_array( 'is-style-curved-text', explode( ' ', $class_name ), true );

	if ( ! $curved ) {
		return $content;
	}

	$dom = dom( $content );
	$p   = get_dom_element( 'p', $dom );
	$svg = get_dom_element( 'svg', $p );

	if ( $svg ) {
		$p->removeChild( $svg );
	}

	$svg_string  = $block['attrs']['svgString'] ?? '';
	$svg_dom     = dom( $svg_string );
	$svg_element = get_dom_element( 'svg', $svg_dom );

	if ( ! $svg_element ) {
		return $content;
	}

	$svg_element->removeAttribute( 'contenteditale' );

	$imported = $dom->importNode( $svg_element, true );

	$p->appendChild( $imported );

	$content = $dom->saveHTML();

	if ( ! $curved ) {
		$container      = $attrs['containerSize'] ?? 150;
		$half_container = ( $attrs['containerSize'] ?? 150 ) / 2;
		$path           = $attrs['pathSize'] ?? 100;
		$half_path      = ( $attrs['pathSize'] ?? 100 ) / 2;

		$svg = <<<SVG
<svg viewBox="0 0 $container $container" width="$container" height="$container" xml:space="preserve" x="0" y="0">
	<path id="circle" d="M $half_container, $half_container m -$half_path, 0 a $half_path,$half_path 0 0,1 $path,0 a $half_path,$half_path 0 0,1 -$path,0" fill="transparent"/>
	<text fill="currentColor">
		<textPath xlink:href="#circle">
			"text "
		</textPath>
	</text>
</svg>
SVG;

		return $svg;
	}

	return $content;
}
