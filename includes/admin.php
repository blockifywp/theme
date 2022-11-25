<?php

declare( strict_types=1 );

namespace Blockify\Theme;

add_filter( 'admin_menu', NS . 'admin_menu_links' );
/**
 * Adds menu link for block pattern editor.
 *
 * @since 0.0.1
 *
 * @return void
 */
function admin_menu_links(): void {
	$stylesheet = get_stylesheet();

	add_theme_page(
		__( 'Header', 'blockify' ),
		__( 'Header', 'blockify' ),
		'edit_theme_options',
		"site-editor.php?postType=wp_template_part&postId=$stylesheet%2F%2Fheader",
		null,
		99,
	);

	add_theme_page(
		__( 'Footer', 'blockify' ),
		__( 'Footer', 'blockify' ),
		'edit_theme_options',

		"site-editor.php?postType=wp_template_part&postId=$stylesheet%2F%2Ffooter",
		null,
		99,
	);

	add_theme_page(
		__( 'Templates', 'blockify' ),
		__( 'Templates', 'blockify' ),
		'edit_theme_options',
		'site-editor.php?post_type=wp_template',
		null,
		99,
	);
}

