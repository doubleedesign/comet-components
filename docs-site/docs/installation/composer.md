---
title: Composer
position: 1
---

# Setup in a PHP project using Composer

## Core library

You can install Comet Components Core into any PHP project using Composer:

```bash
composer require doubleedesign/comet-components-core
```

Or by adding to the `require` section of an existing `composer.json`:

```json
{
	"require": {
		"doubleedesign/comet-components-core": "*"
	}
}
```

## Standalone packages

The core library contains a lot of components, some of which require additional dependencies. If you only need a small number of specific components, that can be unnecessarily bloated.

The following components are available as standalone packages, published on Packagist:
- [Responsive Panels](https://packagist.org/packages/doubleedesign/comet-responsive-panels)
- [FileGroup and File](https://packagist.org/packages/doubleedesign/comet-file-group)

You can always find the latest available packages on the author's [Packagist profile](https://packagist.org/packages/doubleedesign/).

:::tip
Dev documentation on how to create a standalone package for a component can be found in [Development (Core) -> Standalone Component Packages](../development-core/standalone-packages.md).
:::

The standalone packages have one key dependency - the Comet "Launchpad" package, which contains the foundational classes and global CSS, and the dependencies for using Blade templates. This is so that if you use multiple standalone packages in your project, you don't end up with unnecessary duplication.

Once you have installed your chosen standalone package(s), you will need to:

1. Ensure your project loads dependencies using the autoloader:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

2. Tell it where to find the Blade templates, as early as possible so the config is there when you attempt to render components. For example:

```php
use Doubleedesign\Comet\Core\Config;

Config::set_blade_component_paths([
    __DIR__ . '\\vendor\\doubleedesign\\comet-responsive-panels\\src',
]);
```

:::info
- You can also add your own custom paths here to override the provided Blade templates.
- For a WordPress plugin, I place the above code in the root plugin file, right after the autoloader is included.
- If you are adding to a WordPress theme, the `BladeService` is already configured to look in your active theme for files in a `components` directory in your theme root.
  :::

3. Load the core global CSS and JS assets for the component(s) into your project, unless you are going to account of for the classes and attributes in it in your own CSS. The file path from the project root will be:

```
/vendor/doubleedesign/comet-components-launchpad/src/components/global.css
```

4. Load the component-specific CSS and JS assets for the component(s) you are using. Example file paths are:
```
/vendor/doubleedesign/comet-responsive-panels/src/components/responsive-panels.css
/vendor/doubleedesign/comet-responsive-panels/src/components/responsive-panels.js
```

JS files also needs to be specified as a module, for example:

```html

<script type="module" src="/vendor/doubleedesign/comet-responsive-panels/src/components/responsive-panels.js"></script>
```

:::important
Refer to the `README.md` file in the standalone package for any additional setup instructions specific to that package.
:::

