---
title: HTML, CSS, and SCSS
---

# HTML and CSS structure and approach

[[toc]]

## HTML and CSS

Atomic design and component-scoped styling carries a risk of having to write styles for absolutely everything, which leads to duplication and inconsistency - or elements needing way too many class names to style them.

A core principle of Comet Components' HTML construction and associated CSS is balancing the benefits of sensible defaults and shared styles (embracing the C in CSS) with that of component-scoped styling. None of the components are truly standalone in their styling: All require the `global` CSS file, which provides core foundations; but at the same time, component-specific styles are located in the component's own CSS file and are scoped using [BEM](https://getbem.com/) methodology.

While there may be some exceptions for practical reasons, the general guiding principles for how Comet Components' HTML (and by association, CSS) is structured are:

:::details What we call it: BEM class naming
The [Block-Element-Modifier](https://getbem.com/) (BEM) methodology is used for class naming. This is a way of labelling HTML output using class names that:
- makes it clear which component is which when looking at the rendered code
- enables straightforward component-scoped styling and supports reliable JavaScript-based targeting
- enables neat, readable, maintainable Sass nesting.

:::

:::details What it is and its current state: ARIA attributes and roles
As a general rule, if there is an ARIA attribute or role that can describe a component's current state, that is preferred over class naming. For example,
`<a class="menu__item__link" aria-current="page">` is preferred over
`<a class="menu__item__link menu__item__link--current">`.

This is because not only can we grab onto `aria-current` for styling, but it also provides useful information to assistive technologies, search engines, and any other tools for which easily machine-readable output is useful.

This is of course a balancing act with the benefits of BEM for styling and ensuring there are no conflicts, so in some places you will see both a role and a class name. For example, in the `Tabs` component, the `TabListItem` is output as an `li` with both a class name and `role="presentation"`. This is useful from a CSS perspective because we can use the class name to neatly ensure we don't accidentally capture other elements within the `Tabs` that happen to have `role="presentation"`.

:::

:::details What it looks like or how it behaves: Data attributes
In Comet Components, data attributes are used for centralised styling of common, well-scoped, and reasonably generic variation properties (such as background colours) and layout states (such as orientation). The benefits include:
- a single source of sensible defaults and shared styles, which keeps their usage consistent and the CSS maintainable
- those properties are easy to access for JavaScript-based extension and customisation.

This is a minor departure from a full commitment to BEM. For example, for layout behaviour like orientation, instead of
`class="tabs tabs--vertical"`, we use
`class="tabs" data-orientation="vertical"`.

In this example, the data attribute approach means:
- common styling to ensure vertical orientation is not repeated between this and other components
- a custom piece of JavaScript could easily be used to swap the orientation of a component in specific client-side scenarios without risk of impacting other class names and styles.
  :::

:::info
Naturally there is some crossover and potential conflict when applying the above principles, as we strive to balance the goal of concise, functionally-named, maintainable HTML and CSS with practical realities of both document structure and styling requirements.
:::

:::important Practical summary
When deciding on HTML tags, class names and attributes for components, ask yourself:
- what it is, structurally speaking
- how it should be interpreted by assistive technologies (whether that necessitates an ARIA attribute/role or not)
- whether a common data attribute is available for the styling you need (or one should be created)
- whether using ARIA attributes and roles for styling would be sufficiently specific _or_ could have unintended consequences because they wouldn't be specific enough without making a mess of the Sass/CSS (in which case using a class name for styling is better).

And remember:
- if you're using a class name for styling, that doesn't mean you shouldn't also have ARIA attributes or roles on the element. Accessibility and machine-readability is always of paramount importance.
  :::

### Practical examples

The below examples compare the default WordPress output to that of the same block or feature using Comet Components.

:::details Group block with background colour
```html
<!-- WordPress core -->
<div class="wp-block-group has-accent-2-background-color has-background has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
	<p>Group content</p>
</div>
```

```html
<!-- Comet Components -->
<div class="layout-block group" data-background="primary">
	<p>Group content</p>
</div>
```
:::

:::details Buttons block, horizontally centered, vertical orientation, different colours, one solid and one outline style
```html
<!-- WordPress core -->
<div class="wp-block-buttons is-vertical is-content-justification-center is-layout-flex wp-container-core-buttons-is-layout-1 wp-block-buttons-is-layout-flex">
	<div class="wp-block-button">
		<a class="wp-block-button__link has-accent-3-background-color has-background wp-element-button">Button 1</a>
	</div>
	<div class="wp-block-button is-style-outline is-style-outline--2">
		<a class="wp-block-button__link wp-element-button">Button 2</a>
	</div>
</div>
```

```html
<!-- Comet Components -->
<div role="group" class="button-group" data-orientation="vertical" data-halign="center">
	<a class="button button--primary">
		<span>Button 1</span>
	</a>
	<a class="button button--secondary button--secondary--outline">
		<span>Button 2</span>
	</a>
</div>
```
:::

:::details Columns block with two even columns, one with middle vertical alignment
```html
<!-- WordPress core -->
<div class="wp-block-columns is-layout-flex wp-container-core-columns-is-layout-1 wp-block-columns-is-layout-flex">
	<div class="wp-block-column is-vertically-aligned-center is-layout-flow wp-block-column-is-layout-flow">
		<p>Column 1</p>
	</div>
	<div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow">
		<p>Column 2</p>
	</div>
</div>
```
```html
<!-- Comet Components -->
<div class="layout-block columns" data-allow-layout-stacking="true" data-count="2">
	<div class="column columns__column" data-valign="center">
		<div class="column__inner">
			<p>Column 1</p>
		</div>
	</div>
	<div class="column columns__column">
		<div class="column__inner">
			<p>Column 2</p>
		</div>
	</div>
</div>
```
:::

:::details Default output of `wp_nav_menu` vs Comet Canvas's handling + Comet Menu component

```html
<!-- WordPress core -->
<div class="menu-all-pages-container">
	<ul id="menu-all-pages" class="menu">
		<li id="menu-item-2247" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-2247">
			<a href="http://vanilla-playground.local/" aria-current="page">Home</a>
		</li>
		<li id="menu-item-2248" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2248">
			<a href="http://vanilla-playground.local/buttons/">Buttons</a>
		</li>
		<li id="menu-item-2249" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2249">
			<a href="http://vanilla-playground.local/columns/">Columns</a>
		</li>
	</ul>
</div>
```

```html
<!-- Comet Components -->
<nav class="site-footer__menu">
	<ul class="site-footer__menu-list">
		<li class="site-footer__menu-list__item" id="2247">
			<a class="site-footer__menu-list__item__link" aria-current="page" href="http://vanilla-playground.local/">Home</a>
		</li>
		<li class="site-footer__menu-list__item" id="2248">
			<a class="site-footer__menu-list__item__link" href="http://vanilla-playground.local/buttons/">Buttons</a>
		</li>
		<li class="site-footer__menu-list__item" id="2249">
			<a class="site-footer__menu-list__item__link" href="http://vanilla-playground.local/columns/">Columns</a>
		</li>
	</ul>
</nav>
```
:::

## Sass/CSS development approach

Vanilla CSS now has a lot of the features that SCSS was created to provide, such as variables and nesting, and even some colour functions like `lighten` and `darken` can be replicated without SCSS now. Nonetheless, it is used within the `core` package for conveniences such as mixins, loops, and neat [BEM](https://getbem.com/) class syntax.

However, the goal is that SCSS will only be required when making changes to the CSS of the `core` package, and implementations (such as WordPress themes) should be able to change things like colours and fonts without an SCSS compile step being mandatory. Some ways to achieve this are:

:::details Generic variable naming
For example, `primary` not `blue`, based on a colour's place in a brand/palette, not on what colour it actually is. This enables consuming projects to easily apply their own design tokens by simply updating the value, with no need to rename the variable.
:::
:::details CSS variables (custom properties)
Where possible, CSS variables (custom properties) should be used, not SCSS variables. If creating colour functions, they should compile down to vanilla CSS that uses the CSS variables within the output (e.g., `lighten` should compile to CSS `color-mix` with a CSS variable in it, not the hex code of the lightened colour result).
:::


