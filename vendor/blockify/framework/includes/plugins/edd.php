<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function add_filter;

add_filter( 'edd_get_option_disable_styles', fn() => true );
