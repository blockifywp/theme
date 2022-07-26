# Blockify Theme

A lightweight yet powerful full site editing block starter theme. Use it to create any design you can imagine! Insanely fast, 100% theme.json based - loads zero CSS and JS by default. Multiple style variations, block patterns, and templates to get started quickly. Smart features including automatic Google Font loading, box shadows, text gradients, spacing controls, responsive settings and more.

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

- **Block Supports API:** Easy to use PHP API for modifying core block supports. This allows for conditional block supports, or extra settings for core blocks.
- **Block Styles API:** Easy to use PHP API for modifying core block styles that usually require JS. Conditional registration supported - for example, only register a "Secondary" block style if a secondary color is set in the theme or editor.
- **Block Library:** Unbranded, fully customizable, commonly needed UI components. Configurable through theme.json.
- **Block Extensions**: Additional appearance controls for all blocks. For example, box shadows, absolute positioning.
- **Full Site Editing:** CSS framework. Extra page, post and template part settings.
- **Google Fonts:** Automatically downloads and locally serves selected editor fonts.
- **Text Formats:** Additional text formats including gradients, font size and more.
- **Responsive Settings:** Reverse on mobile, hide on mobile and more.
- **Dark Mode (Pro):** Automatically enables dark mode for any supported theme.

## Child themes

Child themes fully supported and recommended. Documentation and starter child theme coming soon.

## Support

Visit [https://blockifywp.com/support](https://blockifywp.com/support)

## Requirements

- WordPress ^6.0
- PHP ^7.4

## Contributing

All contributions and questions are welcome. Please feel free to submit Github issues.

## Theme Developers

Blockify was built for you! It should work out of the box with any standard FSE theme. There is also a free starter theme available for use as an example base

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

More coming soon!
