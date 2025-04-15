---
title: WordPress - Block Editor
---

# Setup for WordPress - Block Editor (Gutenberg)

[[toc]]

## Prerequisites

- [Composer](https://getcomposer.org/) installed on your machine
- WordPress site installed and running locally
- [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/) installed and active on your local site.

## Package details

:::details Comet Components plugin <Badge type="tip" text="Essential" vertical="middle" />
The Comet Components plugin provides:

- implementations of WordPress core blocks listed in its `block-support.json` file
- additional blocks and variations that correspond to Comet components such as `Container`, `Call-to-Action`, `Callout`,  `FileGroup`, `Accordion`, and `Tabs`
- support for using Comet Components for reusable blocks (synced patterns), such as a call-to-action used in multiple places on a site.

Comet Components blocks utilise core blocks and options where practical, but some blocks do use ACF Pro where it provides a better user experience.
:::

:::details Comet Canvas theme <Badge type="tip" text="Essential" vertical="middle" />
The Comet Canvas theme is designed to be used as a parent theme and provides:

- Common navigation menu configuration
- Implementations of Comet's `SiteHeader`, `PageHeader`, and `SiteFooter` components (with relevant inner components like `Menu` and `Breadcrumbs`) in the relevant files as per the WordPress template hierarchy
- Injection of theme colours from `theme.json` as embedded CSS variables in the `<head>`
- Enqueueing of theme `style.css` files in the block editor and front-end
- Site Health checks for required and recommended plugins.

:::

:::details Comet Calendar plugin <Badge type="info" text="Optional" vertical="middle" />

The Comet Calendar provides custom post types, taxonomies, templates, and blocks for managing and displaying event information using Comet Components on the front-end.
:::

:::details Comet Table Block plugin <Badge type="info" text="Optional" vertical="middle" />
Comet Table Block is forked from [Flexible Table Block](https://github.com/t-hamano/flexible-table-block) by Aki Hamano, for a superior table editing experience in the block editor along with using Comet Components for front-end rendering.
:::

## Installation

The easiest way to install the Comet Components plugin and its dependencies is to use [Composer](https://getcomposer.org/) with the below `composer.json` configuration.

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
		   "php": "^8.2",
		   "doubleedesign/comet-calendar": "dev-master",
		   "doubleedesign/comet-plugin": "dev-master",
		   "doubleedesign/comet-table-block": "dev-main",
		   "doubleedesign/comet-canvas": "dev-master",
		   "wpackagist-plugin/gutenberg": "^19.8.0",
		   "composer/installers": "^2.0",
		   "doubleedesign/doublee-base-plugin": "dev-master",
		   "doubleedesign/doublee-breadcrumbs": "dev-main",
		   "humanmade/block-supports-extended": "^0.2.0"
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
			   "type": "composer",
			   "url": "https://wpackagist.org"
		   },
		   {
			   "type": "vcs",
			   "url": "https://github.com/doubleedesign/comet-plugin"
		   },
		   {
			   "type": "vcs",
			   "url": "https://github.com/doubleedesign/comet-calendar"
		   },
		   {
			   "type": "vcs",
			   "url": "https://github.com/doubleedesign/comet-table-block"
		   },
		   {
			   "type": "vcs",
			   "url": "https://github.com/doubleedesign/comet-canvas"
		   },
		   {
			   "type": "vcs",
			   "url": "https://github.com/doubleedesign/doublee-base-plugin"
		   },
		   {
			   "type": "vcs",
			   "url": "https://github.com/doubleedesign/doublee-breadcrumbs"
		   },
		   {
			   "type": "vcs",
			   "url": "https://github.com/humanmade/block-supports-extended"
		   },
		   {
			   "type": "vcs",
			   "url": "https://github.com/doubleedesign/baguetteBox.js"
		   }
	   ],
	   "extra": {
		   "installer-paths": {
			   "wp-content/plugins/{$name}/": [
				   "type:wordpress-plugin",
				   "doubleedesign/comet-calendar",
				   "doubleedesign/comet-plugin",
				   "doubleedesign/comet-table-block",
				   "doubleedesign/doublee-base-plugin",
				   "doubleedesign/doublee-breadcrumbs",
				   "humanmade/block-supports-extended",
				   "wpackagist-plugin/gutenberg"
			   ],
			   "wp-content/themes/{$name}/": [
				   "type:wordpress-theme",
				   "doubleedesign/comet-canvas"
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

4. The theme, Calendar plugin, and Table Block plugin are configured to look for the core library and its dependencies in the main (`comet-plugin`) plugin's `vendor` directory. To install the dependencies, `cd` to the `comet-plugin` directory and run:

   ```powershell:no-line-numbers
   composer install
   ```

:::tip
Steps 3 and 4 (refreshing autoloading and installing dependencies in the plugin) can be consolidated into a single PowerShell script called `composer-postinstall.ps1` placed in your project root.

:::details Streamline steps 3 and 4 with a PowerShell script

```powershell
# composer-postinstall.ps1
Write-Host "Starting Composer refresh script"

composer clear-cache

function Run-Composer {
	param (
		[string]$directory
	)

	Write-Host "Running composer commands in $directory"
	Push-Location $directory

	composer install
	composer dump-autoload -o

	Pop-Location
}

# Directories to run composer commands in
$directories = @(
	"wp-content/plugins/comet-plugin",
	"wp-content/plugins/comet-calendar",
	"wp-content/plugins/comet-table-block",
	"wp-content/themes/comet-canvas"
)

# Save root directory
$ROOT_DIR = Get-Location

# Run composer commands in each directory
foreach ($directory in $directories) {
	$fullPath = Join-Path $ROOT_DIR $directory
	if (Test-Path $fullPath) {
		Run-Composer $fullPath
	}
	else {
		Write-Host "Directory $fullPath does not exist. Skipping."
	}
}

Write-Host "Composer postinstall refresh done."
```

Run it with:
```powershell
.\composer-postinstall.ps1
```
:::

### What's included

The above Composer configuration installs the below:

:::details Comet essentials
- [Comet Components plugin](https://github.com/doubleedesign/comet-components/tree/master/packages/comet-plugin)
- [Comet Canvas theme](https://github.com/doubleedesign/comet-components/tree/master/packages/comet-canvas)
- [Block Supports Extended plugin](https://github.com/humanmade/block-supports-extended) by Human Made
- [Breadcrumbs plugin](https://github.com/doubleedesign/doublee-breadcrumbs)  by Double-E Design
- [Gutenberg plugin](https://github.com/WordPress/gutenberg) for a newer version of the block editor than ships with WordPress core (due to a bug it solves).

Alternatively, you can remove the Gutenberg plugin from the `composer.json` file and install it the traditional way. Similarly, Block Supports Extended and Breadcrumbs can be downloaded from GitHub and installed via SFTP or zip upload if preferred.

:::

:::details Comet add-ons
Remove these from the `composer.json` file if you don't want to use them.
- [Comet Table Block](https://github.com/doubleedesign/comet-table-block)
- [Comet Calendar](https://github.com/doubleedesign/comet-calendar)

:::

:::details Other plugins

- [Double-E Design Base Plugin](https://github.com/doubleedesign/doublee-base-plugin) - provides site "Global Options", some admin customisations, user role customisations, and other features and customisations commonly used for Double-E Design websites.

:::

## Troubleshooting

:::details Latest version not installing
1. Delete the folder of the plugin or theme you are trying to update
2. Run `composer clear-cache`
3. Run `composer update --prefer-source`
4. Run `composer-postinstall.ps1` or equivalent commands as detailed above.

:::
