# Comet Canvas (for the WP Block Editor)

WordPress parent theme to support the intended implementation of Comet Components [Comet Components](https://cometcomponents.io) in WordPress (for the block editor).

If you're reading this from GitHub, you're seeing the mirror of the [Comet Components Canvas package](https://github.com/doubleedesign/comet-components/tree/master/packages/comet-canvas) that is here for the purposes of publishing to Packagist and installing via Composer.

Development of this project belongs in the main Comet Components monorepo.

## Child theming

The Comet Blocks plugin and Comet Canvas parent theme are configured to look for styling files in child themes and load them into the editor as follows:

| File           | Purpose                                                                                                                                                                                                       |
|----------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `theme.json`   | Should contain the colour palette definition. Will be used to dynamically load colours as CSS variables in the front-end, block editor, and TinyMCE.                                                          |
| `common.scss`  | Common typography and other styles that should be used on the front-end, in the block editor, and in TinyMCE, should go in this one file.                                                                     |
| `style.scss`   | Required file for WordPress to recognize the theme. Should contain theme metadata and all CSS styles for the theme that are not already present in the parent theme and plugins. Should import `common.scss`. |
| `tinymce.scss` | Styles to be loaded only in TinyMCE. Should not need to import `common.scss` as that should already be loaded.                                                                                                |

### Setting component defaults

There are filters available for child themes to access Comet Components' global configuration:

| Filter                             | Parameters        | Usage                                                                                                                              |
|------------------------------------|-------------------|------------------------------------------------------------------------------------------------------------------------------------|
| `comet_canvas_component_defaults`  | `array $defaults` | Allows setting of various default values per-component, such as colour theme and container size.                                   |
| `comet_canvas_global_background`   | `string $color`   | Allows setting a global background colour for the site. Default is `white`. Valid values must be drawn from the `ThemeColor` type. |
| `comet_canvas_default_icon_prefix` | `string $prefix`  | Allows setting a default icon prefix for all Icon components. Default is `fa-solid`.                                               |
