---
title: Component traits
position: 2
---

# Component Traits

PHP traits are used to provide common implementations of an attribute's conversion from <code>$attributes</code> array element to object field.
This provides a central location for validation logic and documentation, reducing duplication and ensuring consistency.

<div class="trait-class-doc">

<div>

## BackgroundColor

<dl>

<dt>Property</dt>
<dd>
	<code>backgroundColor</code> 
	<strong>Type:</strong> <code>ThemeColor</code>

<p>Background colour keyword</p>
</dd>

<dt>Method</dt>
<dd>
	<code>set_background_color_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>

<p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use BackgroundColor;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_background_color_from_attrs($attributes);
	}
}
```
:::
</div>
<div class="trait-class-doc">

<div>

## ColorTheme

<dl>

<dt>Property</dt>
<dd>
	<code>colorTheme</code> 
	<strong>Type:</strong> <code>ThemeColor</code>

<p>Colour keyword for the fill or outline colour</p>
</dd>

<dt>Method</dt>
<dd>
	<code>set_color_theme_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>

<p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use ColorTheme;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_color_theme_from_attrs($attributes);
	}
}
```
:::
</div>
<div class="trait-class-doc">

<div>

## Icon

<dl>

<dt>Property</dt>
<dd>
	<code>iconPrefix</code> 
	<strong>Type:</strong> <code>string</code>

<p>Icon prefix class name</p>
</dd>
<dt>Property</dt>
<dd>
	<code>icon</code> 
	<strong>Type:</strong> <code>?string</code>

<p>Icon class name</p>
</dd>

<dt>Method</dt>
<dd>
	<code>set_icon_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>

<p></p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use Icon;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_icon_from_attrs($attributes);
	}
}
```
:::
</div>
<div class="trait-class-doc">

<div>

## LayoutAlignmentHorizontal

<dl>

<dt>Property</dt>
<dd>
	<code>hAlign</code> 
	<strong>Type:</strong> <code>Alignment</code>

<p></p>
</dd>

<dt>Method</dt>
<dd>
	<code>set_halign_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>

<p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use LayoutAlignmentHorizontal;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_halign_from_attrs($attributes);
	}
}
```
:::
</div>
<div class="trait-class-doc">

<div>

## LayoutAlignmentVertical

<dl>

<dt>Property</dt>
<dd>
	<code>vAlign</code> 
	<strong>Type:</strong> <code>Alignment</code>

<p></p>
</dd>

<dt>Method</dt>
<dd>
	<code>set_valign_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>

<p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use LayoutAlignmentVertical;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_valign_from_attrs($attributes);
	}
}
```
:::
</div>
<div class="trait-class-doc">

<div>

## LayoutContainerSize

<dl>

<dt>Property</dt>
<dd>
	<code>size</code> 
	<strong>Type:</strong> <code>ContainerSize</code>

<p>Keyword specifying the relative width of the container for the inner content</p>
</dd>

<dt>Method</dt>
<dd>
	<code>set_size_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>

<p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use LayoutContainerSize;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_size_from_attrs($attributes);
	}
}
```
:::
</div>
<div class="trait-class-doc">

<div>

## LayoutOrientation

<dl>

<dt>Property</dt>
<dd>
	<code>orientation</code> 
	<strong>Type:</strong> <code>Orientation</code>

<p></p>
</dd>

<dt>Method</dt>
<dd>
	<code>set_orientation_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>

<p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use LayoutOrientation;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_orientation_from_attrs($attributes);
	}
}
```
:::
</div>
<div class="trait-class-doc">

<div>

## TextAlign

<dl>

<dt>Property</dt>
<dd>
	<code>textAlign</code> 
	<strong>Type:</strong> <code>Alignment</code>

<p></p>
</dd>

<dt>Method</dt>
<dd>
	<code>set_text_align_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>

<p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use TextAlign;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_text_align_from_attrs($attributes);
	}
}
```
:::
</div>
<div class="trait-class-doc">

<div>

## TextColor

<dl>

<dt>Property</dt>
<dd>
	<code>textColor</code> 
	<strong>Type:</strong> <code>ThemeColor</code>

<p></p>
</dd>

<dt>Method</dt>
<dd>
	<code>set_text_color_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>

<p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use TextColor;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_text_color_from_attrs($attributes);
	}
}
```
:::
</div>
