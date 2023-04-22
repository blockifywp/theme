=== Blockify ===
Contributors: blockify
Requires at least: 6.1
Tested up to: 6.2
Stable tag: 1.2.8
Requires PHP: 7.4
License: GPL-2.0-or-later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Lightweight full site editing block theme framework. Blockify provides useful features that help you build modern WordPress sites with blocks. Optimized for speed - loads zero CSS, JavaScript, image or font files by default. Customization settings include SVG icons, custom Google Fonts, box shadows, gradient text, absolute positioning, responsive settings, negative margins and more. Designed for use with child themes and compatible with most popular plugins. Blockify is a great starting point and toolkit for building your own custom block theme.

== Installation ==

1. In your site's admin panel, go to Appearance > Themes and click `Add New`.
2. Type "Blockify" in the search field.
3. Click `Install` and then `Activate` to start using the theme.
4. Navigate to Appearance > Editor.
5. Edit the page content as you wish, you can add, remove or customize any of the blocks.
6. Enjoy :)

== Copyright ==

Copyright © 2023, Blockify.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details

Composer
License: MIT
License URL: https://github.com/composer/composer/blob/main/LICENSE

WordPress & Social Icons
License: GPL-2.0-or-later
License URL: https://github.com/WordPress/WordPress/blob/master/license.txt

Google Fonts
License: SIL Open Font License, 1.1
License URL: https://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL

© Copyright Blockify 2023, Blockify.

== Changelog ==

= 1.2.8 - 22 April 2023 =

* Fix: Pages list menu item width
* Fix: Icon alignment for centered images
* Update: Year template tag
* Update: Page template main padding bottom

= 1.2.7 - 22 April 2023 =

* Add: Block gap support to page list block
* Update: CSS optimizations
* Update: Patterns with new styles
* Remove: Plugin styles
* Remove: Unused mega menu functions
* Fix: JavaScript errors with Gutenberg 15.6
* Fix: Editor styles bug
* Fix: Icon block variation alignment
* Fix: Template part block filter

= 1.2.6 - 4 April 2023 =

* Update: Tested up to version
* Fix: Icon block variation styles and classes

= 1.2.5 - 3 April 2023 =

* Fix: Broken image link in SVG block variation

= 1.2.4 - 27 March 2023 =

* Add: Support for light mode (default dark mode themes)
* Add: Descriptive screen reader text to read more links
* Add: Video button background custom property
* Add: List style none block style
* Add: Support for separate border styles for surfaces
* Add: Blockify position support to social links block
* Add: Site title template tag
* Add: Support for mega menus
* Update: Inline code styling
* Update: Move front page template to patterns
* Update: Mark element padding
* Fix: Social link hash URLs
* Fix: Icon block variation classes and inline styles
* Fix: Divider widths
* Fix: Checklist circle alignment
* Fix: Video button style inheritance
* Fix: Marquee offscreen in editor when reverse and no animation set
* Fix: Text highlight background color
* Fix: Outline button text gradient specificity
* Remove: Important rule for ghost button color
* Remove: Unused button width CSS

= 1.2.3 - 14 March 2023 =

* Add: is_plugin function to check if installed as plugin
* Update: Default curved text placeholder and size
* Remove: Animation settings help text
* Remove: SLUG constant
* Fix: Editor settings CSS
* Fix: Check editor.css file exists before getting filemtime

= 1.2.2 - 13 March 2023 =

* Fix: Error when template html returning null
* Fix: YouTube block button height
* Update: Feature icons patterns for Gutenberg compat
* Update: Rename register patterns function

= 1.2.1 - 11 March 2023 =

* Update: Improve WooCommerce styles
* Update: Change front end style handle
* Fix: EDD trash icon in dark mode
* Fix: Duplicate db queries with content check
* Fix: Animation condition checks
* Remove: get_page_content function
* Remove: Page template patterns

= 1.2.0 - 5 March 2023 =

* Add: Heading block style for paragraph and site title
* Update: Change load priority of extension files
* Fix: Icon block variation background color issue
* Fix: Button white space wrapping
* Fix: Accordion surface style improvements
* Fix: Change brush underline display to inline-block
* Remove: Keyboard shortcuts for text formats

