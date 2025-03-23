---
title: HTML, CSS, and SCSS
---

# HTML and CSS structure and approach

[[toc]]

## HTML and CSS

Atomic design and component-scoped styling carries a risk of having to write styles for absolutely everything, which leads to duplication and inconsistency - or
elements needing way too many class names to style them. Sometimes we forget what the C stands for in CSS!

A core principle of Comet Components' HTML construction and associated CSS is balancing the benefits of sensible defaults and shared styles with that of
component-scoped styling. None of the components are truly standalone in their styling: All require the `global` CSS file, which provides core foundations; but
at the same time, component-specific styles are located in the component's own CSS file and are scoped using [BEM](https://getbem.com/) methodology.

### BEM class naming

:::warning
// TODO: Details to come
:::

### Data attributes

:::warning
// TODO: Details to come
:::

## Sass development approach

Vanilla CSS now has a lot of the features that SCSS was created to provide, such as variables and nesting, and even some
colour functions like `lighten` and `darken` can be replicated without SCSS now. Nonetheless, I am using it within the
`core` package for conveniences such as mixins, loops, and neat [BEM](https://getbem.com/) class syntax.

However, my goal is that SCSS will only be required when making changes to the CSS of the `core` package, and
implementations (such as WordPress themes) should be able to change things like colours and fonts without an SCSS
compile step being mandatory. Some ways to achieve this are:

- Generic variable naming (e.g., `primary` not `blue`, based on a colour's place in a brand/palette, not on what colour
  it actually is)
- Using CSS variables (custom properties) where possible, not SCSS variables
- If creating colour functions, they should compile down to vanilla CSS that uses the CSS variables within the output (
  e.g., `lighten` should compile to CSS `color-mix` with a CSS variable in it, not the hex code of the lightened colour
  result)

