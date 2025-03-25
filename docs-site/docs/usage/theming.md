# Theming

[[toc]]

## Setting a global background colour
:::tip
By setting the global background colour like this, you are making it available to all components at runtime, allowing them to use this context to refine
styling.
:::
::: warning
Simply adding the colour to the body tag manually will not have the full effect, because the PHP component classes will not be aware of it when constructing
component instances.
:::

### General usage

You can set a global background colour for your site using this line of code in an appropriate location.

For a "vanilla" project this could be as simple as calling it at the top of the page:

```php
CometConfig::set_global_background('dark');
```

You then simply need to add the `data-global-background` attribute to the `body` tag:

```php
$globalBackground = CometConfig::get_global_background();
?>
<body data-global-background="<?php echo $globalBackground; ?>">
```

### In WordPress

In WordPress, themes (other than Comet Canvas) don't generally have direct access to the `CometConfig` class, unless you have installed the Comet Components
core library yourself. Comet Canvas provides a filter to handle this in child themes in `functions.php` like so:

```php
add_filter('comet_canvas_global_background', fn() => 'dark', 20);
```

:::warning
The string must be a colour name (not a hex code) matching the value of a `ThemeColor`.
:::

## Setting colours, fonts, etc

### In WordPress
- Set colours and gradients in your theme's `theme.json` file. WordPress will automatically pick up on these for use in the editor, and the Comet Canvas theme
  will inject them as CSS variables in the relevant places (overriding the Comet default ones).
- Set fonts with the `--font-family-body` and `--font-family-accent` CSS variables in your theme's `style.css`.
- Override any of the other default Comet CSS variables (e.g., spacing, container sizes) in your theme's `style.css`.
- If required, override the global background colour as per the above in your theme's `functions.php` file.

### In other projects
- Set colours and fonts by overriding CSS variables in your own `style.css` file.

## Adding animation
:::warning
// TODO examples of Animate-Into-View, Hover.css, etc
:::
