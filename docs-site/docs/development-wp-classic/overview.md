---
title: Comet plugins and theme
position: 0
---

# WordPress integration development (ClassicPress / Classic Editor + ACF Pro)

:::info
This section of the docs covers developing the Comet Components suite of WordPress plugins (Comet Components for ACF Flexible Modules) themselves.

For guidance on implementing Comet Components in your own plugins and themes, see the [Custom Plugins and Themes](./custom-plugins-themes.md) page.
:::

[[toc]]

## Prerequisites
- [Composer](https://getcomposer.org/) installed on your machine
- Local WordPress or ClassicPress installation to use for testing
- [ACF Pro](https://www.advancedcustomfields.com/pro/) installed and activated in your local WordPress/ClassicPress installation
- If using WordPress, the [Classic Editor](https://wordpress.org/plugins/classic-editor/) plugin installed and activated in your local WordPress installation
- [Local dev setup](../development-core/setup.md) for the Comet Components project as a whole
- The [Comet Components monorepo](https://github.com/doubleedesign/comet-components) cloned to your local machine and open in your IDE
- Sufficient permissions to create symlinks.

:::warning
Make sure any [PhpStorm file watchers](../local-dev-deep-dives/tooling-guides/phpstorm.md#file-watchers) you have for your WordPress site are scoped to your own plugin and theme. If you leave them scoped to the entire project, they'll try to compile the various Comet packages' assets, which you don't need it to do _and_ won't work because of file path differences.
:::

:::warning
This documentation is incomplete, sorry!
:::
