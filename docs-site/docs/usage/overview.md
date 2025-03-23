---
title: Overview
---

# Usage overview

:::note
This section, including the Theming and Extending pages, details how to use Comet Components in your own code. If you're looking for information on using the WordPress plugin in the block editor, see the [WordPress usage page](./wordpress.md).
:::

[[toc]]

## PHP object syntax

This is the original syntax that components are developed with, and the syntax that the WordPress plugin uses to render components on the front-end.

Under the hood, the `render` method calls the `BladeService` to render the component using a [Blade](https://laravel.com/docs/8.x/blade) template.

```php
use Doubleedesign\Comet\Core\{Container, Heading};

$attributes = [
	'size' => 'narrow',
	'backgroundColor' => 'light'
];

$component = new Container([$attributes], [)
	new Heading([], 'Hello world!'),
]);

$component->render();
```

## Tycho template syntax

This is essentially XML/JSX-style convenience syntax whereby [heredoc strings](https://www.php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc) are processed by a custom parser and sent through to the object syntax and hence Blade under the hood. 

:::note
Tycho template syntax / the `TychoService` class is a custom creation, not an existing third-party template engine or library. Rather than calling it something generic like `TemplateService`, for fun I went with continuing the pet-based alliteration and named it after my other dog, Tycho.
:::

::: warning
This option is still in active development and is not yet thoroughly tested.
:::

The below example renders the same output as the above object syntax:

```php
use Doubleedesign\Comet\Core\{TychoService};

$template = <<<TYCHO
<Container size="narrow" backgroundColor="light">
    <Heading>Hello world!</Heading>
</Container>
TYCHO;

$component = TychoService::parse($template);
$component->render();
```
