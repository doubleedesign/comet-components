<?php
namespace Doubleedesign\Comet\Core;
use InvalidArgumentException;

abstract class Renderable {

	/**
	 * @var array<string, string|int|array|null> $rawAttributes
	 * @description Raw attributes passed to the component constructor as key-value pairs
	 */
	private array $rawAttributes;
	/**
	 * @var ?Tag
	 * @description The HTML tag to use for the component
	 */
	protected ?Tag $tagName = Tag::DIV;
	/**
	 * @var string|null $id
	 * @description Unique identifier
	 */
	protected ?string $id;
	/**
	 * @var array<string> $classes
	 * @description CSS classes
	 */
	protected ?array $classes = [];
	/**
	 * @var array|null $style
	 * @description Inline styles
	 */
	protected ?array $style = null;
	/**
	 * The dot-delimited path to the Blade template file
	 * @var string
	 */
	protected string $bladeFile;
	/**
	 * @var string $shortName
	 * @description The name of the component without any namespacing, prefixes, etc. Derived from the Blade filename by default.
	 */
	protected string $shortName;

	public function __construct(array $attributes, string $bladeFile) {
		$this->rawAttributes = $attributes;
		$this->id = isset($attributes['id']) ? Utils::kebab_case($attributes['id']) : null;
		$this->style = (isset($attributes['style']) && is_array($attributes['style'])) ? $attributes['style'] : null;
		$this->bladeFile = $bladeFile;
		$this->shortName = array_reverse(explode('.', $this->bladeFile))[0];

		$classes = [];
		// Handle WordPress block implementation of classes (className string)
		if (isset($attributes['className']) && is_string($attributes['className'])) {
			$classes = explode(' ', $attributes['className']);
		}
		// Handle preferred implementation of classes (array)
		if (isset($attributes['classes']) && is_array($attributes['classes'])) {
			$classes = array_merge($classes, $attributes['classes']);
		}
		$this->classes = $classes;

		// If no tagName is set, default to div
		if (!isset($attributes['tagName'])) {
			$this->tagName = Tag::DIV;
		}
	}

	public function get_id(): ?string {
		return $this->id;
	}

	/**
	 * Get the valid/supported classes for this component
	 * Note: Supported classes noted in docblocks refer to those that have been accounted for in CSS.
	 *       They are not the only valid classes - implementations can add their own.
	 * Note 2: No sanitisation is done here because using htmlspecialchars() + Blade template output resulted in double encoding.
	 *        So let's just let Blade look after it.
	 *
	 * @return array<string>
	 */
	protected function get_filtered_classes(): array {
		$current_classes = $this->classes;
		$redundant_classes = ['is-style-default'];

		return array_filter($current_classes, function ($class) use ($redundant_classes) {
			return !in_array($class, $redundant_classes);
		});
	}

	/**
	 * Get the valid/supported HTML attributes for the given tag
	 * @return array<string>
	 */
	private function get_valid_html_attributes(): array {
		return $this->tagName->get_valid_attributes();
	}

	/**
	 * Filter the attributes for later use
	 * @return array<string, string|int|array|null>
	 */
	private function get_filtered_attributes(): array {
		$class_properties = array_keys(get_class_vars(self::class));

		// Filter out:
		// 1. attributes that are handled as separate properties
		// 2. nested arrays such as layout and focalPoint (which should be handled elsewhere)
		// 3. attributes that are not valid/supported HTML attributes for the given tag
		// Explicitly keep:
		// 1. attributes that start with 'data-' (custom data attributes)
		return array_filter($this->rawAttributes, function ($key) use ($class_properties) {
			return (
				// Stuff to filter out
				$key !== 'class' && $key !== 'style' && !in_array($key, $class_properties) && !is_array($this->rawAttributes[$key]) &&
				// Other stuff to keep - valid attributes for this tag
				in_array($key, $this->get_valid_html_attributes()) ||
				str_starts_with($key, 'data-'));
		}, ARRAY_FILTER_USE_KEY);
	}

	/**
	 * Collect the final HTML attributes excluding "class"
	 * @return array<string,string>
	 */
	protected function get_html_attributes(): array {
		$baseAttributes = $this->get_filtered_attributes();
		$styles = $this->get_inline_styles();

		$attrs = array_merge(
			$baseAttributes,
			array(
				'id'    => $this->get_id(),
				'style' => implode(';',
					array_map(fn($key, $value) => $key . ':' . $value,
						array_keys($styles),
						array_values($styles),
					)
				)
			)
		);

		// Remove any empty attributes before returning
		return array_filter($attrs, fn($value) => !empty($value));
	}

	/**
	 * Build the inline styles (style attribute) as a single string
	 * using the relevant supported attributes
	 * @return array<string, string>
	 */
	protected function get_inline_styles(): array {
		return [];
	}

	abstract function render(): void;
}