= 1.1.1 - 18 February 2023 =

* Add: Submenu background color
* Fix: Submenu open on click button icon
* Update: Hero text

= 1.1.0 - 18 February 2023 =

* Add: Core button classes to EDD buttons
* Add: Improved EDD styles
* Add: Surface scss mixin
* Add: Block pattern templates
* Add: remove_line_breaks function
* Add: Size option to get_icon function
* Add: Composer autoloading support for functions.php
* Update: Conditionally add light/dark block styles if dark mode enabled
* Update: Font sizes
* Update: Color palette
* Update: Don't load default patterns for child themes
* Update: Default sub heading font size from small to smaller
* Update: Use important for transparent ghost button background
* Update: PHPStan memory limit to 1GB
* Remove: Wireframe and blueprint style variations
* Remove: SVN npm script
* Remove: All frontend references to blockify
* Remove: DIR and FILE path constants
* Remove: Use of glob for php files
* Fix: Icon classes now applied to figure
* Fix: SVG block variation improvements
* Fix: Remove unused arg from border mixin
* Fix: EDD download archive template title
* Fix: TinyMCE button styles in editor
* Fix: Cover block padding error
* Fix: Empty block option styles
* Fix: ESLint improvements
* Fix: Search block padding formatted values

= 1.0.0 - 6 February 2023 =

* Add: Basic support for LifterLMS plugin
* Add: Surface block style mixin
* Update: Copyright year
* Update: Ignore generated files in editorconfig
* Fix: Empty post title error
* Fix: EDD update profile button styling to match other buttons
* Remove: Scrollbar width custom property in favor of dvw

= 0.9.36 - 1 February 2023 =

* Add: Scroll position custom property for animations
* Add: Slack block variation for social icons
* Add: Support for non-stacking query loop columns
* Add: Plugin compatibility back to theme
* Add: Default syntax highlighting color scheme
* Add: Margin support to query block
* Add: Positioning support to slider block
* Add: Badge and badges block styles
* Add: Ghost block style to button block
* Add: Button block style to read more block
* Add: Surface block style to quote block
* Add: Spacing support for query title and post terms block
* Add: Filter for post comments form title
* Add: Grid gradient background pattern support
* Add: Input border custom property
* Add: Border support to post comments form
* Add: Support for gradient mask to sub-heading block style
* Update: Improved PHP 8.2+ support
* Update: Split up block extensions for faster access
* Update: Only show OnClick setting for admin users
* Fix: Box shadow support for featured image block
* Fix: Curved text editor iframe
* Fix: Box shadow rendering
* Fix: Paragraph block always add wp-block-paragraph class
* Fix: Cover block padding and z-index
* Fix: Icon block variation background color issue
* Fix: File input height
* Fix: Navigation styling
* Fix: Circle checklist positioning
* Remove: Form element margin
* Remove: Default margin from post template block
* Remove: Click to copy CSS (moved to plugin)

= 0.9.35 - 5 January 2023 =

* Add: Dynamic search results title
* Add: CPT archive search results
* Add: Badge styles
* Add: Block gap support to table of contents
* Add: Position support to query block
* Add: Default links for empty TOC
* Add: Default related articles for empty query block
* Add: all-terms support to post terms block
* Add: Block gap support to heading block
* Add: Search results heading string
* Add: Gradient support to post title and query title blocks
* Add: .wp-site-blocks overflow custom property
* Add: Text gradient support to Site Title block
* Add: 100vh min height to navigation responsive container
* Fix: Center alignment for checklist icon
* Update: Remove empty separator span elements from post terms block
* Remove: Custom fields functionality
* Remove: Plugin styles
* Remove: Excerpt length filter
* Remove: Dark mode filters
* Remove: Ninja Forms display CSS
* Remove: Composer and TGMPA (plugin no longer needed)
* Remove: Click to copy
* Remove: Unused font files

= 0.9.34 - 28 December 2022 =

