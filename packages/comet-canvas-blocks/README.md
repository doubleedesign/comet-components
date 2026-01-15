# Comet Canvas (for the WP Block Editor)

WordPress parent theme to support the intended implementation of Comet Components [Comet Components](https://cometcomponents.io) in WordPress (for the block editor).

> [!IMPORTANT]  
> This theme **must
** be used with the [Comet Blocks plugin](https://github.com/doubleedesign/comet-plugin-blocks). The theme itself is kept intentionally minimal, with most functionality provided by the plugin for ease of development and maintenance.

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

### Third-party hosted fonts

To ensure theme fonts loaded from Typekit, Google Fonts, Font Awesome, etc load everywhere they should, you can:

#### For CSS files:

- Import them in your child theme's `common.scss` (recommended as this is already set up to be loaded everywhere needed), ensuring that
  `common.scss` is imported into `style.scss` and `tinymce.scss`
- Enqueue them in the child theme's `functions.php` file on the following action hooks:
    - `wp_enqueue_scripts` for the front-end, using the `wp_enqueue_style` function
    - `enqueue_block_assets` with an admin check (to ensure no duplicate front-end loading) for the block editor
    - `admin_enqueue_scripts` for core TinyMCE, using the `add_editor_style` function
    - `tiny_mce_before_init` for ACF TinyMCE, by adding a CSS `@import` rule to the `content_css` field

#### For JavaScript files:

- Enqueue them in the child theme's `functions.php` file on the following action hooks:
    - `wp_enqueue_scripts` for the front-end, using the `wp_enqueue_script` function
    - `enqueue_block_assets` with an admin check (to ensure no duplicate front-end loading) for the block editor

### Setting component defaults

There are filters available for child themes to access Comet Components' global configuration, including the default values of various component attributes.

| Filter                                  | Parameters        | Usage                                                                                                                                                                                                                                                          |
|-----------------------------------------|-------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `comet_canvas_component_defaults`       | `array $defaults` | Allows setting of various default values per-component, such as colour theme and container size.                                                                                                                                                               |
| `comet_canvas_global_background`        | `string $color`   | Allows setting a global background colour for the site. Default is `white`. Valid values must be drawn from the `ThemeColor` type.                                                                                                                             |
| `comet_canvas_default_icon_prefix`      | `string $prefix`  | Allows setting a default icon prefix for all Icon components. Default is `fa-solid`.                                                                                                                                                                           |
| `comet_canvas_theme_colours`            | `array $colours`  | An alternative or supplementary method of setting theme colours. This filter runs _after_ `theme.json` is used to find the colour palette, so if you use both the filter will win.                                                                             |
| `comet_canvas_theme_colour_pairs_maybe` | `array $pairs`    | Allows setting of accessible colour pairs (foreground/background) for use in components that support them, such as Buttons and Banners. Contains some common defaults. **Note:** If a given pair does not have sufficient contrast, it will not be registered. |

In addition, there are some filters to modify attributes for nested components in the provided blocks. This is to ensure consistency across the theme rather than having backend controls for every possible attribute in every individual use case. These filters are applied in the
`render.php` file for the block, so if a filter isn't listed here you can check that file to see if I've forgotten to document one - or add it. They are also intentionally all prefixed with
`comet_blocks_` for easy searching.

> [!NOTE]  
> The term "related content blocks" refers to the various blocks that display card lists of content from around the site, such as Related pages, Child pages, Latest Posts, and Featured Posts.

| Filter                                                                 | Parameters          | Usage                                                                                                                                                                                                              |
|------------------------------------------------------------------------|---------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `comet_blocks_cta_heading_classes`                                     | `array $classes`    | Add CSS class(es) to the heading in the call-to-action block, e.g., `['is-style-accent']`.                                                                                                                         |
| `comet_blocks_cta_button_group_attributes`                             | `array $attrs`      | Modify the attributes of the Button Group in the call-to-action block, e.g., `['halign' => 'end']`.                                                                                                                |
| `comet_blocks_related_content_card_list_card_as_link`                  | 	  `bool $as_link`  | Set whether the cards in the various related content blocks render as links. Default is `false`, which renders them with a "Read more" button link.                                                                |
| `comet_blocks_banner_heading_classes`                                  | `array $classes`    | Add CSS class(es) to the heading in the Banner block, e.g., `['is-style-accent']`.                                                                                                                                 |
| `comet_blocks_banner_button_group_attributes`                          | `array $attrs`      | Modify the attributes of the Button Group in the Banner block, e.g., `['halign' => 'center']`.                                                                                                                     |
| `comet_blocks_related_content_max_per_row`                             | `int $max_per_row`  | Set the maximum number of cards per row in the dynamic content blocks (Related pages, child pages, latest posts, featured posts, etc) when they are displayed in grid format.                                      |                                               |
| `comet_blocks_related_content_card_list_behaviour_when_fewer_than_max` | `string $behaviour` | Set the behaviour of the related content blocks  when there are fewer items than the maximum per row in grid format. Options are `default` (keep the width of the cards as though the max is there), and `expand`. |

### Modifying WordPress template options

Thirdly, there are some filters available to modify options for the WordPress templates provided by Comet Canvas. These will take priority over component defaults where applicable.

| Filter                                          | Parameters            | Usage                                                                                                                                    |
|-------------------------------------------------|-----------------------|------------------------------------------------------------------------------------------------------------------------------------------|
| `comet_canvas_post_category_list_card_layout`   | `string $card_layout` | Set the card layout in the blog template when displaying categories instead of posts. Default is `list`. Other option is `grid`.         |
| `comet_canvas_post_category_list_cards_per_row` | `int $cards_per_row`  | Set the number of cards per row in the blog template when displaying categories instead of posts, and using `grid` layout. Default is 3. |
| `comet_canvas_post_category_list_card_layout`   | `string $card_layout` | Set the card layout in the blog template when displaying the list of posts. Default is `list`. Other option is `grid`.                   |
| `comet_canvas_posts_loop_cards_per_row`         | `int $cards_per_row`  | Set the number of cards per row in the blog template when displaying the list of posts, and using `grid` layout. Default is 3.           |

### Modifying block field options

There are also some filters available to add or modify options for the ACF fields for some components:

| Filter                          | Parameters      | Usage                                                                                           |
|---------------------------------|-----------------|-------------------------------------------------------------------------------------------------|
| `comet_blocks_separator_styles` | `array $styles` | Add style options for the Separator block. This is an associative array in key => label format. |

### Troubleshooting

#### Blocks not rendering in an iframe in the editor / styles leaking into block previews from WordPress core admin styles

**All** blocks must use
`apiVersion: 3` for any blocks to render in an iframe in the editor. If blocks are not rendered in an iframe, WordPress core admin styles may affect the preview appearance due to CSS leakage. If the editor is not loading blocks in an iframe and styles like
`.wp-core-ui` are affecting the appearance of blocks, check all
`block.json` files in the Comet plugin and theme, client plugin and theme, and third-party plugins that add blocks to ensure they are using
`apiVersion: 3`.

You can override this setting for third-party blocks in `block-registry.js` or an equivalent file in the client plugin. For example:

```javascript
// Use new API version for third-party blocks so that all blocks can use the new iframe-based editor experience
wp.hooks.addFilter('blocks.registerBlockType', 'comet/use-new-block-api', (settings, name) => {
	if (name.startsWith('ninja-forms')) {
		return {
			...settings,
			apiVersion: 3,
		};
	}

	return settings;
});
```

#### Comet or theme styles loaded into the editor affecting the WP admin/editor UI in undesired way

Selector conflicts can happen between the Comet or theme styles and WordPress core admin/editor styles due to Comet having deliberately global styles (like variables on `:root` and styles on `body`) and simple class names (like `.button` and `.card`). I have not been able to find a way to ensure these stylesheets load only for the blocks (in the iframe) and nothing else.

Normal CSS specificity rules apply here of course, but Comet core styles (and theme styles intended for the front-end) should NOT be directly modified to be more specific in ways that refer to the WP admin/editor, or introduce extra layers of prerequisites for styles to work by targeting assumed parent elements and the like.

There are two main ways to deal with this:

1. Wrap theme common styles in a [CSS layer](https://developer.mozilla.org/en-US/docs/Web/CSS/Reference/At-rules/@layer). This ensures that when there is a direct property conflict, styles that are not on a layer (i.e., the WP core admin styles in this case) have precedence. (Comet's `global.css` and `common.css` are already in layers.)
2. Override styles in an admin-specific stylesheet for your theme or plugin. This can be enqueued on the `admin_enqueue_scripts` action hook. This is the less-preferred option because it requires more maintenance and is less future-proof, but is sometimes the most practical option. It is often necessary when the Comet/theme styles explicitly apply a property but the WP core styles do not (because it uses the default); in these cases it is usually best to use property values like `initial`,
   `unset`, or `revert` to reset the property rather than overriding it with a specific value (except for fonts, where `inherit` may be more appropriate).

#### Warning: The tag <accordion> is unrecognized in this browser. If you meant to render a React component, start its name with an uppercase letter.

This error occurs in the block editor with the Comet Components that use VueJS (so not just `<accordion>`), when `SCRIPT_DEBUG` is enabled in
`wp-config.php`. The workaround (until I can find a better solution) is to set `SCRIPT_DEBUG` to `false` when you don't actively need to debug something else.
 

