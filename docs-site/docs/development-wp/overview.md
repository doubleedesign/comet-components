---
position: 0
---

# Overview

:::info
This section of the docs covers developing the Comet Components suite of WordPress plugins (Comet Plugin, Comet Calendar, Comet Table Block, etc.) themselves.

For guidance on implementing Comet Components in your own plugins and themes, see the [Wordpress usage](../usage/wordpress.md) section.
:::

[[toc]]

## Prerequisites

- Local WordPress installation to use for testing
- [Local dev setup](../development-core/setup.md) for the Comet Components project as a whole.

## Setup

To use your local copy of Comet Components packages in your dev site instead of the published versions that Composer will install by default, use symbolic links (symlinks).

:::warning
// TODO details to come
- Composer file update to use symlink
- Local symlink using PowerShell
  :::

:::warning
Make sure any [PhpStorm file watchers](../local-dev-deep-dives/tooling-guides/phpstorm.md#file-watchers) you have for your WordPress site are scoped to your own plugin and theme. If you leave them scoped to the entire project, they'll try to compile the various Comet packages' assets, which you don't need it to do _and_ won't work because of file path differences.
:::
