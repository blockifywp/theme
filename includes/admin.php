<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WPTRT\AdminNotices\Notices;
use function add_action;

add_action( 'admin_init', NS . 'add_admin_notices' );
/**
 * Displays admin notices to users.
 *
 * @since 0.3.5
 *
 * @return void
 */
function add_admin_notices(): void {

	if ( defined( 'GUTENBERG_VERSION' ) ) {
		$notices = new Notices();

		$notices->add(
			'blockify-gutenberg',
			__( 'Please deactivate Gutenberg', 'blockify' ),
			__( 'Hi there! Thank you for using Blockify. To ensure correct styling, please deactivate Gutenberg. This notice can be dismissed for developers requiring Gutenberg for testing.', 'blockify' ),
			[]
		);

		$notices->boot();
	}
}
