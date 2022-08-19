# Blockify Theme

Lightweight yet powerful full site editing starter theme for building modern WordPress sites with blocks. Use it as a starting point to create almost any design! Optimized for speed - loads zero CSS, JavaScript, image or web font files by default. Style variations, block patterns, templates and placeholder content to get started quickly. Blockify extends core WordPress blocks allowing finer design control. Smart customization features including dark mode support, Google Fonts, SVG icons, box shadows, text gradients, block positioning, responsive settings and more. Please note Gutenberg is still experimental and updates can cause breaking changes.

![screenshot](https://user-images.githubusercontent.com/24793388/181021587-6476bc7a-9400-4213-9af7-2b8e2687c921.png)

## Installation

1. In your site's admin panel, go to Appearance > Themes and click `Add New`.
2. Type "Blockify" in the search field.
3. Click `Install` and then `Activate` to start using the theme.
4. Navigate to Appearance > Customize in your admin panel and customize to your needs.
5. A notice box may appear, recommending you to install the Blockify plugin. You can either use it or any other block toolkit.
4. Navigate to Appearance > Editor.
7. With the Site Editor open, click the inserter icon, pick one of the many ready-made blocks or patterns then click to insert.
8. Edit the page content as you wish, you can add, remove and customize any of the blocks.
9. Enjoy :)

## Features

- **Block Supports API:** Easy to use PHP API for modifying core block supports. This allows for conditional block supports, or extra settings for core blocks. By default, Blockify enables extra block supports where possible.
- **Block Styles API:** Easy to use PHP API for modifying core block styles that usually require JS. Conditional registration supported - for example, only register a "Secondary" block style if a secondary color is defined in the theme or editor.
- **Block Extensions:** Additional appearance controls for all blocks including box shadows, absolute positioning, CSS transforms, CSS filters and more.
- **Full Site Editing:** Additional page, post and template part settings provided to make customizing individual pages easier.
- **SVG Icons:** Inline SVG icons can be created with the image block or as inline text. Default icons included are WordPress, Dashicons and Social Icons. Also supports custom SVGs.
- **CSS Framework:** Minimal base FSE CSS framework. All CSS files have are split and are conditionally loaded only when required by a page.
- **Google Fonts:** Automatically downloads and locally serves selected editor fonts.
- **Gradients:** Gradient rich text formats and text block gradient settings.
- **Text Formats** Additional text formats including clear formatting, underline, gradients, font sizes and more.
- **Responsive Settings:** Reverse on mobile, hide on mobile and more.
- **Header Styles:** Support for absolute, transparent and sticky headers.
- **Mega Menu:** Create simple, multi-column dropdown menus.
- **Search Toggle:** Full screen, CSS-only, search form toggle.
- **Dark Mode:** Automatically enables dark mode for any supported theme.

*Pro features*

- **Block Library:** Unbranded, customizable, commonly needed UI components, fully configurable through theme.json.
- **WooCommerce:** Additional support and styling for WooCommerce blocks.

## Requirements

- WordPress ^6.0
- PHP ^7.4

## Contributing

All contributions and questions are welcome. Please feel free to submit Github issues.

## Support

Visit [https://blockifywp.com/support](https://blockifywp.com/support)

## Theme Developers

Blockify was built for you! It should work out of the box with any standard FSE theme. There is also a free starter theme available for use as an example base.

Child themes fully supported and recommended. Documentation and starter child theme coming soon.

To add theme support, copy and paste the code snippet below into your parent theme, child theme or custom plugin to begin configuring your settings:

```php
// Filter Blockify config.
add_theme_support( 'blockify', [

    // Register custom icon set with icon block.
    'icons' => [
        'fontawesome' => get_stylesheet_directory() . '/assets/svg/fontawesome',
    ],

	// Modify default block supports.
	'blockSupports' => [
		'core/paragraph' => [
			'alignWide' => true,
		],
	],

	// Block styles to be registered correctly with JS.
	'blockStyles'   => [
		'unregister' => [
			[
				'type' => 'core/separator',
				'name' => [ 'wide', 'dots' ],
			],
		],
		'register'   => [
			[
				'type'  => 'core/button',
				'name'  => 'secondary',
				'label' => __( 'Secondary', 'blockify' ),
			],
		],
	],

	// Colors to swap (requires pro).
	'darkMode'      => [
		'black' => 'white',
		'white' => 'black',
	],
] );
```

Alternatively, you can completely overwrite the defaults and start blank by using the `blockify` filter. For example:

```php
namespace Custom\Theme;

add_filter( 'blockify', __NAMESPACE__ . '\\blockify_config' );
/**
 * Customize Blockify config.
 *
 * @since 1.0.0
 *
 * @param array $defaults Default Blockify config.
 *                       
 * @return array Custom config.
 */
function blockify_config( array $defaults ) : array {
    return [
        ...$defaults,
        'blockSupports' => [
            'core/paragraph' => [
                'alignWide' => true,
            ],
        ],
    ];
}
```

## Screenshots

![blocks](https://ps.w.org/blockify/assets/screenshot-1.png)
*Block library*

![shadows](https://ps.w.org/blockify/assets/screenshot-2.png)
*Box shadow settings*

![gradients](https://ps.w.org/blockify/assets/screenshot-3.png)
*Text gradients*
