=== Blockify - Lightweight Full Site Editing Block Library ===
Contributors: blockify
Requires at least: 6.0
Tested up to: 6.0
Requires PHP: 7.4
Stable tag: 0.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Blockify is a lightweight full-site editing block theme framework for WordPress. It provides useful features to help you build modern, fast-loading websites using only core blocks. Blockify is optimized for speed, with no CSS, JavaScript, image, or font files loaded by default. Customization settings include SVG icons, custom Google Fonts, box shadows, gradient text, absolute positioning, responsive settings, negative margins and more.

Designed for use with any FSE theme and the most popular WordPress plugins. It's the perfect starting point and toolkit for building your own custom block theme. With Blockify, creating fast and stylish block-based websites has never been easier. Give it a try today and see the difference it can make for your site.

## Site Editing

Blockify is designed to work with any FSE theme. It provides some useful settings to help you customize your site, including:

* Site Identity (logo, title, tagline)
* Custom Google Fonts
* SVG Icons
* Additional CSS
* Dark Mode

All settings are available in the Site Editor or any editor page. Locate the Blockify settings in the Options > Plugins section of the Editor.

## Block Variations

Blockify provides a number of block variations that use only core WordPress blocks:

* Accordion - (list block)
* Counter - (paragraph block)
* Curved Text - (paragraph block)
* Grid - (group block - coming soon)
* Icon - (image block)
* Marquee - (group block)
* SVG - (image block)

## Block Extensions

Block extensions with responsive and hover state settings:

* Animation
* Background
* Display
* Filter
* Negative Margins
* On Click attributes
* Position
* Box Shadow
* Transform

## Text Formats

* Clear Formatting
* Gradient Text
* Inline SVGs
* Text Outline
* Typography (Font Family, Font Size, Font Weight)
* Underline (multiple styles)

## Block Styles

* Surface
* Dark/Light mode
* Checklist
* Checklist Circle
* Checklist Square
* Mega Menu
* Sub Heading
* Notice
* Divider - curve/wave/angle/round/fade

== Frequently Asked Questions ==

= What themes does this plugin work with? =

This plugin is designed to work with any Full Site Editing block theme.

= Where is the settings page? =

This plugin provides no settings page. All settings are available in the block editor and can be accessed by clicking on the Blockify icon in the top right corner of the screen.

= How to add custom Dark Mode colors? =

The Dark Mode color palette can be customized from the Site Editor by clicking the Styles icon in the top right of the screen.

1. Navigate to Appearance > Editor
2. Click on the Styles button in the top right corner (light/dark circle)
3. Click Colors
4. Click Palette
5. Click the Custom tab
6. In the Custom section, click the Add ➕ button
7. Select your colors and then give each color a name beginning with Dark Mode, for example:
	Dark Mode Foreground
8. Click Done and click Save to save your changes

= How is this a theme framework? =

The Blockify plugin provides a set of useful features to help you build modern, fast-loading websites using only core blocks. It's the perfect starting point and toolkit for building your own custom block theme.

The framework includes a tonne of useful block customization features, smart base CSS, and a set of block variations and extensions to help you build your site faster. It's designed to work with any FSE theme and the most popular WordPress plugins.

It can be installed as a plugin, theme or a Composer package. The free Blockify theme demonstrates how to use the plugin as a package in your own theme. The recommended way to use Blockify is as a plugin with your own parent block theme. The Blockify theme is not required.

= Do I need to install the Blockify theme? =

The Blockify theme is not required if the Blockify plugin is active.

The Blockify plugin will work with any parent block theme.

= How do I add icons to the editor? =

SVG Icons can be created by inserting an Image block anywhere in the block editor and then selecting the "SVG Icon" block style from the right sidebar settings. The original image is not loaded and the selected SVG icon is displayed instead. To add a gradient to an icon, select a gradient from the Background Color setting and clear any colors from the Text Color setting.

= How do I add a custom icon set to the editor? =

Custom icon sets can be added by passing the path to the icons to the Blockify config. Please see the default Blockify theme for an example.

= What version of PHP do I need? =

PHP 7.4 or higher is required. Lower versions are no longer supported.

= How do I add code snippets for filter and action hooks? =

Parent themes, child themes and plugins can all be used to modify the default behaviour of Blockify. Every function is either added with a filter or an action which provides developers more control.

== Screenshots ==

1. Site Identity settings
2. Google Fonts, Icons and Additional CSS
3. Transform and filter effects
4. Box Shadow
5. Text Gradients
6. Text underline
7. Icon block variation
8. SVG block variation


== Installation ==

This plugin can be installed directly from your site.

1. Log in and navigate to Plugins → Add New.
2. Type "Blockify" into the search and hit Enter.
3. Locate the Blockify plugin in the list of search results and click Install Now.
4. Once installed, click the Activate link.

It can also be installed manually:

1. Download the Blockify plugin from WordPress.org.
2. Unzip the package and move to your plugins directory.
3. Log into WordPress and navigate to the Plugins screen.
4. Locate Blockify in the list and click the Activate link.

== Copyright ==

This plugin, like WordPress, is licensed under the GPL.

© Copyright 2022 BlockifyWP.

== Changelog ==

= 0.5.0 - 8 December 2022 =

* Add: Plugin settings
* Add: Theme framework
* Add: Dark mode toggle block
* Remove: Existing blocks

= 0.4.0 - 16 November 2022 =

* Add: Api Key fields
* Remove: Deprecated blocks (replaced with variations)

= 0.3.0 - 3 September 2022 =
* Add: New reCaptcha v2 block
* Add: reCaptcha v3 setting to Submit block
* Add: Columns support to form
* Add: blockify_form_user_data filter
* Add: Slider arrows and dots visibility settings

= 0.2.0 - 29 August 2022 =
* Update: Rename newsletter block to form

= 0.0.15 - 20 August 2022 =
* Remove: Divider block, moved to spacer block
* Add: Missing pot file

= 0.0.14 - 20 August 2022 =
* Fix: Calls to undefined functions

= 0.0.13 - 19 August 2022 =
* Remove: Theme related features

= 0.0.12 - 25 July, 2022 =
* Fix: Reverse on mobile
* Fix: Navigation block font size

= 0.0.11 - 24 July 2022 =
* Fix: Google fonts loading
* Remove: Theme related functions

= 0.0.10 - 22 July 2022 =
* Update: Move icons to config

= 0.0.9 - 21 July 2022 =
* Fix: Reverse on mobile settings
* Update: Improve box shadows

= 0.0.8 - 20 July 2022 =
* Fix: Google map block
* Fix: Site editor scripts

= 0.0.7 - 19 July 2022 =
* Add: Accordion block description
* Update: Add blocks to config

= 0.0.6 - 19 July 2022 =
* Add: Block descriptions

= 0.0.5 - 19 July 2022 =
* Add: Screenshots

= 0.0.4 - 19 July 2022 =
* Remove: Pattern functions

= 0.0.3 - 15 July 2022 =
* Update: Readme

= 0.0.2 - 12 July 2022 =
* Remove: Framework CSS

= 0.0.1 - 20 June 2022 =
* Initial commit:
