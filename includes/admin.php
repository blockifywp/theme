<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const WP_PLUGIN_DIR;
use function add_action;
use function add_filter;
use function admin_url;
use function array_merge;
use function defined;
use function esc_attr;
use function file_exists;
use function get_template;
use function is_multisite;
use WPTRT\AdminNotices\Notices;

add_action( 'admin_init', NS . 'add_plugin_notice', 99 );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return void
 */
function add_plugin_notice(): void {
	if ( defined( 'GUTENBERG_VERSION' ) ) {
		return;
	}

	global $pagenow;

	$notice       = new Notices();
	$plugins_page = $pagenow === 'plugins.php';
	$installed    = file_exists( WP_PLUGIN_DIR . '/gutenberg' );
	$multisite    = is_multisite();

	$url = 'plugin-install.php?s=gutenberg&tab=search&type=term';

	if ( $installed ) {
		$url = admin_url( 'plugins.php' );
	}

	if ( $multisite && ! $installed && ! $plugins_page ) {
		$url = admin_url( 'network/plugin-install.php?s=gutenberg&tab=search&type=term' );
	}

	if ( $installed && $plugins_page ) {
		$url = '';
	}

	$message = __( 'Hi there! Thank you for using Blockify. To ensure correct styling, please install and activate the Gutenberg plugin. ', 'blockify' );

	if ( $url ) {
		$message .= '<a href="' . esc_attr( $url ) . '">' . __( 'Install Gutenberg Plugin', 'blockify' ) . '</a>';
	} else {
		$message .= __( 'Click the ', 'blockify' );
		$message .= '<strong>' . __( 'Activate', 'blockify' ) . '</strong>';
		$message .= __( ' link below to get started.', 'blockify' );
	}

	$notice->add(
		'blockify-gutenberg',
		__( 'Please install and activate the Gutenberg plugin', 'blockify' ),
		$message,
	);

	$notice->boot();
}

add_action( 'admin_init', NS . 'add_setup_notice' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return void
 */
function add_setup_notice(): void {
	global $pagenow;

	if ( $pagenow !== 'plugins.php' ) {
		return;
	}

	if ( ! defined( 'GUTENBERG_VERSION' ) ) {
		return;
	}

	$notice = new Notices();

	$notice->add(
		'blockify-setup',
		__( 'Blockify theme successfully installed', 'blockify' ),
		__( 'You are ready to begin customizing your site. Please visit the Site Editor to get started.', 'blockify' ) . ' <a href="' . esc_attr( admin_url( 'site-editor.php' ) ) . '">' . __( 'Go to Site Editor →', 'blockify' ) . '</a>'
	);

	$notice->boot();
}

add_filter( 'wptrt_admin_notices_allowed_html', NS . 'add_button_to_allowed_html' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param array $allowed_html
 *
 * @return array
 */
function add_button_to_allowed_html( array $allowed_html ): array {
	return array_merge( $allowed_html, [ 'button' => [] ] );
}

add_filter( 'admin_menu', NS . 'theme_quick_links', 99 );
/**
 * Adds menu link for block pattern editor.
 *
 * @since 0.0.1
 *
 * @return void
 */
function theme_quick_links(): void {
	$slug = get_template();

	add_theme_page(
		__( 'Header', 'blockify' ),
		__( 'Header', 'blockify' ),
		'edit_theme_options',
		'site-editor.php?postType=wp_template_part&postId=' . $slug . '%2F%2Fheader'
	);

	add_theme_page(
		__( 'Footer', 'blockify' ),
		__( 'Footer', 'blockify' ),
		'edit_theme_options',
		'site-editor.php?postType=wp_template_part&postId=' . $slug . '%2F%2Ffooter'
	);
}
