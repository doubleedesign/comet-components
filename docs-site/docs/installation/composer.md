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

:::tip
Dev documentation on how to create a standalone package for a component can be found in [Development (Core) -> Standalone Component Packages](../development-core/standalone-packages.md).
:::


