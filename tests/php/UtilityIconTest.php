<?php

namespace Blockify\Tests;

use WP_REST_Server;
use const ABSPATH;
use const WPINC;

beforeEach( function () {
	parent::setUp();

	// Set up a REST server instance.
	global $wp_rest_server;

	if ( ! class_exists( '\WP_REST_Server' ) ) {
		return;
	}

	$this->server = $wp_rest_server = new WP_REST_Server();
	do_action( 'rest_api_init', $this->server );
} );

afterEach( function () {
	global $wp_rest_server;
	$wp_rest_server = null;

	parent::tearDown();
} );

test( 'rest API endpoints work', function () {
	if ( ! class_exists( '\WP_REST_Server' ) ) {

		// If the REST API is not available, skip the test.
		$this->markTestSkipped( 'TODO: Configure Rest API.' );

		return;
	}

	$routes = $this->server->get_routes();

	expect( $routes )
		->toBeArray()
		->toHaveKey( '/wp/v2/posts' );
} );
