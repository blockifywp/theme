<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function function_exists;
use function is_readable;
use const DIRECTORY_SEPARATOR;

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
		'utility/array',
		'utility/color',
		'utility/css',
		'utility/dom',
		'utility/helper',
		'utility/icon',
		'utility/string',
		'api/block-extensions',
		'api/block-styles',
		'api/block-supports',
		'extensions/animation',
		'extensions/counter',
		'extensions/copy-to-clipboard',
		'extensions/dark-mode',
		'extensions/gradient',
		'extensions/grid',
		'extensions/icon',
		'extensions/inline-color',
		'extensions/onclick',
		'extensions/placeholder',
		'extensions/shadow',
		'extensions/svg',
		'extensions/template-tags',
		'common/fonts',
		'common/patterns',
		'common/scripts',
		'common/styles',
		'common/templates',
		'blocks/avatar',
		'blocks/button',
		'blocks/buttons',
		'blocks/columns',
		'blocks/cover',
		'blocks/group',
		'blocks/heading',
		'blocks/image',
		'blocks/list',
		'blocks/navigation',
		'blocks/page-list',
		'blocks/pagination',
		'blocks/paragraph',
		'blocks/pattern',
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
		'blocks/shortcode',
		'blocks/site-logo',
		'blocks/social-link',
		'blocks/social-links',
		'blocks/spacer',
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
