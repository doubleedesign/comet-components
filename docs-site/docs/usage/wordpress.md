---
title: Using in WordPress
position: 3
---

# Using Comet Components in WordPress

Comet Components is designed to be used with the WordPress block editor (also known as Gutenberg), with a subset of core blocks as well as a range of blocks provided by the Comet Library.

:::important
The Comet Components plugin **disables** core blocks that are not explicitly supported by the Comet library. It also imposes some limitations on "parent/child" block relationships to help ensure consistent layouts that follow expected and tested patterns.

If you want to start using Comet Components on an existing site, it is highly recommended to take a backup first and test thoroughly on a local copy of your site, as there is a good chance you will need to replace some existing blocks with Comet equivalents or alternatives, and/or create replacements in your plugin or theme using Comet Components.
:::

## Installation

See the [installation instructions](../installation/wordpress.md) for details on how to install Comet Components in WordPress.

## Usage

Activate the Comet Components plugin and all of its required plugins (which should also be installed if you followed the instructions linked above). Then go forth and create content in the editor!

## Customisation

You can use Comet Components to create your own blocks within your own plugin and/or theme. See the [WordPress development](../development-wp/overview.md) page for details on how to set up your local development environment, and the [custom plugins and themes](../development-wp/custom-plugins-themes.md) page for details on how to create your own blocks using Comet Components.