* Add: Code block improvements
* Add: Border and box shadow support to List block
* Add: Margin and padding support to featured image block
* Update: Pattern content
* Update: Screenshot
* Fix: Nav menu vertical padding
* Fix: Emoji styles
* Fix: OL support for accordion

= 0.9.33 - 20 December 2022 =

* Add: EDD and WooCommerce includes
* Fix: Stylesheet escaping

= 0.9.32 - 20 December 2022 =

* Fix: Missing font files
* Fix: Editor style paths

= 0.9.31 - 20 December 2022 =

* Add: Escaping for CSS output
* Remove: Framework

= 0.9.30 - 17 December 2022 =

* Update: Install framework

= 0.9.29 - 9 December 2022 =

* Update: Version bump

= 0.9.28 - 9 December 2022 =

* Add: Theme framework
* Add: SVG site logo support
* Add: TGMPA
* Add: Blockify plugin support

= 0.9.27 - 6 December 2022 =

* Add: Margin auto classes
* Add: Back to top anchor
* Add: ARIA role banner to header template part
* Fix: Navigation block gap
* Fix: Restrict Content do_blocks conflict
* Fix: Submenu block dimension custom property support
* Remove: Body fade-in css

= 0.9.26 - 1 December 2022 =

* Add: Open on click support for submenus on mobile
* Add: Basic accordion animation
* Add: Default button gap
* Add: Template search pattern
* Add: More supports to buttons block
* Add: get_elements_by_class_name() helper function
* Fix: Outline button border width
* Fix: Accordion toggle icon overlap issue

= 0.9.25 - 28 November 2022 =

* Add: Browser block pattern
* Add: Inline color custom property support
* Add: Onclick support to group block
* Add: Dark/light styles to column blocks
* Update: Patterns
* Update: Blockify theme settings
* Fix: Sub menu styling
* Fix: Remove nav menu ref in patterns
* Fix: List block spacing
* Fix: Accordion marker webkit display
* Fix: Style loading priority (make latest)
* Remove: Font stack custom properties (using presets)

= 0.9.24 - 27 November 2022 =

* Add: Automatic heading IDs
* Add: Positioning support to List block
* Add: Block gap support to list block
* Fix: Video background and margin styles
* Fix: More font loading improvements
* Fix: Reverse menu toggle button on mobile
* Fix: Dark mode custom property strings
* Fix: Social links colors
* Fix: Curved text content saving
* Fix: Marquee block gap
* Remove: Menu links

= 0.9.23 - 25 November 2022 =

* Add: Header, footer and template admin links
* Fix: Editor stylesheet loading

= 0.9.22 - 24 November 2022 =

* Add: Inline styles filter
* Fix: Styles not loading in editor

= 0.9.21 - 24 November 2022 =

* Add: Prefers reduced motion check to counter animation
* Fix: Editor style loading priority
* Remove: Composer autoloader from load files

= 0.9.20 - 23 November 2022 =

* Add: List item color support
* Add: Flow block font family
* Add: Code block position and transform support
* Add: Content option to display settings
* Fix: Style loading in patterns
* Fix: Sub menu getAttribute method check
* Fix: Icon display contents check
* Remove: Theme slug from header pattern

= 0.9.19 - 22 November 2022 =

* Add: New settings UI
* Remove: Child theme json fix
* Fix: Placeholder border
* Fix: Surface before psuedo element pointer events
* Fix: Marquee block gap custom properties

= 0.9.18 - 21 November 2022 =

* Add: index.php file
* Remove: Temporary theme.json fix

= 0.9.17 - 18 November 2022 =

* Fix: Typo in theme.json fix

= 0.9.16 - 18 November 2022 =

* Add: Fix for child theme previews not loading theme.json

= 0.9.15 - 18 November 2022 =

* Update: Load all fonts for child themes

= 0.9.14 - 17 November 2022 =

* Add: CSS fix for wp.org preview
* Fix: SVG block string includes bug
* Fix: List block gap support
* Update: Improve post author block styles
* Update: Improve preformatted block styles
* Update: Improve quote block styles

