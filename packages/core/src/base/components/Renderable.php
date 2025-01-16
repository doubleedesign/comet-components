<?php
namespace Doubleedesign\Comet\Core;

abstract class Renderable {
	/**
	 * @var array<string, string|int|array|null> $rawAttributes
	 * @description Raw attributes passed to the component constructor as key-value pairs
	 */
	protected array $rawAttributes = [];
	/**
	 * @var Tag|null
	 */
	protected ?Tag $tag = null;
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
	 * @description Inline styles, or in WordPress a style object
	 */
	protected ?array $style = null;
	/**
	 * The dot-delimited path to the Blade template file
	 * @var string
	 */
	protected string $bladeFile;
	protected string $shortName;


	public function __construct(array $attrs, string $bladeFile) {
		$this->rawAttributes = $attrs;
		$this->id = isset($attrs['id']) ? Utils::kebab_case($attrs['id']) : null;
		$this->classes = isset($attrs['className']) ? explode(' ', $attrs['className']) : [];
		$this->style = (isset($attrs['style']) && is_array($attrs['style'])) ? $attrs['style'] : null;
		$this->bladeFile = $bladeFile;
		$this->shortName = array_reverse(explode($this->bladeFile, '.'))[0];
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
		$redundant_classes = ['is-style-default'];

		return array_filter($this->classes, function ($class) use ($redundant_classes) {
			return (
				!in_array($class, $redundant_classes)
				// WordPress sometimes add classes like wp-element-some-really-long-random-string and I don't know why nor do I want it
				&& !str_starts_with('wp-elements-', $class)
			);
		});
	}


	/**
	 * Get the valid/supported HTML attributes for the given tag
	 * @return string[]
	 */
	private function get_valid_html_attributes(): array {
		return $this->tag->get_valid_attributes();
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
		$baseClasses = $this->get_filtered_classes();
		$styles = $this->get_inline_styles();

		$attrs = array_merge(
			$baseAttributes,
			array(
				'id'    => $this->get_id(),
				'class' => implode(' ', $baseClasses),
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

	abstract function get_inline_styles(): array;

	abstract function render(): void;
}
