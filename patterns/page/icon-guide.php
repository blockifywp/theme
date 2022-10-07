<?php

$html    = '';
$svg_dir = dirname( dirname( __DIR__ ) ) . '/assets/svg/';

foreach ( [ 'WordPress', 'Social' ] as $dir ) {
	$set   = strtolower( basename( $dir ) );
	$icons = glob( $svg_dir . $set . '/*.svg' );

	$html .= '<!-- wp:heading {"className":"alignwide has-text-align-center"} --><h3 class="alignwide has-text-align-center" style="padding:var(--wp--preset--spacing--xs)">' . $dir . '</h3><!-- /wp:heading -->';

	$html .= '<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
<div class="wp-block-group">';

	foreach ( $icons as $icon ) {
		$basename = basename( $icon, '.svg' );
		$content  = esc_attr( trim( file_get_contents( $icon ) ) );
		$html     .= <<<HTML

	<!-- wp:image {"className":"is-style-icon","textColor":"currentColor","style":{"spacing":{"padding":{"top":"var:preset|spacing|xxs","right":"var:preset|spacing|xxs","bottom":"var:preset|spacing|xxs","left":"var:preset|spacing|xxs"}}} -->
	<figure class="wp-block-image has-text-color is-style-icon" style="padding-top:var(--wp--preset--spacing--xxs);padding-right:var(--wp--preset--spacing--xxs);padding-bottom:var(--wp--preset--spacing--xxs);padding-left:var(--wp--preset--spacing--xxs);--wp--custom--icon--padding:var(--wp--preset--spacing--xxs) var(--wp--preset--spacing--xxs) var(--wp--preset--spacing--xxs) var(--wp--preset--spacing--xxs);--wp--custom--icon--url:url('data:image/svg+xml;utf8,$content');--wp--custom--icon--color:currentColor;"><img src="" alt="$basename"/></figure>
	<!-- /wp:image -->

	HTML;
	}

	$html .= '</div><!-- /wp:group -->';
}

return $html;
