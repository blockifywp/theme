# Blockify Theme

[![Packagist](https://img.shields.io/packagist/v/blockify/theme.svg?color=239922&style=popout)](https://packagist.org/packages/blockify/theme)
[![CI Status](https://github.com/blockifywp/theme/actions/workflows/integrate.yml/badge.svg)](https://github.com/blockifywp/theme/actions)
[![License](https://img.shields.io/badge/License-GPLv2-blue.svg)](https://github.com/blockifywp/theme/blob/main/LICENSE)
[![WordPress.org](https://img.shields.io/wordpress/theme/installs/blockify?label=WordPress.org)](https://img.shields.io/wordpress/theme/installs/blockify?label=WordPress.org)

Lightweight full site editing block theme framework. Blockify provides useful
features that help you build modern WordPress sites with blocks. Optimized for
speed - loads zero CSS, JavaScript, image or font files by default.
Customization settings include SVG icons, custom Google Fonts, box shadows,
gradient text, absolute positioning, responsive settings, negative margins and
more. Designed for use with child themes and compatible with most popular
plugins. Blockify is a great starting point and toolkit for building your own
custom block theme.

<img width="600" alt="Blockify full site editing theme screenshot" src="https://github.com/blockifywp/theme/assets/24793388/5d548ffa-03f7-4103-98b6-b37ead143f4c">

## Installation

1. In your site's admin dashboard, go to Appearance > Themes and
   click `Add New`.
2. Type "Blockify" in the search field.
3. Click `Install` and then `Activate` to start using the theme.
4. Navigate to Appearance > Customize in your admin panel and customize to your
   needs.
5. A notice box may appear, recommending you to install the Blockify plugin. You
   can either use it or any other block toolkit.
4. Navigate to Appearance > Editor.
7. With the Site Editor open, click the inserter icon, pick one of the many
   ready-made blocks or patterns then click to insert.
8. Edit the page content as you wish, you can add, remove and customize any of
   the blocks.
9. Enjoy :)

## Features

- **Block Supports API:** Easy to use PHP API for modifying core block supports.
  This allows for conditional block supports, or extra settings for core blocks.
  By default, Blockify enables extra block supports where possible.
- **Block Styles API:** Easy to use PHP API for modifying core block styles that
  usually require JS. Conditional registration supported - for example, only
  register a "Secondary" block style if a secondary color has been defined in
  the theme or editor.
- **Block Extensions:** Additional appearance controls for all blocks including
  box shadows, absolute positioning, CSS transforms, CSS filters and more.
- **Full Site Editing:** Additional page, post and template part settings
  provided to make customizing individual pages easier.
- **SVG Icons:** Inline SVG icons can be created with the image block or as
  inline text. Default icons included are WordPress, Dashicons and Social Icons.
  Also supports custom SVGs. Search for "Icon" in the block inserter.
- **CSS Framework:** Minimal base FSE CSS framework. All CSS files have are
  split and are conditionally loaded only when required by a page. Fixes some
  core CSS issues
- **Local Fonts:** Collection of the most popular variable Google Fonts. Only
  fonts selected in Site Editor > Styles will be loaded.
- **Gradients:** Gradient rich text formats and text block gradient settings.
- **Text Formats** Additional text formats including clear formatting,
  underline, gradients, font sizes and more.
- **Responsive Settings:** Reverse on mobile, hide on mobile and more.
- **Header Styles:** Support for absolute, transparent and sticky headers.
- **Mega Menu:** Create simple, multi-column dropdown menus using the core
  submenu block.
- **Search Toggle:** Full screen, CSS-only, search form toggle.
- **Dark Mode:** Automatically enables dark mode for any supported theme. Dark
  mode can be deactivated from the Blockify settings available in the page
  editor.
- **eCommerce Support (coming soon!):** Full support coming for both WooCommerce
  and Easy Digital Downloads.
- **Block Library:** Blockify should work out of the box with any block library
  plugin, and a free Block Library plugin is also available for download on
  both [WordPress.org](https://wordpress.org/plugins/blockify)
  and [GitHub](https://github.com/blockifywp/plugin).

## Requirements

- WordPress ^6.3
- PHP ^7.4

## Contributing

All contributions and questions are welcome. Please feel free to submit GitHub
issues and pull request. All feature requests will be considered.

## Support

Visit [https://github.com/blockifywp/theme/issues](https://github.com/blockifywp/theme/issues)
to submit a support ticket.
