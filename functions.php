<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const DIRECTORY_SEPARATOR;
use function add_action;
use function function_exists;
use function is_readable;

const NS = __NAMESPACE__ . '\\';
const DS = DIRECTORY_SEPARATOR;

// Allow file to be autoloaded without affecting phpcs and phpstan.
if ( function_exists( 'add_action' ) ) {
	add_action( 'after_setup_theme', NS . 'setup', 8 );
}

/**
 * Setup theme.
 *
 * @since 0.0.1
 *
 * @return void
 */
function setup(): void {
	$files = [
		'utility/color',
		'utility/css',
		'utility/dom',
		'utility/helper',
		'utility/icon',
		'utility/string',
		'extensions/animation',
		'extensions/block-extras',
		'extensions/block-styles',
		'extensions/block-supports',
		'extensions/box-shadow',
		'extensions/conic-gradient',
		'extensions/counter',
		'extensions/dark-mode',
		'extensions/icon',
		'extensions/inline-color',
		'extensions/onclick',
		'extensions/placeholder',
		'extensions/svg',
		'fonts',
		'patterns',
		'scripts',
		'styles',
		'blocks/button',
		'blocks/columns',
		'blocks/cover',
		'blocks/group',
		'blocks/heading',
		'blocks/image',
		'blocks/list',
		'blocks/navigation-submenu',
		'blocks/navigation',
		'blocks/page-list',
		'blocks/paragraph',
		'blocks/post-author',
		'blocks/post-comments-form',
		'blocks/post-content',
		'blocks/post-date',
		'blocks/post-excerpt',
		'blocks/post-featured-image',
		'blocks/post-terms',
		'blocks/post-title',
		'blocks/query-pagination',
		'blocks/query-title',
		'blocks/query',
		'blocks/search',
		'blocks/site-logo',
		'blocks/social-link',
		'blocks/social-links',
		'blocks/table-of-contents',
		'blocks/tag-cloud',
		'blocks/template-part',
		'blocks/video',
	];

	foreach ( $files as $file ) {
		$path = __DIR__ . "/includes/$file.php";

		if ( is_readable( $path ) ) {
			require_once $path;
		}
	}
}