= 0.9.13 - 16 November 2022 =

* Remove: Font family in theme.json

= 0.9.12 - 16 November 2022 =

* Add: WooCommerce templates and styling
* Add: EDD styles
* Update: Group typography text formats
* Update: Absolute header for full-width template
* Fix: Single and page template use content width
* Fix: Checklist circle List block style icon
* Remove: Unused image assets

= 0.9.11 - 12 November 2022 =

* Fix: Remove child theme style.css loading option
* Fix: Font loading when Gutenberg active

= 0.9.10 - 12 November 2022 =

* Add: Responsive positioning settings
* Add: Box shadow hover settings
* Add: Marquee block variation
* Add: SVG block variation
* Add: Filter and transform animations
* Add: Font family text format
* Add: Font size text format
* Add: Curved text block style
* Add: Backdrop filter settings
* Add: Spacing support to navigation block
* Add: Margin support to pagination block
* Add: Boxed blog layout
* Add: Block gap support to Query block
* Add: List and List Item block border support
* Add: Order, overflow and pointer events settings
* Fix: Font family loading
* Remove: Gutenberg as dependency

= 0.9.9 - 29 October 2022 =

* Update: Icons in patterns
* Remove spacing utility classes

= 0.9.8 - 29 October 2022 =

* Add: Spacing scale utility classes
* Add: Ninja form styles
* Add: Min height setting for Group block
* Add: Basic code syntax highlighting plugin support
* Update: Refactor responsive block supports
* Update: Optimize circle text format svg
* Fix: Temporary fix for font loading (again)
* Fix: Conditional style string checks
* Fix: Use do_blocks for content checks
* Fix: Add missing button custom properties
* Fix: Navigation block spacing and sub menu styles
* Fix: Default box shadow color (now uses transparent)

= 0.9.7 - 27 October 2022 =

* Fix: Temporary fix for font loading

= 0.9.6 - 27 October 2022 =

* Add: Box shadow support to post featured image block
* Add: Gradient text for heading and paragraph blocks
* Fix: Fonts not loading
* Fix: Transparent text issue with gradient and underline formats
* Fix: Icon css not loading in pattern preview

= 0.9.5 - 26 October 2022 =

* Add: the_content to conditional css checks

= 0.9.4 - 26 October 2022 =

* Fix: Call to get_site function in contact pattern link

= 0.9.3 - 26 October 2022 =

* Fix: Revert inline icon CSS (not loading wp.org)

= 0.9.2 - 26 October 2022 =

* Fix: Use content_url instead of home_url for inline SVG check

= 0.9.1 - 26 October 2022 =

* Fix: Missing site logo
* Fix: Navigation pattern refs

= 0.9.0 - 26 October 2022 =

* Add: Wireframe and blueprint style variations
* Add: Notice block style for paragraphs
* Add: Dynamic front page pattern templates
* Add: Remaining checks for conditional stylesheets
* Add: Inline SVG support for image block
* Add: Style previews to site editor
* Add: Link and onclick support to icon block variation
* Add: Basic EDD styling
* Add: Add button hover effects
* Add: Notice to deactivate Gutenberg if 6.1
* Add: Template patterns
* Add: Pattern, style and block filters
* Update: Switch to Inter as default font for OS consistency
* Update: Icon block variation optimizations
* Update: Clean up style handling
* Update: Font sizes to semantic scale for better UI
* Update: Simplify pattern registration
* Update: Replace logos with image block
* Update: All patterns for 6.1
* Update: Adjust side spacing for containers
* Fix: Search form block styling
* Remove: All font families from settings
* Remove: Custom numbered list block style
* Remove: Animation scripts
* Remove: Loading all fonts in editor
* Remove: Rest page settings
* Remove: Unused animation scripts
* Remove: Search toggle block style

= 0.8.0 - 13 October 2022 =

