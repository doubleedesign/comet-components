---
title: WordPress
position: 3
---

# Setup for WordPress

[[toc]]

:::tip
My [WordPress Canvas](https://github.com/doubleedesign/wordpress-canvas) project provides a good starting point for setting up a WordPress site for developing and testing Comet Components and other Double-E Design plugins can be used as a template for new client projects. It already contains all of the configuration listed below.
:::

## Prerequisites

- [Composer](https://getcomposer.org/) installed on your machine
- WordPress site installed and running locally
- [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/) installed and active on your local site.

## Package details

:::details Comet Components plugin <Badge type="tip" text="Essential" vertical="middle" />
The Comet Components plugin provides:

- implementations of WordPress core blocks listed in its `block-support.json` file, as Comet Component versions
- additional blocks and variations that correspond to selected Comet components such as `Container`, `Call-to-Action`, `Callout`,  `FileGroup`, `Accordion`, and
  `Tabs`
- support for using Comet Components for reusable blocks (synced patterns), such as a call-to-action used in multiple places on a site.

Comet Components blocks utilise core blocks and options where practical, but some blocks do use ACF Pro where it provides a better user experience.
:::

:::details Comet Canvas theme <Badge type="tip" text="Essential" vertical="middle" />
The Comet Canvas theme is designed to be used as a parent theme and provides:

- Common navigation menu configuration
- Implementations of Comet's `SiteHeader`, `PageHeader`, and `SiteFooter` components (with relevant inner components like `Menu` and
  `Breadcrumbs`) in the relevant files as per the WordPress template hierarchy
- Injection of theme colours from `theme.json` as embedded CSS variables in the `<head>`
- Enqueueing of theme `style.css` files in the block editor and front-end
- Site Health checks for required and recommended plugins.

:::

:::details Comet Calendar plugin <Badge type="info" text="Optional" vertical="middle" />

The Comet Calendar provides custom post types, taxonomies, templates, and blocks for managing and displaying event information using Comet Components on the front-end.
:::

## Installation

The easiest way to install the Comet Components plugin and its dependencies is to use [Composer](https://getcomposer.org/) with the below `composer.json` configuration.

:::important
This configuration installs the current dev versions of Comet Components and other Double-E Design plugins. It is highly recommended that you update this to the current version used when your site goes live, so if there are breaking changes before you next work on it your project is "pinned" to the known working versions. This enables you to selectively update the plugins at your convenience and find the source of any breaking changes more easily than if you install all the latest versions at once.
:::

1. Add this file in your project root (the same level as the `wp-content` directory), or update your existing `composer.json` if you have one:

   ```json
   {
    "name": "your-name/your-project",
    "description": "Website for My Client",
    "type": "project",
    "minimum-stability": "dev",
    "require": {
        "php": ">=8.4",
        "composer/installers": "^2.0",
        "doubleedesign/comet-canvas-blocks": "dev-master",
        "doubleedesign/comet-plugin-blocks": "dev-master",
        "doubleedesign/comet-calendar": "dev-master",
        "doubleedesign/doublee-breadcrumbs": "dev-main",
        "doubleedesign/doublee-base-plugin": "dev-master",
        "doubleedesign/acf-advanced-image-field": "dev-master",
        "doubleedesign/doublee-tinymce": "dev-master",
        "doubleedesign/doublee-ninja-markup": "dev-master",
        "ext-dom": "*",
        "ext-libxml": "*"
    },
    "require-dev": {
        "doubleedesign/doublee-local-dev": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/doubleedesign/doublee-local-dev.git"
        },
        {
            "type": "vcs",
            "url": "https://github.com/doubleedesign/baguetteBox.js"
        }
    ],
    "extra": {
        "installer-paths": {
            "wp-content/plugins/{$name}/": [
                "type:wordpress-plugin"
            ],
            "wp-content/themes/{$name}/": [
                "type:wordpress-theme"
            ]
        }
    },
    "config": {
        "allow-plugins": {
            "composer/installers": true
        },
        "optimize-autoloader": true
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

4. The theme, and Calendar plugin are configured to look for the core library and its dependencies in the main (`comet-plugin-blocks`) plugin's
   `vendor` directory. To install the dependencies, `cd` to the `comet-plugin-blocks` directory and run:

   ```powershell:no-line-numbers
   composer install
   ```

:::tip
Steps 3 and 4 (refreshing autoloading and installing dependencies in the plugin) can be consolidated into a single PowerShell script called
`composer-postinstall.ps1` placed in your project root.

:::details Streamline steps 3 and 4 with a PowerShell script

```powershell
# composer-postinstall.ps1
Write-Host "Starting Composer refresh script"

composer clear-cache

function Run-Composer
{
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
    "wp-content/plugins/comet-plugin-blocks",
    "wp-content/plugins/comet-calendar",
    "wp-content/themes/comet-canvas-blocks"
)

# Save root directory
$ROOT_DIR = Get-Location

# Run composer commands in each directory
foreach ($directory in $directories)
{
    $fullPath = Join-Path $ROOT_DIR $directory
    if (Test-Path $fullPath)
    {
        Run-Composer $fullPath
    }
    else
    {
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

:::

:::details Comet add-ons
Remove these from the `composer.json` file if you don't want to use them.

- [Comet Calendar](https://github.com/doubleedesign/comet-calendar)

:::

:::details Other plugins

- [Double-E Breadcrumbs plugin](https://github.com/doubleedesign/doublee-breadcrumbs) - the Comet themes and plugins are configured to work with this for breadcrumbs in the PageHeader component.
- [Double-E Design Base Plugin](https://github.com/doubleedesign/doublee-base-plugin) - provides site "Global Options", some admin customisations, user role customisations, and other features and customisations commonly used for Double-E Design websites.
- [Double-E TinyMCE](https://github.com/doubleedesign/doublee-tinymce) - provides customised configurations and plugins for TinyMCE fields and the Classic Editor. Plugins include the ability to add a button group, callout, pullquote, or table to TinyMCE-driven content fields. Highly recommended for the as-intended content editing experience.
- [Advanced Image Field for ACF by Double-E Design](https://github.com/doubleedesign/acf-advanced-image-field) - Comet's flexible content modules are designed to utilise this plugin for enhanced image field functionality, including aspect ratio and focal point cropping options.

:::

## Troubleshooting

:::details Latest version not installing

1. Delete the folder of the plugin or theme you are trying to update
2. Run `composer clear-cache`
3. Run `composer update --prefer-source`
4. Run `composer-postinstall.ps1` or equivalent commands as detailed above.

:::
