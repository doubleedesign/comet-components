# WordPress usage

## Quick Links

### Prerequisites

- [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/)
- [Block Supports Extended](https://github.com/humanmade/block-supports-extended)
- [Breadcrumbs](https://github.com/doubleedesign/doublee-breadcrumbs) (optional, but required to enable breadcrumbs in the
    `PageHeader` component)

### Comet Essentials
- [Comet Components plugin](https://github.com/doubleedesign/comet-components/tree/master/packages/comet-plugin)
- [Comet Canvas theme](https://github.com/doubleedesign/comet-components/tree/master/packages/comet-canvas)

### Comet Add-Ons
- [Comet Table Block](https://github.com/doubleedesign/comet-table-block)
- Comet Calendar (coming soon)

## Plugins and parent theme

### Comet Components plugin

The Comet Components plugin provides:

- implementations of WordPress core blocks listed in its `block-support.json` file
- additional blocks and variations that correspond to Comet components such as `Container`, `Call-to-Action`, `Callout`,
  `FileGroup`, `Accordion`, and `Tabs`
- support for using Comet Components for reusable blocks (synced patterns), such as a call-to-action used in multiple
  places on a site.

Comet Components blocks utilise core blocks and options where practical, but some blocks do use ACF Pro where it
provides a better user experience. You can use Comet without ACF, you'll just be missing some blocks.

### Comet Canvas theme

The Comet Canvas theme is designed to be used as a parent theme and provides:

- Common navigation menu configuration
- Implementations of Comet's `SiteHeader`, `PageHeader`, and `SiteFooter` components (with relevant inner components
  like `Menu` and `Breadcrumbs`) in the relevant files as per the WordPress template hierarchy
- Injection of theme colours from `theme.json` as embedded CSS variables in the `<head>`
- Enqueueing of theme `style.css` files in the block editor and front-end
- Site Health checks for required and recommended plugins.

## Supporting plugins

See [Quick Links](#quick-links) above for required and recommended plugins.

I also always use Comet along with my [Base plugin](https://github.com/doubleedesign/doublee-base-plugin) which provides
some admin customisations, site "Global Options", and other features and customisations I commonly need for client
sites. It shouldn't be essential but some blocks and template parts will be missing things without it.

## With custom ACF Blocks

When creating a new block for a client site, I usually:

- use [ACF Blocks](https://www.advancedcustomfields.com/resources/blocks/) to meet the particular requirements of that
  client[^1]
- set at least one parent block - usually a layout or content grouping component such as `Container`, `Column`, `Group`,
  or `PanelContent`
- set the `renderTemplate` in `block.json` and convert the ACF field data to Comet component instances rendered in that
  file
- prefix the block name with a client-specific name, e.g., `karilee/classes`
- use API version 2 in `block.json`, because version 3 is known to cause some problems with preview mode.

These blocks to be accounted for in the `render_block` and `process_innerblocks` methods in the Comet plugin's
`BlockRenderer` class, ensuring that the `PreprocessedHTML` component is used to render them.
Otherwise, they will either render nothing at all, or will throw a `RuntimeException` because there is no matching
component.
In most cases, this is already taken care of by those methods checking for prefixes other than `core/` and `comet/`. I
just wanted to note it here because it caused some seriously head-scratching silent failures early on and is likely to
again if there are situations I have not accounted for yet.

[^1]: If the block is generic enough that it can be created with simple core/Comet block combinations, there's a good
chance it can go in the Comet plugin for use by all clients.

## With custom WordPress blocks

### Saving with minimal markup

When creating blocks without ACF i.e., with core `edit` and `save` functions, there are some key things to know:

1. The `edit` function determines what is shown in the editor, so you should try to mimic the Comet Components output as
   closely as possible for ease of styling and consistency between the front and back-ends.
2. There are two approaches to the `save` function. You can either:
    - return the same HTML as the `edit` function, which will be saved as-is in the post content, or
    - use a very minimal `save` function to let the Comet PHP take care of all front-end rendering.

I have opted for option 2 because it's less code duplication and less prone to hard-to-debug inconsistencies, and
prevents "rogue" classes from being added to the output.

For example, this...

```javascript
save: ({attributes}) => {
	return createElement(
		'section',
		useBlockProps.save({
			className: 'banner'
		}),
		createElement(
			'div',
			{className: 'banner__overlay'},
		),
		createElement(
			'div',
			{className: 'banner__content'},
			createElement(InnerBlocks.Content)
		)
	);
}
```

...somehow brings with it WordPress's built-in utility classes, such as `banner__content` having
`is-content-justification-left is-layout-constrained wp-container-comet-banner-is-layout-1 wp-block-comet-banner-is-layout-constrained`
added to it, which I don't want.

Instead, I use a minimal `save` function like this:

```javascript
save: () => {
	return createElement(
		'section',
		useBlockProps.save(),
		createElement(InnerBlocks.Content)
	);
}
```

...which saves a minimal version of the block and allows me more control over the output from my PHP code.

There are valid reasons to choose option 1 - having the code in the database closely match the PHP-driven output could
be a useful fallback; it's just not the approach I have chosen because I am prioritising predictability, clean markup,
minimal code duplication.