* Add: Dark style variation & toggle
* Add: Surface styles & custom properties
* Add: On-click field for button block
* Add: Background, text and link color support for Cover block
* Add: Position utility classes
* Add: Brush stroke color support
* Add: Border inheritance for tables and legend
* Add: Button line height support
* Improve: Cover block colors
* Update: Vertical navigation styling
* Update: Switch to semantic color palette names
* Fix: Remove id from nav menu in patterns
* Fix: Sub menu padding
* Fix: Template part inline css
* Fix: Icon psuedo element bug in Gutenberg 14.3
* Fix: Horizontal submenu padding on mobile

= 0.7.0 - 12 October 2022 =

* Add: Cover block border support
* Add: Boxed style css
* Remove: Use of background gradients as text mask
* Update: Adjust spacing scale again
* Fix: Conic gradient style switching

= 0.6.0 - 10 October 2022 =

* Add: Box shadow inset support
* Add: Animation php filter
* Update: Adjust spacing scale
* Remove: index.php file
* Remove: Box shadow gradient support

= 0.5.1 - 7 October 2022 =

* Add: Featured image margin bottom
* Fix: Gradient picker bug
* Fix: str_between bug
* Remove: Icon guide pattern preview

= 0.5.0 - 6 October 2022 =

* Add: Icon Guide pattern
* Add: Secondary color palette
* Add: Light and secondary button styles
* Update: Clean up all icon patterns
* Update: Rename color palette
* Update: Simplify color palette names
* Fix: Line icons missing fill
* Remove: Template patterns
* Remove: Site logo icon support
* Remove: Site logo default style
* Remove: Navigation separator

= 0.4.6 - 5 October 2022 =

* Fix: Navigation ids in header patterns

= 0.4.5 - 5 October 2022 =

* Add: Default hero pattern
* Update: Selected font loading improvements
* Update: Rename index.js to editor.js
* Update: Rename blockify filter to blockify_editor_script
* Update: Footer pattern spacing
* Fix: Navigation link underline

= 0.4.4 - 5 October 2022 =

* Add: WordPress 6.1-beta support for theme.json filter
* Add: Missing languages files

= 0.4.3 - 5 October 2022 =

* Add: WordPress 6.1 support

= 0.4.2 - 5 October 2022 =

* Fix: Local font loading

= 0.4.1 - 5 October 2022 =

* Fix: Theme.json filters

= 0.4.0 - 4 October 2022 =

* Add: Icon block variation
* Add: Conic gradient support
* Add: Negative margin support
* Add: Underline, brush stroke, circle text formats
* Add: Gradient text format
* Add: CI workflow, phpstan, coding standards props @szepeviktor
* Add: Local web font files
* Add: Sub heading paragraph block style
* Add: Home page block pattern
* Add: Gutenberg 14.2 support
* Add: Tested up to PHP 8.2 support
* Add: Scroll animation
* Add: Spacing scale
* Update: Rename first_child variables to match element
* Update: Separate utility functions
* Update: Move all templates to patterns
* Update: Patterns from units to spacing scale
* Fix: Navigation block styling
* Remove: Image block icon and placeholder styles
* Remove: Slugs from patterns
* Remove: Web font downloader
* Remove: Dashicons

= 0.3.12 - 7 September 2022 =
* Fix: No dark mode config

= 0.3.11 - 7 September 2022 =
* Remove: Admin notice for Gutenberg plugin
* Update: Version bump for wp.org

= 0.3.10 - 6 September 2022 =
* Fix: Resize screenshot

= 0.3.9 - 6 September 2022 =
* Add: 14px font size
* Update: Adjust colors
* Remove: Style variations
* Remove: Pro patterns

= 0.3.8 - 6 September 2022 =
* Update: Clean up patterns
* Fix: Navigation links for wp.org

= 0.3.7 - 6 September 2022 =
* Fix: Remove pattern functionality

= 0.3.6 - 6 September 2022 =
* Fix: Add missing vendor packages!

= 0.3.5 - 6 September 2022 =
* Fix: Add missing vendor packages

= 0.3.4 - 6 September 2022 =
* Fix: Patch string case utility function

= 0.3.3 - 6 September 2022 =
* Add: Light and dark patterns
* Remove: Gutenberg and Blockify plugin dependencies
* Remove: Editor settings panel
* Remove: Automatic dark mode feature

