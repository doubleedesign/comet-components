# Welcome!

<figure class="comet-photo">

![comet.png](/comet.png)

<figcaption class="comet-photo-caption">
<strong>This is Comet.</strong>I wanted a short and snappy name for this project, alliteration sounds good for that, and he is a good boy.</figcaption>
</figure>

Comet Components is a web UI component library initially developed as an abstraction layer for WordPress blocks, with the intention of being able to use the same components in other projects.

It is built with PHP using an object-oriented architecture where each component is passed an array of `$attributes` and in most cases, either a string of `$content` or an array of component objects called `$innerComponents`.

## Guiding principles
::: details Clean, semantic, accessible HTML
Semantic HTML is a priority in Comet Components, with a focus on accessibility and enabling easy theming while avoiding class name soup. This is achieved through:
- ARIA roles and [BEM](http://getbem.com/) class naming for "what it is" and "what we call it" labelling in the code
- ARIA attributes where possible and practical for "what its current state is"
- custom data attributes for common "how it looks" properties.

If someone hits "view source" on a Comet Components site, I want them to revel in how easy it is to read and understand - while seeing enough selectors to enable custom styling.

<small>And by that I really mean: if certain former teachers, mentors, or colleagues of mine were to hit "view source" or take it for a spin with assistive technologies, I want them to feel a rush of clean-code, design-system-mindset, structural perfectionist, and/or accessibility advocate satisfaction. If any of my former students were to do so, they should see me practising what I preach. :laughing: </small>
:::

::: details Atomic design and reusability
Comet Components provides building blocks that come together to create flexible yet consistent UI design components and patterns. The core library includes both the atomic building blocks and many common patterns.

This approach allows for easy reuse and extension of components while maintaining consistency. Atomic building blocks also enable shared foundational styling and granular automated testing.
:::
::: details Flexibility - theming and customisation
Comet Components is designed to provide sensible defaults to so you can hit the ground running, while also being easy to re-skin by using vanilla CSS variables for core design tokens such as colours, spacing, and typography.

More advanced customisation is enabled through the object-oriented architecture and use of the [Blade](https://laravel.com/docs/12.x/frontend#php-and-blade) templating engine.
:::

## Examples

:::warning
// TODO: Add some basic examples here
:::
