<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function add_action;
use function register_setting;

add_action( 'admin_init', NS . 'register_settings' );
add_action( 'rest_api_init', NS . 'register_settings' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_settings() {
	register_setting(
		'options',
		SLUG,
		[
			'description'  => __( 'Blockify Settings.', 'blockify' ),
			'type'         => 'object',
			'show_in_rest' => [
				'schema' => [
					'type'       => 'object',
					'properties' => [
						'apiKey'          => [
							'type' => 'string',
						],
						'apiKeyStatus'    => [
							'type' => 'string',
						],
						'autoDarkMode'    => [
							'type' => 'boolean',
						],
						'additionalCss'   => [
							'type' => 'string',
						],
						'googleAnalytics' => [
							'type' => 'string',
						],
						'siteIconUrl'     => [
							'type' => 'string',
						],
						'googleFonts'     => [
							'type' => 'array',
						],
						'iconSets'        => [
							'type' => 'array',
						],
					],
				],
			],
		]
	);
}
