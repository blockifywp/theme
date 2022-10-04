<?php

declare( strict_types=1 );

namespace Blockify\Theme;

function get_image_placeholder( string $html, array $attributes = [] ): string {
	$html        = ! $html ? '<figure><img src="" alt=""/></figure>' : $html;
	$dom         = dom( $html );
	$figure      = get_dom_element( 'figure', $dom );
	$styles      = css_array_to_string( add_block_support_color( [], $attributes ) );
	$svg         = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" class="wp-block-image__placeholder" style="' . $styles . '"><path vector-effect="non-scaling-stroke" d="M60 60 0 0"></path></svg>';
	$svg_dom     = dom( $svg );
	$svg_element = get_dom_element( 'svg', $svg_dom );
	$result      = $dom->importNode( $svg_element, true );
	$img         = get_dom_element( 'img', $figure );

	if ( $img ) {
		$figure->removeChild( $img );
	}

	if ( $figure && $result ) {
		$figure->appendChild( $result );
	}

	if ( $figure ) {

		$figure->setAttribute( 'class', $figure->getAttribute( 'class' ) . ' is-placeholder' );
	}

	return $dom->saveHTML();
}
