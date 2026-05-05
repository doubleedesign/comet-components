---
title: Using in WordPress
position: 3
---

# Using Comet Components in WordPress

Comet Components is designed to be used with the WordPress block editor (also known as Gutenberg), with a range of blocks provided by the Comet core library.

:::important
The Comet Components plugin **disables** core blocks that are not explicitly supported by the Comet library. It also imposes limitations on "parent/child" block relationships to help ensure consistent layouts that follow expected and tested patterns.

If you want to start using Comet Components on an existing site, it is highly recommended to take a backup first and test thoroughly on a local copy of your site, as there is a good chance you will need to replace some existing blocks with Comet equivalents or alternatives, and/or create replacements in your plugin or theme using Comet Components.
:::

[[toc]]

## Installation

See the [installation instructions](../installation/wordpress.md) for details on how to install Comet Components in WordPress.

## Setup

1. Activate the Comet Components plugin and all of its required plugins (which should also be installed if you followed the instructions linked above.
2. Create a child theme of Comet Canvas and activate it.
3. Create a `common.css` file and `functions.php` file and add the required design tokens and hook functions as per the below to customise the available colour combinations.

:::tip
There are more filter hooks available for core configuration and customisation of block rendering than those listed here.

Please see the [Comet Canvas README](https://github.com/doubleedesign/comet-components/tree/master/packages/comet-canvas-blocks#readme) for more information.
:::

### Setting available theme colours

Comet Components supports a specific set of named colours, and gradients named as pairs of those colours. These are used in specific places in the controls provided by the plugin, and the Comet block rendering templates handle the resulting attribute values in the way the Comet component(s) used in the block expect to receive them.

By default, all named colours are available in the base "palette" but the colour controls do some filtering based on assumptions such as which blocks should have the "status" colours.

:::note
As of version 0.9.0, the Comet Components plugin does not look for, use, or account for any values in `theme.json`.
:::

At the time of writing, you can't add new colour names, but you can modify which named colours are available in the base palette in `functions.php` like so:

```php
add_filter('comet_canvas_theme_colours', function ($colours) {
    // Remove "warning" colour throughout
    unset($colours['warning']);

    return $colours;
});
```

You can also modify the available gradients in the base palette:

```php
add_filter('comet_canvas_theme_gradients', function ($gradients) {
    // Note: This can be a ThemeGradient object or a from-to string;
    // similarly the ThemeGradient arguments can be ThemeColor values or strings corresponding to them
    array_push($gradients, new ThemeGradient(ThemeColor::PRIMARY, ThemeColor::WHITE));

    return $gradients;
});
```

In addition, there is a concept of "colour pairs" which are used by the editor controls for certain blocks, narrowing the possible combinations of content "colour theme" and background colours. The PHP code used to register these in the plugin does a colour contrast check and will not register pairs that do not have a contrast ratio of at least 3:1.

By default, the plugin will attempt to register certain colour pairs, but you can modify this (including adding additional pairs) like so:

```php
add_filter('comet_canvas_theme_colour_pairs_maybe', function ($pairs) {
    // Add this pair if it meets contrast requirements
    array_push($pairs, ['primary', 'secondary']);

    // Remove a default pair
    $pairs = array_filter($pairs, function ($pair) {
        return !($pair[0] === 'primary' && $pair[1] === 'light');
    });

    return $pairs;
});
```

There is also the ability to provide component-level overrides of the available colour pairs, which provides the ability to specify a different set of colour pairs for a specific block. This also bypasses the colour contrast check, so should be used with caution (e.g., when the pair has slightly lower than 3:1 contrast but will only contain a large heading, not small body text). This works like so:

```php
add_filter('comet_canvas_colour_pair_overrides', function ($overrides) {
    // Specify which pairs the call-to-action block supports
    $overrides['call-to-action'] = array(
        ['white', 'primary'],
        ['white', 'dark']
    );

    return $overrides;
});
```

### Setting colour and gradient values

The values of all relevant design tokens that Comet Component uses (such as colours and gradients) are defined as CSS variables. There should be no need to hard-code colour values anywhere else for your base theming, because Comet is configured to use `var(--*)` values everywhere.

The WordPress plugin is configured to load a file called `common.css` from your theme into the block editor (both preview and controls) and TinyMCE (for [ACF](https://www.advancedcustomfields.com) WYSIWYG fields and post types using the Classic Editor). Comet Components provides defaults, so you can use its stylesheets as a guide for all available tokens.

An example of setting colour values:

```css
/* common.css */
:root {
    --color-primary: #502595;
    --color-secondary: #845ec2;
    --color-accent: #b01b87;
    --color-info: #24a8e1;
    --color-warning: #ffc75f;
    --color-success: #0abfa0;
    --color-error: #ff775f;
    --color-light: ghostwhite;
    --color-dark: #181825;
    --color-white: #ffffff;
}
```

The default gradients are 50/50 of pairs of these colours vertically. They will automatically adopt your colours as they use the `--color-*` values, not hardcoded colour values. However, you can override these of you want different gradients. For example, to make the `dark-light` gradient a 45% smooth transition, you would add:

```css
/* common.css */
:root {
    /* ... your other colour tokens here **/
    --gradient-dark-light: linear-gradient(45deg,rgba(0, 0, 0, 1) 0%, rgba(255, 255, 255, 1) 100%)
}
```

::: important
The Comet plugin does not load the `common.css` file on the front-end automatically. You can either compile it into your core `style.css` file using Sass or similar, or enqueue it separately in your theme's `functions.php`.
:::

## Customisation

### Setting component-level default values

You can override and add to the Comet blocks' `block.json` attribute definitions by setting "component defaults" at the level of the global Comet `Config` object like so:

```php
add_filter('comet_canvas_component_defaults', function($defaults) {
    $defaults['site-header']['breakpoint'] = '1200px';
    $defaults['gallery']['maxPerRow'] = 4;
    
    return $defaults;
});
```

### Disabling block attributes

You can disable editing of block attributes for any block using a JavaScript filter. The component should then always use its default.

Create a `block-registry.js` file in your theme and enqueue it in `functions.php` like so:

```php
add_action('enqueue_block_editor_assets', function() {
    wp_enqueue_script(
        'clientname-block-registry',
        get_stylesheet_directory_uri() . '/block-registry.js',
        ['wp-hooks', 'wp-blocks', 'wp-dom-ready'],
        filemtime(get_stylesheet_directory() . '/block-registry.js')
    );
});
```

In the JavaScript file, you can remove attributes like so:

```javascript
/* global wp */

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'client-name/customise-comet-attributes',
	(settings, name) => {
		switch (name) {
			case 'comet/banner':
				delete settings.attributes.backgroundOpacity;
				delete settings.attributes.backgroundType;
		}

		return settings;
	}
);
```

:::tip
It is also possible to add attributes this way - you would just also need to override the render template to handle them.
:::

## Creating new blocks

You can use Comet Components to create your own blocks within your own plugin and/or theme. See the [WordPress development](../development-wp/overview.md) page for details on how to set up your local development environment, and the [custom plugins and themes](../development-wp/custom-plugins-themes.md) page for details on how to create your own blocks using Comet Components.