= 0.3.2 - 3 September 2022 =
* Add: Excerpt length setting to editor
* Add: Hide on mobile settings to supported blocks
* Add: Center on mobile to supported blocks
* Add: Block gap support for Query block
* Add: Spacing support for quote block
* Add: Tag cloud correct fallback font size
* Update: Move remaining CSS to theme.json where possible
* Update: Change default block gap from em to rem
* Update: Separate scripts and styles includes
* Remove: Single block pattern from theme.json
* Fix: Render position styles
* Fix: Input heights to match buttons
* Fix: Rest options permissions callback
* Fix: Remove theme slug from single post template for child theme support
* Fix: Permissions callback for rest options

= 0.3.1 = 2 September 2022 =
* Remove: tgmpa languages

= 0.3.0 - 1 September 2022 =
* Add: Spacing scale
* Add: Gradient support for submenu, paragraph, site-logo and template part
* Add: Editor page title text alignment
* Add: Custom property filters where possible
* Add: Quote block and post author avatar border radius support
* Add: Basic CSS for elements without block classes
* Add: Position support to columns
* Add: Blog features post pattern
* Update: Post template layout
* Update: Post title hover effects
* Remove: Page title settings
* Fix: Post block CSS file not loaded
* Fix: Legend/fieldset border-box issue
* Fix: Placeholder image sizes
* Fix: Gradient support not working from incorrect key name
* Fix: Gutenberg 14.0.1 outline button padding issue

= 0.2.1 - 29 August 2022
* Remove: Dark mode preview setting

= 0.2.0 - 29 August 2022 =
* Add: Style guide pattern
* Add: Post featured image border radius
* Add: IBM Plex Sans & Mono, DM Sans, Source Serif Pro
* Add: Global API key settings
* Add: Page list block spacing support
* Update: All settings to use Rest API
* Update: Front page template blog pattern
* Update: CSS loading optimizations
* Update: Switch to dynamic render block filters
* Update: Alignment improvements
* Update: Move all settings to rest api
* Remove: Single block pattern template
* Remove: Link box shadow hover effect
* Fix: Full width template footer template part
* Fix: Default border color contrast
* Fix: Icon even/odd fill rules

= 0.1.4 - 22 August 2022 =
* Add: Color fallbacks in theme.json
* Update: Clean up style variation json
* Fix: Box shadows
* Remove: PHP CSS minification utility
* Remove: Footer social links

= 0.1.3 - 22 August 2022 =
* Update: Link styling improvements
* Update: Color palette adjustments

= 0.1.2 - 22 August 2022 =
* Add: Social icon block to footer default pattern
* Update: Footer pattern improvements
* Remove: Links in footer patterns
* Fix: Pattern preview alignment

= 0.1.1 - 22 August 2022 =
* Fix: Remove testing css

= 0.1.0 - 22 August 2022 =
* Add: Spacing controls to post content block
* Add: Single columns testimonial pattern
* Add: Conditional loading for admin bar styles
* Add: Custom property support for cite element
* Update: Single block pattern improvements
* Update: Refactor query block files
* Update: Only set scrollbar width on desktop
* Fix: Heading styles to pages without heading block
* Fix: Post content layout inheritance
* Fix: Improve wp.org pattern display

= 0.0.33 - 22 August 2022 =
* Add: Full width template
* Add: WP.org pattern temporary css

= 0.0.32 - 22 August 2022 =
* Fix: Custom properties not showing in admin

= 0.0.31 - 21 August 2022 =
* Add: More Google Fonts
* Add: wp-element-button class to search blocks
* Update: Clean up style variations

= 0.0.30 - 21 August 2022 =
* Add: Blueprint style variation
* Add: Dark style variation
* Add: Wireframe style variation
* Fix: Google fonts in editor

= 0.0.29 - 21 August 2022 =
* Add: Placeholder image style
* Add: Group min height setting
* Update: Rename patterns
* Update: Search toggle improvements
* Remove: Page patterns
* Fix: Cover image defaults
* Fix: Remove duplicate classes in blocks
* Fix: Sub menu styling

