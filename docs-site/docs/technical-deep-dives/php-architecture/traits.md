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
<dt>Method</dt>
<dd>
	<code>get_background_color</code> 
	<strong>Returns:</strong> <code>ThemeColor</code>
	
<p>Get the background colour of the component.</p>
</dd>
<dt>Method</dt>
<dd>
	<code>set_background_color</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p>Allows the background colour of a component to be set based on contextual factors not available at instantiation.</p>
</dd>
<dt>Method</dt>
<dd>
	<code>simplify_all_background_colors</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p>Clean up duplication of background colours between this and its inner components simplify HTML and CSS. Runs either remove_redundant_background_colors() or set_background_color_based_on_children() as appropriate.</p>
</dd>
<dt>Method</dt>
<dd>
	<code>remove_redundant_background_colors</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p>If this component has a background colour set, remove the same background from any children that have it to simplify HTML and CSS.</p>
</dd>
<dt>Method</dt>
<dd>
	<code>set_background_color_based_on_inner_components</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
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


## BlockElementModifier

<dl>

<dt>Property</dt>
<dd>
	<code>bladeFile</code> 
	<strong>Type:</strong> <code>string</code>
	
<p></p>
</dd>

<dt>Method</dt>
<dd>
	<code>init_bem_structure</code> 
	<strong>Returns:</strong> <code>static</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>and_bem</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>set_bem_block</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>set_bem_element</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>set_bem_modifier</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>get_bem_structure</code> 
	<strong>Returns:</strong> <code>array</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>get_bem_classes</code> 
	<strong>Returns:</strong> <code>array</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>get_filtered_classes</code> 
	<strong>Returns:</strong> <code>array</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>get_bem_prefix</code> 
	<strong>Returns:</strong> <code>string</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>get_context</code> 
	<strong>Returns:</strong> <code>?string</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>init_context</code> 
	<strong>Returns:</strong> <code>static</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>with_explicit_context</code> 
	<strong>Returns:</strong> <code>static</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>update_context</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>set_shortname</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>set_shortname_from_blade_file</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>get_shortname</code> 
	<strong>Returns:</strong> <code>string</code>
	
<p></p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use BlockElementModifier;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->init_bem_structure($attributes);
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


## ContextHierarchy

<dl>

<dt>Property</dt>
<dd>
	<code>bladeFile</code> 
	<strong>Type:</strong> <code>string</code>
	
<p></p>
</dd>

<dt>Method</dt>
<dd>
	<code>get_context</code> 
	<strong>Returns:</strong> <code>?string</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>init_context</code> 
	<strong>Returns:</strong> <code>static</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>with_explicit_context</code> 
	<strong>Returns:</strong> <code>static</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>update_context</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use ContextHierarchy;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->get_context($attributes);
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
	<strong>Type:</strong> <code>?string</code>
	
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


## ImageCropProperties

<dl>

<dt>Property</dt>
<dd>
	<code>aspectRatio</code> 
	<strong>Type:</strong> <code>AspectRatio</code>
	
<p>Crop banner image to the given aspect ratio</p>
</dd>
<dt>Property</dt>
<dd>
	<code>focalPoint</code> 
	<strong>Type:</strong> <code>?array</code>
	
<p>The focal point of the image to use when cropping - x and y values between 0 and 100</p>
</dd>
<dt>Property</dt>
<dd>
	<code>offset</code> 
	<strong>Type:</strong> <code>?array</code>
	
<p>The percentage offsets of the image to use when cropping</p>
</dd>

<dt>Method</dt>
<dd>
	<code>set_aspect_ratio_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>set_focal_point_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>set_image_offset_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>get_local_css_properties</code> 
	<strong>Returns:</strong> <code>string</code>
	
<p></p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use ImageCropProperties;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_aspect_ratio_from_attrs($attributes);
	}
}
```
:::
</div>
<div class="trait-class-doc">

<div>


## LayoutAlignment

<dl>

<dt>Property</dt>
<dd>
	<code>hAlign</code> 
	<strong>Type:</strong> <code>Alignment</code>
	
<p>Horizontal alignment, if applicable</p>
</dd>
<dt>Property</dt>
<dd>
	<code>vAlign</code> 
	<strong>Type:</strong> <code>Alignment</code>
	
<p>Vertical alignment, if applicable</p>
</dd>

<dt>Method</dt>
<dd>
	<code>set_layout_alignment_from_attrs</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use LayoutAlignment;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_layout_alignment_from_attrs($attributes);
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
	
<p>Keyword specifying the relative width of the container for the inner content if the component is not nested inside another layout component. Ignored if the component has an isNested attribute set to true, or other logic determines that it is not nested.</p>
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
	
<p>Orientation of the component content, if applicable</p>
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


## NestedState

<dl>


<dt>Method</dt>
<dd>
	<code>set_is_nested</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>get_is_nested</code> 
	<strong>Returns:</strong> <code>bool</code>
	
<p></p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use NestedState;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_is_nested($attributes);
	}
}
```
:::
</div>
<div class="trait-class-doc">

<div>


## ShortName

<dl>


<dt>Method</dt>
<dd>
	<code>set_shortname</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>set_shortname_from_blade_file</code> 
	<strong>Returns:</strong> <code>void</code>
	
<p></p>
</dd>
<dt>Method</dt>
<dd>
	<code>get_shortname</code> 
	<strong>Returns:</strong> <code>string</code>
	
<p></p>
</dd>
</dl>

</div>

::: note Example usage
```php:no-line-numbers
namespace Doubleedesign\Comet\Core;
class MyComponent {
	use ShortName;
	
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents);
		$this->set_shortname($attributes);
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