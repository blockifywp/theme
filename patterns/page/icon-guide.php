<?php

$html    = '';
$svg_dir = dirname( dirname( __DIR__ ) ) . '/assets/svg/';

foreach ( [ 'WordPress', 'Social' ] as $dir ) {
	$set   = strtolower( basename( $dir ) );
	$icons = glob( $svg_dir . $set . '/*.svg' );

	$html .= '<!-- wp:heading {"className":"alignwide has-text-align-center"} --><h3 class="alignwide has-text-align-center" style="padding:var(--wp--preset--spacing--xs)">' . $dir . '</h3><!-- /wp:heading -->';

	$html .= '<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"var:preset|spacing|md"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"},"textColor":"black"} --><div class="wp-block-group has-black-color" style="padding-bottom:var(--wp--preset--spacing--md)">';

	foreach ( $icons as $icon ) {
		$basename = basename( $icon, '.svg' );
		$content  = esc_attr( trim( file_get_contents( $icon ) ) );
		$html     .= <<<HTML

		<!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|xs","right":"var:preset|spacing|xs","bottom":"var:preset|spacing|xs","left":"var:preset|spacing|xs"}}}} -->
	<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--xs);padding-right:var(--wp--preset--spacing--xs);padding-bottom:var(--wp--preset--spacing--xs);padding-left:var(--wp--preset--spacing--xs)"><!-- wp:image {"className":"is-style-icon","iconSvgString":"$content","textColor":"neutral-dark"} -->
	<figure class="wp-block-image has-neutral-dark-color has-text-color is-style-icon" style="--wp--custom--icon--url:url('data:image/svg+xml;utf8,$content')"><img src="" alt="$basename"/></figure>
	<!-- /wp:image --></div>
	<!-- /wp:group -->

	HTML;
	}

	$html .= '</div><!-- /wp:group -->';
}

return $html;
