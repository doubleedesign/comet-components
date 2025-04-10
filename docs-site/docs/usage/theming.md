---
position: 1
---

# Theming

[[toc]]

## Global PHP configuration

The `Config` class provides static methods to set and get several options to be made available to components at runtime, such as global background colour and default icon prefix. In general usage, these can be set directly. 

In WordPress, themes (other than Comet Canvas) don't generally have direct access to the `Config` class, unless you have installed the Comet Components core library yourself. Comet Canvas provides filters so that child themes can easily override the defaults from `functions.php`.

### Global background colour

:::important
By setting the global background colour in the Comet config, you are making it available to all components at runtime, allowing them to use this context to refine styling.

Simply adding the colour to the body tag manually will not have the full effect, because the PHP component classes will not be aware of it when constructing component instances.
:::
:::warning
The string must be a colour name (not a hex code) matching the value of a [ThemeColor](../technical-deep-dives/php-architecture/data-types.html#themecolor).
:::

::: details General usage
You can set a global background colour for your site using this line of code in an appropriate location.

For a "vanilla" project this could be as simple as calling it at the top of the page:

```php
Config::set_global_background('dark');
```

You then simply need to add the `data-global-background` attribute to the `body` tag:

```php
<?php
$globalBackground = Config::get_global_background();
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
Config::set_icon_prefix('fa-duotone fa-solid');
```
:::

:::details In WordPress
Comet Canvas provides a filter to override the default icon prefix in your child theme's `functions.php` like so:

```php
add_filter('comet_canvas_default_icon_prefix', fn() => 'fa-light', 20);
```
:::

## Global CSS configuration

Many design tokens are set as CSS variables in Comet's stylesheets, allowing you to easily override them without any special tooling. Sass can be useful for calculating values (such as for the readable colour variables) but it is not essential. You can hit the ground running with a simple vanilla stylesheet that sets the variables you want to change. The full list of tokens can be found in the `:root` selector in [global.css](https://github.com/doubleedesign/comet-components/blob/master/packages/core/src/components/global.css) and in the list below.

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

### Default design tokens (CSS variables)

:::tip
The working proposal for [CSS Color Module Level 6](https://drafts.csswg.org/css-color-6/#colorcontrast) includes bringing colour contrast functions to vanilla CSS. When this is finalised and hits widespread browser support, the HSL and "readable colour" Sass calculations will likely be replaced with these, removing the need to calculate them in SCSS or manually.
:::

:::details Colours

| Colour variable name | Default value                                                                | Readable* | 
|----------------------|------------------------------------------------------------------------------|-----------|
| `--color-primary`    | <span style="background:#845ec2;" class="docs-token-color"></span> `#845ec2` | `white `  | 
| `--color-secondary`  | <span style="background:#00c9a7;" class="docs-token-color"></span> `#00c9a7` | `white `  |
| `--color-accent`     | <span style="background:#ba3caf;" class="docs-token-color"></span> `#ba3caf` | `white `  |
| `--color-info`       | <span style="background:#00d2fc;" class="docs-token-color"></span> `#00d2fc` | `white `  |
| `--color-warning`    | <span style="background:#f9c971;" class="docs-token-color"></span> `#f9c971` | `black `  |
| `--color-success`    | <span style="background:#00c9a7;" class="docs-token-color"></span> `#00c9a7` | `white `  |
| `--color-error`      | <span style="background:#d23e3e;" class="docs-token-color"></span> `#d23e3e` | `white `  |
| `--color-light`      | <span style="background:#f0f0f2;" class="docs-token-color"></span> `#f0f0f2` | `black `  |
| `--color-dark`       | <span style="background:#4b4453;" class="docs-token-color"></span> `#4b4453` | `white `  |

*Corresponding "readable" variable e.g., `--readable-color-primary`.

See [_variables.scss](https://github.com/doubleedesign/comet-components/blob/master/packages/core/src/components/_variables.scss) and [global.scss](https://github.com/doubleedesign/comet-components/blob/master/packages/core/src/components/global.scss) for how the readable colours are calculated for the default colours using Sass. You can copy this into your own SCSS to calculate the overrides for your own colours.
:::

:::details Typography

| Variable name            | Default value |
|--------------------------|---------------|
| `--font-family-body`     | `sans-serif`  |
| `--font-family-accent`   | `sans-serif`  |
| `--font-size-sm`         | `0.875rem`    |
| `--font-weight-light`    | `300`         |
| `--font-weight-regular`  | `400`         |
| `--font-weight-semibold` | `600`         |
| `--font-weight-bold`     | `700`         |

:::

:::details Spacing
The default spacing values use an [augmented fourth scale](https://www.modularscale.com/) from small upwards.

| Variable name   | Default value |                                                                 |
|-----------------|---------------|-----------------------------------------------------------------|
| `--spacing-xxs` | `0.25rem`     | <span style="width:0.25rem" class="docs-token-spacing"></span>  |
| `--spacing-xs`  | `0.5rem`      | <span style="width:0.5rem" class="docs-token-spacing"></span>   |
| `--spacing-sm`  | `0.707rem`    | <span style="width:0.707rem" class="docs-token-spacing"></span> |
| `--spacing-md`  | `1rem`        | <span style="width:1rem" class="docs-token-spacing"></span>     |
| `--spacing-lg`  | `1.414rem`    | <span style="width:1.414rem" class="docs-token-spacing"></span> |
| `--spacing-xl`  | `2rem`        | <span style="width:2rem" class="docs-token-spacing"></span>     |
| `--spacing-xxl` | `2.827rem`    | <span style="width:2.827rem" class="docs-token-spacing"></span> |
:::

:::details Container widths

| Variable name       | Default value |
|---------------------|---------------|
| `--width-wide`      | `1440px`      |
| `--width-contained` | `1100px`      |
| `--width-narrow`    | `960px `      |
| `--width-small`     | `600px`       |
:::

:::details Miscellaneous

| Variable name            | Default value |
|--------------------------|---------------|
| `--button-border-radius` | `0`           |
:::

## Adding animation

:::warning
// TODO examples of Animate-Into-View, Hover.css, etc
:::
