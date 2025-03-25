# Theming

[[toc]]

## Global PHP configuration

The `CometConfig` class provides static methods to set and get several options to be made available to components at runtime, such as global background colour and default icon prefix. In general usage, these can be set directly. In WordPress, themes (other than Comet Canvas) don't generally have direct access to the `CometConfig` class, unless you have installed the Comet Components
core library yourself. Comet Canvas provides filters so that child themes can easily override the defaults from `functions.php`. 

### Global background colour

:::important
By setting the global background colour in the Comet config, you are making it available to all components at runtime, allowing them to use this context to refine
styling.

Simply adding the colour to the body tag manually will not have the full effect, because the PHP component classes will not be aware of it when constructing
component instances.
:::
:::warning
The string must be a colour name (not a hex code) matching the value of a [ThemeColor](../technical-deep-dives/php-architecture/data-types.html#themecolor).
:::

::: details General usage
You can set a global background colour for your site using this line of code in an appropriate location.

For a "vanilla" project this could be as simple as calling it at the top of the page:

```php
CometConfig::set_global_background('dark');
```

You then simply need to add the `data-global-background` attribute to the `body` tag:

```php
<?php
$globalBackground = CometConfig::get_global_background();
?>
<body data-global-background="<?php echo $globalBackground; ?>">
```
:::

:::details In WordPress
Comet Canvas provides a filter to override the default (white) in your child theme's `functions.php` like so:

```php
add_filter('comet_canvas_global_background', fn() => 'dark', 20);
```
:::

### Default icon prefix

Components that render icons do so using the `Icon` trait, which uses two attributes passed from the component `$attributes`: `icon` and an optional `iconPrefix`. These values are usually passed to a HTML `<i>` element as class names in the component's Blade template.

The default `iconPrefix` is Font Awesome's `fa-solid`, but this can be overridden both at the global configuration level and at component level. For example if you want to use `fa-light` by default, but then `fa-solid` for a specific component, you can do so by setting `fa-light` in the global configuration and then passing `fa-solid` in the special component's `$attributes`.

:::details General usage
You can set a default icon prefix for your site using this line of code in an appropriate location:

```php
CometConfig::set_icon_prefix('fa-duotone fa-solid');
```
:::

:::details In WordPress
Comet Canvas provides a filter to override the default icon prefix in your child theme's `functions.php` like so:

```php
add_filter('comet_canvas_default_icon_prefix', fn() => 'fa-light', 20);
```
:::

## Global CSS configuration (colours, fonts, spacing, etc)

Many design tokens are set as CSS variables in Comet's stylesheets, allowing you to easily override them without any special tooling. Sass can be useful for calculating values (such as for the readable colour and HSL variables), but it is not essential. You can hit the ground running with a simple vanilla stylesheet that sets the variables you want to change.

:::important
For WordPress usage, some tokens should be set in your theme's `theme.json` so that they take effect in the editor as well.
:::

:::details General usage
Set colours, fonts, spacing, font weights, and container widths by overriding Comet's CSS variables in your own `style.css` file. For example:
- Set fonts with the `--font-family-body` and `--font-family-accent` variables
- Set colours with the `--color-primary`, `--color-secondary`, etc. variables (unless you are using WordPress - see below).
:::

:::details In WordPress
- Set colours and gradients in your theme's `theme.json` file. WordPress will automatically pick up on these for use in the editor, and the Comet Canvas theme will inject them as CSS variables in the relevant places (overriding the Comet default ones).
- Set fonts and other CSS variables as per the general usage instructions above.
:::

## Adding animation

:::warning
// TODO examples of Animate-Into-View, Hover.css, etc
:::
