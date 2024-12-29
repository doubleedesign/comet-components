<?php
namespace Doubleedesign\Comet\Components;

abstract class CoreAttributes {
	/* @var array{string: string|int|array|null}[] */
	protected array $rawAttributes = [];
	/* @var string[] */
	protected array $classes = []; // Includes classes for component "style" like accent, lead etc
	protected ?string $id = null;
	protected string|array|null $style = null; // Inline styles
	
	public function __construct(array $attrs) {
		$this->rawAttributes = $attrs;
		$this->classes = isset($attrs['className']) ? explode(' ', $attrs['className']) : [];
		$this->id = isset($attrs['id']) ? Utils::kebab_case($attrs['id']) : null;
		$this->style = $attrs['style'] ?? null;
	}
	
	public function get_id(): ?string {
		return $this->id;
	}
	
	/**
	 * Filter the attributes for later use
	 * @return array<string, string|int|array|null>
	 */
	public function get_filtered_attributes(): array {
		$class_properties = array_keys(get_class_vars(self::class));
		
		// Filter out:
		// 1. attributes that are handled as separate properties in this class
		// 2. nested arrays such as layout and focalPoint (which should be handled elsewhere)
		// 3. attributes that are not valid/supported HTML attributes for the given tag
		// Explicitly keep:
		// 1. attributes that start with 'data-' (custom data attributes)
		return array_filter($this->rawAttributes, function ($key) use ($class_properties) {
			return (// Stuff to filter out
				$key !== 'class' && $key !== 'style' && !in_array($key, $class_properties) && !is_array($this->rawAttributes[$key]) && // Other stuff to keep
				str_starts_with($key, 'data-'));
		}, ARRAY_FILTER_USE_KEY);
	}
	
	/**
	 * Get the valid/supported classes for this component
	 * @return string[]
	 */
	public function get_filtered_classes(): array {
		$redundant_classes = ['is-style-default'];
		
		return array_filter($this->classes, function ($class) use ($redundant_classes) {
			return !in_array($class, $redundant_classes);
		});
	}
}
