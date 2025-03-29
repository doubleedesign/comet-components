---
title: WordPress
---

# Setup for WordPress

[[toc]]

## Quick Links

### Prerequisites

- [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/)
- [Block Supports Extended](https://github.com/humanmade/block-supports-extended)
- [Breadcrumbs](https://github.com/doubleedesign/doublee-breadcrumbs) (optional, but required to enable breadcrumbs in the `PageHeader` component)

### Comet Essentials

- [Comet Components plugin](https://github.com/doubleedesign/comet-components/tree/master/packages/comet-plugin)
- [Comet Canvas theme](https://github.com/doubleedesign/comet-components/tree/master/packages/comet-canvas)

### Comet Add-Ons

- [Comet Table Block](https://github.com/doubleedesign/comet-table-block)
- Comet Calendar (coming soon)

## Plugins and parent theme

For best results, use the plugin along with the Comet Canvas theme as a parent theme, and your project's theme as a child theme.

### Comet Components plugin

The Comet Components plugin provides:

- implementations of WordPress core blocks listed in its `block-support.json` file
- additional blocks and variations that correspond to Comet components such as `Container`, `Call-to-Action`, `Callout`,  `FileGroup`, `Accordion`, and `Tabs`
- support for using Comet Components for reusable blocks (synced patterns), such as a call-to-action used in multiple  places on a site.

Comet Components blocks utilise core blocks and options where practical, but some blocks do use ACF Pro where it provides a better user experience. You can use Comet without ACF, you'll just be missing some blocks.

### Comet Canvas theme

The Comet Canvas theme is designed to be used as a parent theme and provides:

- Common navigation menu configuration
- Implementations of Comet's `SiteHeader`, `PageHeader`, and `SiteFooter` components (with relevant inner components  like `Menu` and `Breadcrumbs`) in the relevant files as per the WordPress template hierarchy
- Injection of theme colours from `theme.json` as embedded CSS variables in the `<head>`
- Enqueueing of theme `style.css` files in the block editor and front-end
- Site Health checks for required and recommended plugins.

## Supporting plugins

See [Quick Links](#quick-links) above for required and recommended plugins.

I also always use Comet along with my [Base plugin](https://github.com/doubleedesign/doublee-base-plugin) which provides some admin customisations, site "Global Options", and other features and customisations I commonly need for client sites. It shouldn't be essential but some blocks and template parts will be missing things without it.

