---
title: Overview
position: 0
---

# PHP Architecture Overview

[[toc]]

## Constructor signatures

:::details Background: WordPress-inspired, but made to be generic
While Comet Components is designed to be platform-agnostic, the fact is that its first purpose is as an abstraction layer for WordPress blocks. This means that the constructor signatures of component classes are shaped by one key part of that equation: The `render()` callback method of WordPress blocks.

The render callback has three parameters:
1. `$attributes`: An associative array of properties. If you come from a React background, this is like the props.
	- That WordPress provides the attributes all in one array is the original reason that the component constructors take them this way rather than as individual named arguments.[^1]
	- In many cases, these are passed through to Comet as-is; in some cases there is some manipulation of the data structure that WordPress provides to simplify it, and/or some addition of attributes that come from somewhere else in the WordPress code.
2. `$content`: The HTML content of the block. This is only used as-is in specific cases.
3. `$block_instance`: The complete instance of the `WP_Block` class for the block being rendered.
	- If the block has more blocks inside it, they can be found in the `inner_blocks` field of the block instance.
	- Use of inner blocks is preferred over the raw HTML content, to ensure that Comet is applied consistently across all blocks it supports.

[^1]: This also worked out pretty well in general, because several components would have very unwieldy constructors if every attribute was passed in individually.

In the Comet Components WordPress plugin, the render callback is "intercepted" in the `BlockRenderer` class, where:
- the correct Comet Component class to use is determined based on the block name (and if there isn't one, there's handling for that too)
- in some cases, block data is slightly manipulated to either be a little more generic (e.g., pulling certain HTML out of the content to make it an attribute) or more specific (such as passing an array of data instead of inner components)
- the relevant Comet component constructor is called to create and return the component object: In most cases, this is an array of attributes and either a string of content (for basic text elements) or an array of child components corresponding to the inner blocks.
  :::

The majority of Comet Component classes have one of two constructor signatures:
```php
function __construct(array $attributes, array $innerComponents)
```
```php
function __construct(array $attributes, string $content)
```

| Parameter          | Type     | Description                                                                |
|--------------------|----------|----------------------------------------------------------------------------|
| `$attributes`      | `array`  | An associative array of attributes that map to the component's properties. |
| `$innerComponents` | `array`  | An array of child components. Used for layout and complex UI components.   |
| `$content`         | `string` | A HTML string to render directly. Used for basic text elements.            |

In some specific cases, such as the Image component, the component only accepts attributes:
```php
function __construct(array $attributes)
```

And in others, such as the Breadcrumbs component, the second parameter is component-specific data that will be processed within the component:
```php
function __construct(array $attributes, array $data)
```

:::tip Project roadmap
A longer-term goal of the Comet Components project is to create plugins/modules/whatever-else-they-are-called for other CMSs. This is a reason that the broadly generic constructor signatures have been maintained: The idea is that regardless of how other CMSs structure their data, we will be able to manipulate it to map it to the arguments described above.
:::

## Class hierarchy

Thanks to the common nature of component constructors and many attribute structures, there is a lot of common code and logic that can be shared between components. This is achieved through the use of a handful of [abstract classes](https://www.php.net/manual/en/language.oop5.abstract.php) that the components extend. See the [Abstract Classes page](./abstract-classes.md) for more details on Comet's abstract classes.

## Traits

Common properties are shared between components using [Traits](https://www.php.net/manual/en/language.oop5.traits.php). This allows the type, inline documentation, and implementation logic to be defined in one place and shared between components. See the [Traits page](./traits.md) for more information on Comet's traits.

## Data types

One limitation of the associative array structure of `$attributes`, especially if you're accustomed to something like TypeScript, is that an associative array is just...an associative array. Specifying expected field names and types is not built-in.

This is worked around using a combination of:
1. specifying the expected and supported attribute names and their types as class properties
2. creation of custom data types for specific attributes using [enums](https://www.php.net/manual/en/language.types.enumerations.php).

See the [Data Types page](./data-types.md) for more information on Comet's data types.

