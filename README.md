# Blockify Theme

Lightweight yet powerful full site editing starter theme for building modern WordPress sites with blocks. Use it as a starting point to create almost any design! Optimized for speed - loads zero CSS, JavaScript, image or font files by default. Style variations, block patterns, templates and placeholder content to get started quickly. Blockify adds minimal settings which extend core WordPress blocks in a natural way, allowing for a greater level of design control. Dark mode, Google Fonts, SVG icons, box shadows, text gradients, absolute positioning and responsive settings are some of the smart customization features included. Please note Gutenberg is an experimental plugin and updates may cause breaking changes that require block recovery.

![screenshot](https://raw.githubusercontent.com/blockifywp/theme/aaf0f14b2db28b37e69a8ee52a8d7d565e691355/screenshot.png)

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
- **Block Styles API:** Easy to use PHP API for modifying core block styles that usually require JS. Conditional registration supported - for example, only register a "Secondary" block style if a secondary color has been defined in the theme or editor.
- **Block Extensions:** Additional appearance controls for all blocks including box shadows, absolute positioning, CSS transforms, CSS filters and more.
- **Full Site Editing:** Additional page, post and template part settings provided to make customizing individual pages easier.
- **SVG Icons:** Inline SVG icons can be created with the image block or as inline text. Default icons included are WordPress, Dashicons and Social Icons. Also supports custom SVGs.
- **CSS Framework:** Minimal base FSE CSS framework. All CSS files have are split and are conditionally loaded only when required by a page. Fixes some core CSS issues
- **Google Fonts:** Automatically downloads and locally serves selected editor fonts.
- **Gradients:** Gradient rich text formats and text block gradient settings.
- **Text Formats** Additional text formats including clear formatting, underline, gradients, font sizes and more.
- **Responsive Settings:** Reverse on mobile, hide on mobile and more.
- **Header Styles:** Support for absolute, transparent and sticky headers.
- **Mega Menu:** Create simple, multi-column dropdown menus using the core submenu block.
- **Search Toggle:** Full screen, CSS-only, search form toggle.
- **Dark Mode:** Automatically enables dark mode for any supported theme. Dark mode can be deactivated from the Blockify settings available in the page editor.
- **eCommerce Support (coming soon!):** Full support coming for both WooCommerce and Easy Digital Downloads.
- **Block Library:** Blockify should work out of the box with any block library plugin, and a free Block Library plugin is also available for download on both [WordPress.org](https://wordpress.org/plugins/blockify) and [GitHub](https://github.com/blockifywp/plugin). 

### Blockify plugin

Free, lightweight collection of fully customizable, commonly needed blocks, fully configurable through theme.json.

- **Breadcrumbs:** Displays page and post breadcrumbs. This block will be removed if WordPress core adds a breadcrumbs block.
- **Icon:** Inline SVG icon block. Default icon sets include Dashicons, Social icons and WordPress editor icons. Renders an inline SVG, no icon fonts are loaded. Supports gradients and shadows.
- **Counter:** Counts up or down from one number to another.
- **Tabs:** Simple and useful tabbed content section block. Can also be used for creating pricing table switches.
- **Google Map:** Easy to use, customizable Google Map block. Also supports dark mode.
- **Newsletter:** Displays a newsletter block. NOTE: This block is still experimental.
- **Slider:** A simple, lightweight vanilla JS slider block.
- **Accordion:** A simple, customizable, accordion/faq block.  
- **Popup Block (coming soon!):** Lightweight, zero JS modal popups.

### Pro features

- **Pro Blocks:** Dark mode toggle, cookie consent, mailchimp integration and more.
- **Pro Extensions:** React front-end, animations, additional icons, text-effects, click-to-copy code block and more. 
- **Pro Patterns:** Additional block patterns for use in the editor.
- **Pro Styles:** Pro theme style variations.

## Requirements

- WordPress ^6.0
- PHP ^7.4

## Contributing

All contributions and questions are welcome. Please feel free to submit Github issues.

## Support

Visit [https://github.com/blockifywp/theme/issues](https://github.com/blockifywp/theme/issues) to submit a support ticket. 

## Theme Developers

To customize the default Blockify config, copy and paste the code snippet below into your child theme or custom plugin to begin configuring your settings:

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

![shadows](https://ps.w.org/blockify/assets/screenshot-2.png)
*Box shadow settings*

![gradients](https://ps.w.org/blockify/assets/screenshot-3.png)
*Text gradients*

![blocks](https://ps.w.org/blockify/assets/screenshot-1.png)
*Block library (in blockify plugin)*
