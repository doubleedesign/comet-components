<?php
namespace Doubleedesign\Comet\Components;

abstract class CoreAttributes {
    /**
     * @var array<string, string|int|array|null> $rawAttributes
     * @description Raw attributes passed to the component constructor as key-value pairs
     */
	protected array $rawAttributes = [];
    /**
     * @var string|null $id
     * @description Unique identifier for the component instance
     */
    protected ?string $id = null;
    /**
     * @var string|null $style
     * @description Inline styles to be applied to the component HTML
     */
	protected ?string $style = null;


	public function __construct(array $attrs) {
		$this->rawAttributes = $attrs;
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
		// 1. attributes that are handled as separate properties
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

}
