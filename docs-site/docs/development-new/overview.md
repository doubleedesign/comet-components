---
position: 0
---

# Overview

:::info Definition
An **implementation** of Comet Components is an integration for a Content Management System (CMS) or other platform that allows the components to be used in that system. An implementation includes the core library (installed as a Composer dependency), any necessary plugins, and the platform-specific code required to make the components work in that system.
:::

This page provides an overview of the elements required to integrate Comet Components into a CMS or other system.

[[toc]]

## Initial setup
Create your implementation as a Composer package with the Comet Components core library as a dependency. The basics of what to include in your `composer.json` are:

```json
{
	"name": "namespace/your-implementation-name",
	"require": {
		"doubleedesign/comet-components-core": "*"
	}
}
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

## Theming support

Implementations need a way for their usages to apply their own CSS. At a minimum, this is the ability to have a custom stylesheet that would contain CSS variables that match what Comet expects, but many implementations will have more complex requirements.

For example, in the WordPress implementation this is achieved through loading theme stylesheets and some custom code in the Comet Canvas parent theme to load `theme.json` variables as thes CSS variables that Comet Components expects. If your CMS also has a particular way that design tokens are set, you will need a way to get that to integrate with Comet.

---
:::warning
// TODO More info to come
:::