= 0.0.28 - 20 August 2022 =
* Add: Featured post placeholder divs
* Fix: Site logo icon position
* Fix: Wide alignment in patterns
* Fix: Missing inline editor styles

= 0.0.27 - 20 August 2022 =
* Add: Support for image borders
* Add: Spacer block styles
* Update: CSS loading improvements
* Update: Theme description
* Fix: Broken image urls in patterns

= 0.0.26 - 19 August 2022 =
* Add: Dark mode editor settings
* Add: Outline button style
* Remove: Secondary button style

= 0.0.25 - 14 August 2022 =
* Fix: WordPress update issue
* Update: Screenshot and changelog

= 0.0.24 - 14 August, 2022 =
* Add: Dark mode
* Add: Page title settings
* Add: Absolute & sticky header support
* Add: Multi column sub menus
* Fix: Blog patterns
* Update: Smooth scroll media query
* Remove: Template part settings

= 0.0.23 - 2 August, 2022 =
* Fix: Navigation blocks in patterns

= 0.0.22 - 2 August, 2022 =
* Fix: Patterns for release
* Add: TGMPA for Gutenberg
* Update: Screenshot

= 0.0.21 - 2 August, 2022 =
* Remove: Theme URI
* Remove: Emoji scripts
* Add: SVG image icons
* Fix: Filter and transform settings
* Update: Move languages
* Update: Move scripts to assets dir

= 0.0.20 - 27 July, 2022 =
* Fix: Box shadow bug

= 0.0.19 - 27 July, 2022 =
* Fix: Site editor assets
* Add: Border custom property
* Remove: Unused functions

= 0.0.18 - 27 July, 2022 =
* Fix: Missing block editor styles
* Fix: Layout defaults

= 0.0.17 - 26 July, 2022 =
* Fix: Button errors in patterns
* Fix: Responsive styles
* Remove: Unused style.css

= 0.0.16 - 26 July, 2022 =
* Remove: _MACOSX folder

= 0.0.15 - 26 July, 2022 =
* Remove: TGMPA
* Remove: Gutenberg as dependency
* Fix: Patterns

= 0.0.14 - 26 July, 2022 =
* Remove: Plugin dependency
* Remove: Logo Ipsum logos
* Add: Theme settings
* Add: Page patterns
* Update: Theme URI
* Fix: Pattern dummy content
* Fix: Reverse on mobile
* Fix: Navigation block font size
* Fix: Image block CSS

= 0.0.13 - 24 July, 2022 =
* Update: Recommend plugins instead of require
* Fix: Block validation errors (remove plugin blocks)
* Update: Screenshot image text
* Remove: Auto insert nav menu
* Add: License information
* Add: Link underline styling

= 0.0.12 - 22 July, 2022 =
* Add: FAQ 2 pattern
* Update: About page pattern, hero 2 pattern
* Fix: Button element Gutenberg issue

= 0.0.11 - July 19, 2022 =
* Add: Metrics 2 pattern
* Add: Copyright in readme
* Update: Screenshot.png
* Fix: Version number in changelog

= 0.0.10 - July 19, 2022 =
* Add: Nofollow link on pattern previews

= 0.0.9 - July 19, 2022 =
* Add: Pattern previews
* Update: Block pattern directory

= 0.0.8 - July 15, 2022 =
* Fix: RSS feed error

= 0.0.7 - July 15, 2022 =
* Fix: Default navigation block URLs
* Remove: CSS
* Remove: Includes directory

= 0.0.6 - July 14, 2022 =
* Add: Move CSS to theme
* Add: Includes directory

= 0.0.5 - July 14, 2022 =
* Add: Demo content and images
* Fix: Local URLs in patterns

= 0.0.4 - July 14, 2022 =
* Fix: Theme review feedback

= 0.0.3 - July 13, 2022 =
* Remove: Theme activation redirect

= 0.0.2 - June 12, 2022 =
* First submission

= 0.0.1 - March 23, 2022 =
* Initial release
