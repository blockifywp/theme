<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block_Template;
use function add_filter;
use function array_unshift;
use function class_exists;
use function file_exists;
use function get_post_type;
use function get_queried_object;
use function get_stylesheet_directory;
use function get_template_directory;
use function is_post_type_archive;
use function is_search;
use function str_contains;

add_filter( 'get_block_templates', NS . 'remove_templates', 10, 3 );
/**
 * Remove unused templates from editor.
 *
 * @since 1.2.9
 *
 * @param ?WP_Block_Template[] $query_result  The query result.
 * @param array                $query         The query.
 * @param string               $template_type The template type.
 *
 * @return array
 */
function remove_templates( ?array $query_result, array $query, string $template_type ): array {
	if ( 'wp_template' !== $template_type ) {
		return $query_result;
	}

	$woocommerce = class_exists( 'WooCommerce' );
	$edd         = class_exists( 'Easy_Digital_Downloads' );

	foreach ( $query_result as $index => $wp_block_template ) {
		$slug = $wp_block_template->slug;

		if ( 'blockify' !== $wp_block_template->theme ) {
			continue;
		}

		if ( ! $woocommerce && str_contains( $slug, 'product' ) ) {
			unset( $query_result[ $index ] );
			continue;
		}

		if ( ! $edd && str_contains( $slug, '-download' ) ) {
			unset( $query_result[ $index ] );
		}
	}

	return $query_result;
}

add_filter( 'search_template_hierarchy', NS . 'update_search_template_hierarchy' );
/**
 * Updates search template hierarchy.
 *
 * @since 1.0.0
 *
 * @param array $templates Template files to search for, in order.
 *
 * @return array
 */
function update_search_template_hierarchy( array $templates ): array {
	if ( is_search() && is_post_type_archive() ) {
		$post_type = get_queried_object()->name ?? get_post_type();
		$slug      = "search-$post_type";
		$child     = get_stylesheet_directory() . "/templates/$slug.html";
		$parent    = get_template_directory() . "/templates/$slug.html";

		if ( file_exists( $child ) || file_exists( $parent ) ) {
			array_unshift( $templates, $slug );
		}
	}

	return $templates;
}
