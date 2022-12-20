<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const DIRECTORY_SEPARATOR;
use function add_action;
use function function_exists;
use function is_readable;

const SLUG = 'blockify';
const NAME = 'Blockify';
const NS   = __NAMESPACE__ . '\\';
const DS   = DIRECTORY_SEPARATOR;
const DIR  = __DIR__ . DS;
const FILE = __FILE__;

add_action( 'after_setup_theme', NS . 'setup', 8 );
/**
 * Load theme.
 *
 * @since 1.0.0
 *
 * @return void
 */
function setup(): void {
	$files = [
		DIR . '/vendor/autoload.php',

		// Utility.
		DIR . 'includes/utility/color.php',
		DIR . 'includes/utility/css.php',
		DIR . 'includes/utility/dom.php',
		DIR . 'includes/utility/helper.php',
		DIR . 'includes/utility/icon.php',
		DIR . 'includes/utility/string.php',

		// Config.
		DIR . 'includes/config/block-extras.php',
		DIR . 'includes/config/block-styles.php',
		DIR . 'includes/config/block-supports.php',

		// Includes.
		DIR . 'includes/patterns.php',
		DIR . 'includes/scripts.php',
		DIR . 'includes/styles.php',

		// Blocks.
		DIR . 'includes/blocks/archive-title.php',
		DIR . 'includes/blocks/button.php',
		DIR . 'includes/blocks/columns.php',
		DIR . 'includes/blocks/group.php',
		DIR . 'includes/blocks/heading.php',
		DIR . 'includes/blocks/image.php',
		DIR . 'includes/blocks/list.php',
		DIR . 'includes/blocks/navigation-submenu.php',
		DIR . 'includes/blocks/navigation.php',
		DIR . 'includes/blocks/paragraph.php',
		DIR . 'includes/blocks/post-author.php',
		DIR . 'includes/blocks/post-content.php',
		DIR . 'includes/blocks/post-date.php',
		DIR . 'includes/blocks/post-excerpt.php',
		DIR . 'includes/blocks/post-featured-image.php',
		DIR . 'includes/blocks/post-terms.php',
		DIR . 'includes/blocks/post-title.php',
		DIR . 'includes/blocks/query-pagination.php',
		DIR . 'includes/blocks/query.php',
		DIR . 'includes/blocks/search.php',
		DIR . 'includes/blocks/site-logo.php',
		DIR . 'includes/blocks/social-link.php',
		DIR . 'includes/blocks/social-links.php',
		DIR . 'includes/blocks/tag-cloud.php',
		DIR . 'includes/blocks/template-part.php',
		DIR . 'includes/blocks/video.php',

		// Extensions.
		DIR . 'includes/extensions/accordion.php',
		DIR . 'includes/extensions/animation.php',
		DIR . 'includes/extensions/conic-gradient.php',
		DIR . 'includes/extensions/counter.php',
		DIR . 'includes/extensions/dark-mode.php',
		DIR . 'includes/extensions/icon.php',
		DIR . 'includes/extensions/inline-color.php',
		DIR . 'includes/extensions/onclick.php',
		DIR . 'includes/extensions/placeholder.php',
		DIR . 'includes/extensions/position.php',
		DIR . 'includes/extensions/svg.php',
	];

	foreach ( $files as $file ) {
		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}

	if ( function_exists( 'tgmpa' ) ) {
		\tgmpa(
			[
				[
					'name'     => __( 'Blockify', 'blockify' ),
					'slug'     => 'blockify',
					'required' => false,
				],
			]
		);
	}
}
