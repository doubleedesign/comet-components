---
title: WordPress - Classic
position: 2
---

# Setup for WordPress - Classic Editor + ACF Pro

:::tip
This section is also applicable to [ClassicPress](https://classicpress.net/) usage. You will still need ACF Pro, but you don't need the Classic Editor plugin.
:::

[[toc]]

## Prerequisites

- [Composer](https://getcomposer.org/) installed on your machine
- WordPress site installed and running locally
- [Classic Editor plugin](https://wordpress.org/plugins/classic-editor/) installed and active on your local site
- [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/) installed and active on your local site.

## Package details

:::details Comet Components for ACF Flexible Content plugin <Badge type="tip" text="Essential" vertical="middle" />
The Comet Components for ACF Flexible content plugin provides modules that correspond to selected Comet components, so you can use the ACF "fill in a form" interface to add and configure components in the classic editing experience.
:::

:::details Comet Canvas Classic theme <Badge type="tip" text="Essential" vertical="middle" />
The Comet Canvas theme is designed to be used as a parent theme and provides:

- Common navigation menu configuration
- Implementations of Comet's `SiteHeader` and `SiteFooter` components (with relevant inner components like
  `Menu`) in the relevant files as per the WordPress template hierarchy
- Enqueueing of theme `style.css` files for itself and child themes in the block editor and front-end
- Extendable classes for configuration and functionality that can be overridden in child themes
- Site Health checks for required and recommended plugins.

:::

## Installation

The easiest way to install the Comet Components for ACF plugin, the Comet Classic Canvas theme, and their dependencies is to use [Composer](https://getcomposer.org/) with the below
`composer.json` configuration.

1. Add this file in your project root (the same level as the `wp-content` directory), or update your existing `composer.json` if you have one:

   ```json
   {
	   "name": "your-project-name",
	   "description": "Custom WordPress site",
	   "type": "project",
	   "private": true,
	   "minimum-stability": "dev",
	   "prefer-stable": true,
	   "require": {
		   "php": "^8.3",
		   "doubleedesign/comet-plugin-acf": "dev-master",
		   "doubleedesign/comet-canvas-classic": "dev-master",
		   "composer/installers": "^2.0",
		   "doubleedesign/doublee-base-plugin": "dev-master",
		   "doubleedesign/doublee-breadcrumbs": "dev-main",
           "doubleedesign/doublee-tinymce": "dev-main",
           "doubleedesign/acf-advanced-image-field": "dev-main"
	   },
	   "config": {
		   "allow-plugins": {
			   "composer/installers": true
		   },
		   "preferred-install": {
			   "doubleedesign/comet-components-core": "dist"
		   }
	   },
	   "repositories": [
		   {
			   "type": "vcs",
			   "url": "https://github.com/doubleedesign/baguetteBox.js"
		   }
	   ],
	   "extra": {
		   "installer-paths": {
			   "wp-content/plugins/{$name}/": [
				   "type:wordpress-plugin",
				   "doubleedesign/comet-plugin-acf",				
				   "doubleedesign/doublee-base-plugin",
				   "doubleedesign/doublee-breadcrumbs",
   				   "doubleedesign/doublee-tinymce",
                   "doubleedesign/acf-advanced-image-field"
			   ],
			   "wp-content/themes/{$name}/": [
				   "type:wordpress-theme",
				   "doubleedesign/comet-canvas-classic"
			   ]
		   },
		   "resolve-dependencies": false
	   }
   }
   ```

2. To install the plugins and theme, run the following command in your terminal:
   ```powershell:no-line-numbers
   composer install
   ```

   Or if you're updating an existing installation:
   ```powershell:no-line-numbers
   composer update
   ```

3. It is then recommended to `cd` into each directory and ensure the autoloaders are up to date:

   ```powershell:no-line-numbers
   composer dump-autoload -o
   ```

4. The theme and plugin are configured to look for the core library and its dependencies in the main (`comet-plugin-acf`) plugin's
   `vendor` directory. If you have any issues with dependencies, `cd` into the `comet-plugin-acf` directory and run:

   ```powershell:no-line-numbers
   composer update
   ```
    ```powershell:no-line-numbers
   composer dump-autoload -o
   ```

## What's included

The above Composer configuration installs the below:

:::details Comet essentials

- [Comet Components for ACF Flexible Content plugin](https://github.com/doubleedesign/comet-components/tree/master/packages/comet-plugin-acf)
- [Comet Canvas Classic theme](https://github.com/doubleedesign/comet-components/tree/master/packages/comet-canvas)

:::

:::details Other plugins

- [Double-E Breadcrumbs plugin](https://github.com/doubleedesign/doublee-breadcrumbs) - the Comet themes and plugins are configured to work with this for breadcrumbs in the PageHeader component.
- [Double-E Design Base Plugin](https://github.com/doubleedesign/doublee-base-plugin) - provides site "Global Options", some admin customisations, user role customisations, and other features and customisations commonly used for Double-E Design websites.
- [Double-E TinyMCE](https://github.com/doubleedesign/doublee-tinymce) - provides customised configurations and plugins for TinyMCE fields and the Classic Editor. Plugins include the ability to add a button group, callout, pullquote, or table to TinyMCE-driven content fields. Highly recommended for the as-intended content editing experience.
  Add-ons such as Comet Calendar have not yet been updated to support the Classic setup.
- [Advanced Image Field for ACF by Double-E Design](https://github.com/doubleedesign/acf-advanced-image-field) - Comet's flexible content modules are designed to utilise this plugin for enhanced image field functionality, including aspect ratio and focal point cropping options.

## Troubleshooting

:::details Latest version not installing

1. Delete the folder of the plugin or theme you are trying to update
2. Run `composer clear-cache`
3. Run `composer update --prefer-source`
   :::
