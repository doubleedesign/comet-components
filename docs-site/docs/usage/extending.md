# Extending

[[toc]]

## Overriding Blade templates

::: tip
The WordPress plugin will automatically look for Blade templates in your theme to override the existing block component's output. To use this option, your custom template needs to have the same name as the original which will be the component's name in kebab case (e.g. `my-component.blade.php`), and be placed directly in a folder named `components` in your theme's root directory.
:::

For other usages including custom WordPress blocks, you can override the rendering template for a component by passing a custom Blade template path to the component constructor.

:::warning
// TODO examples to come
:::


## Customising component classes
The core component classes are built in an object-oriented fashion, so you can create your own versions of them while keeping the functionality of the original using inheritance.

:::warning
// TODO basic example to come
:::
