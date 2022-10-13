<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_merge;
use function array_merge_recursive;
use function is_admin;
use function str_replace;

add_filter( 'theme_json_theme', NS . 'register_local_font_choices' );
/**
 * Filters theme.json font families.
 *
 * @todo  Move layout settings to separate file.
 *
 * @since 0.4.2
 *
 * @param mixed $theme_json WP_Theme_JSON_Data | WP_Theme_JSON_Data_Gutenberg.
 *
 * @return mixed
 */
function register_local_font_choices( $theme_json ) {
	$data         = $theme_json->get_data();
	$content_size = $data['settings']['layout']['contentSize'] ?? 'min(90vw, 800px)';
	$wide_size    = $data['settings']['layout']['wideSize'] ?? 'min(90vw, 1200px)';

	// Gutenberg bug.
	unset( $data['settings']['layout']['contentSize'] );
	unset( $data['settings']['layout']['wideSize'] );

	if ( is_admin() ) {
		$content_size = str_replace( 'vw', '%', $content_size );
		$wide_size    = str_replace( 'vw', '%', $wide_size );
	}

	$theme_json->update_with(
		array_merge_recursive(
			$data,
			[
				'settings' => [
					'typography' => [
						'fontFamilies' => array_merge(
							get_system_fonts(),
							is_admin() ? get_all_fonts() : get_selected_fonts( $data['styles'] ?? [] )
						),
					],
					'layout'     => [
						'contentSize' => $content_size,
						'wideSize'    => $wide_size,
					],
				],
			]
		)
	);

	return $theme_json;
}
