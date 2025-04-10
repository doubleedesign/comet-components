---
position: 0
---

# Overview

:::info Definition
An **implementation** of Comet Components is an integration for a Content Management System (CMS) or other platform that allows the components to be used in that system. An implementation includes the core library (installed as a Composer dependency), any necessary plugins, and the platform-specific code required to make the components work in that system.
:::

This page provides an overview of the elements required to integrate Comet Components into a CMS or other system. This also encompasses the requirements for a one-off integration in a custom projet.

[[toc]]

## Installation

Create your implementation as a Composer package with the Comet Components core library as a dependency. The basics of what to include in your `composer.json` are:

```json
{
	"name": "namespace/your-implementation-name",
	"require": {
		"doubleedesign/comet-components-core": "*"
	}
}
```

Or if you have already set up your Composer configuration, you can install Comet with:

```bash
composer require doubleedesign/comet-components-core
```

## Composer autoloading

Comet Components Core uses Composer's autoloading system to load its classes. This means that to make the component classes available to your implementation, you need to ensure your implementation's autoloader is up-to-date:

```bash
composer dump-autoload -o
```

And is loaded into your project, for example:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

## Setting configuration

Implementations need a way to set the global configuration for the components, which are found in the `CometConfig` class.

The most basic way, using the global background setting as an example, is like so:
```php
CometConfig::set_global_background($color);
```

Depending on the way your CMS works, you may need to do this in a particular place. For example, the Comet Canvas WordPress theme does it in an action hook (and provides a filter for child themes to change it).

:::details What if I don't do this?
Nothing will break - the options will be set to their defaults.
:::

## Loading assets

### Comet's assets
Implementations need a way to load Comet Components Core's CSS and JavaScript files. There are three asset loading methods you can choose from or use a combination of:

:::details Load the bundled CSS and JS
The simplest way to load the assets is by loading the bundled JavaScript and CSS files using whatever method your system uses to put stuff in the document `<head>`.

The paths from your project root (or wherever your Composer `vendor` folder is) are:
```text:no-line-numbers
./vendor/doubleedesign/comet-components-core/dist/dist.js
```
```text:no-line-numbers
./vendor/doubleedesign/comet-components-core/dist/dist.css
```

The JavaScript file's `<script>` tag also requires the following attributes:
1. `type="module"` so that ES module imports work
2. `data-base-path` set to the `comet-components-core` directory so that Vue SFC Loader can find its templates

An example of the result in WordPress is:
```html
<script id="comet-components-js" 
		type="module" 
		src="http://vanilla-playground.local/wp-content/plugins/comet-plugin/vendor/doubleedesign/comet-components-core/dist/dist.js" 
		data-base-path="/wp-content/plugins/comet-plugin/vendor/doubleedesign/comet-components-core">
</script>

```
:::

:::details Load the global CSS + individual CSS and JS files 
Each component is designed to be fairly self-contained in terms of assets, so that you can load just what you need. How you achieve this depends on how your system determines which components are in use.

Some common styling is located in `global.css`, which you will need to load however your system handles loading site-wide CSS. The path from your project root (or wherever your Composer `vendor` folder is) is:
```text:no-line-numbers
./vendor/doubleedesign/comet-components-core/src/components/global.css
```
:::

:::details Your own bundling
The source SCSS and JS files are included in the Composer installation. You can use these to build your own bundle(s) that contain only the components you need. This is a good option if you want to load a single CSS and/or JS file but reduce the size of it by only including the components you need.
:::

### Fonts and icons

Comet Components _supports_ Font Awesome out of the box, but doesn't _provide_ it - projects need to obtain their own licence and link the associated kit. This is similar for any other third-party font or icon library.

If you are intending to use web fonts loaded from Google Fonts, Adobe Fonts or similar; and/or icons using Font Awesome or another icon library that works similarly, you will need a method of loading the files they require into projects using your implementation. For example, in WordPress this is done using the `wp_enqueue_script` and `wp_enqueue_style` functions in themes.

## Theming support

Implementations need a way for their usages to apply their own CSS. At a minimum, this is the ability to have a custom stylesheet that would contain CSS variables that match what Comet expects, but many implementations will have more complex requirements.

For example, in the WordPress implementation this is achieved through loading theme stylesheets and some custom code in the Comet Canvas parent theme to load `theme.json` variables as thes CSS variables that Comet Components expects. If your CMS also has a particular way that design tokens are set, you will need a way to get that to integrate with Comet.
