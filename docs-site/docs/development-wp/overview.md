---
title: Comet plugins and theme
position: 0
---

# WordPress integration development

:::info
This section of the docs covers developing the Comet Components suite of WordPress plugins (Comet Plugin, Comet Calendar) themselves.

For guidance on implementing Comet Components in your own plugins and themes, see the [Custom Plugins and Themes](./custom-plugins-themes.md) page.
:::

[[toc]]

## Prerequisites

- [Composer](https://getcomposer.org/) installed on your machine
- Local WordPress installation to use for testing
- [Local dev setup](../development-core/setup.md) for the Comet Components project as a whole
- The [Comet Components monorepo](https://github.com/doubleedesign/comet-components) cloned to your local machine and open in your IDE.

:::warning
Make sure any [PhpStorm file watchers](../local-dev-deep-dives/tooling-guides/phpstorm.md#file-watchers) you have for your WordPress site are scoped to your own plugin and theme. If you leave them scoped to the entire project, they'll try to compile the various Comet packages' assets, which you don't need it to do
_and_ won't work because of file path differences.
:::

## Setup

:::tip
My [WordPress Canvas](https://github.com/doubleedesign/wordpress-canvas) project provides a good starting point for setting up a WordPress site for developing and testing Comet Components and other Double-E Design plugins.
:::

To use your local copy of Comet Components packages in your dev site instead of the published versions that Composer will install by default, use symbolic links (symlinks). This is a two-step process:

1. In the `comet-components` project directory, refresh all dependencies with:

   ```bash
   pnpm run refresh:all:dev
   ```

   The `:dev` version of the refresh script uses `composer.local.json` where available, which should be configured to symlink local package usages (e.g., the
   `comet-plugin-blocks` package's installation of `comet-components-core`).

2. In your WordPress site, add a modified version of the [suggested Composer configuration](../installation/wordpress.md) that symlinks to your local copy of the theme and plugins. Refer to my [WordPress Canvas](https://github.com/doubleedesign/wordpress-canvas) repo's `composer.json` file for an example of how to do this.

   Optionally, you can save this as `composer.local.json` and run install/update commands with:

   ```powershell:no-line-numbers
   $env:COMPOSER = "composer.local.json"; composer update --prefer-source
   ```

## Development

- Please see the [Comet Blocks Plugin README](https://github.com/doubleedesign/comet-components/tree/master/packages/comet-plugin-blocks#readme) for the latest information about plugin architecture and development guidance.
