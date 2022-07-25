# Webfonts Loader

Downloads webfonts (like for example Google-Fonts), and hosts them locally on a WordPress site.

This improves performance (fewer requests to multiple top-level domains) and increases privacy. Since fonts get hosted locally on the site, there are no pings to a 3rd-party server to get the webfonts and therefore no tracking.

## Usage

A WordPress theme will typically enqueue assets using the [`wp_enqueue_style`](https://developer.wordpress.org/reference/functions/wp_enqueue_style/) function:

```php
function my_theme_enqueue_assets() {
	// Load the theme stylesheet.
	wp_enqueue_style(
		'my-theme',
		get_stylesheet_directory_uri() . '/style.css',
		array(),
		'1.0'
	);
	// Load the webfont.
	wp_enqueue_style(
		'literata',
		'https://fonts.googleapis.com/css2?family=Literata&display=swap',
		array(),
		'1.0'
	);
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_assets' );
```

To locally host the webfonts, you will first need to download the [`wptt-webfont-loader.php`](https://raw.githubusercontent.com/WPTT/font-loader/master/wptt-webfont-loader.php) file from this repository and copy it in your theme. Once you do that, the above code can be converted to this:
```php
function my_theme_enqueue_assets() {
	// Include the file.
	require_once get_theme_file_path( 'inc/wptt-webfont-loader.php' );
	// Load the theme stylesheet.
	wp_enqueue_style(
		'my-theme',
		get_stylesheet_directory_uri() . '/style.css',
		array(),
		'1.0'
	);
	// Load the webfont.
	wp_enqueue_style(
		'literata',
		wptt_get_webfont_url( 'https://fonts.googleapis.com/css2?family=Literata&display=swap' ),
		array(),
		'1.0'
	);
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_assets' );
```

## Available functions

### `wptt_get_webfont_styles`
```
$remote_url = 'https://fonts.googleapis.com/css2?family=Literata&display=swap';
$contents   = wptt_get_webfont_styles( $remote_url );
```
Returns the stylesheet contents, using locally hosted webfonts.

### `wptt_get_webfont_url`
```
$remote_url = 'https://fonts.googleapis.com/css2?family=Literata&display=swap';
$contents   = wptt_get_webfont_url( $remote_url );
```
Returns a stylesheet URL, locally-hosted.

## Build url for multiple fonts
```php
$font_families = array(
	'Quicksand:wght@300;400;500;600;700',
	'Work+Sans:wght@300;400;500;600;700'
);

$fonts_url = add_query_arg( array(
	'family' => implode( '&family=', $font_families ),
	'display' => 'swap',
), 'https://fonts.googleapis.com/css2' );

$contents = wptt_get_webfont_url( esc_url_raw( $fonts_url ) );
```

## Supporting IE
The `wptt_get_webfont_url` will - by default - download `.woff2` files. However, if you need to support IE you will need to use `.woff` files instead. To do that, you can pass `woff` as the 2nd argument in the `wptt_get_webfont_url` function:
```php
wptt_get_webfont_url( 'https://fonts.googleapis.com/css2?family=Literata&display=swap', 'woff' );
```

## Storing In A Custom Directory
If you have the need to store font files in a custom directory you can pass a custom path and URL using filters. Be sure you add these filters **BEFORE** the file containing the `WPTT_WebFont_Loader` class is called.

```php
/**
 * Change the base path.
 * This is by default WP_CONTENT_DIR.
 *
 * NOTE: Do not include trailing slash.
 */
add_filter( 'wptt_get_local_fonts_base_path', function( $path ) {
	return WP_CONTENT_DIR;
} );

/**
 * Change the base URL.
 * This is by default the content_url().
 *
 * NOTE: Do not include trailing slash.
 */
add_filter( 'wptt_get_local_fonts_base_url', function( $url ) {
	return content_url();
} );

/**
 * Change the subfolder name.
 * This is by default "fonts".
 *
 * Return empty string or false to not use a subfolder.
 */
add_filter( 'wptt_get_local_fonts_subfolder_name', function( $subfolder_name ) {
	return 'fonts';
} );
```
