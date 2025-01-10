<?php
namespace Doubleedesign\Comet\Components;

abstract class CoreElementComponent implements IRenderable {
    /**
     * @var Alignment|null $textAlign
     */
	protected ?Alignment $textAlign = Alignment::START;
	/**
     * @var string $content
     * @description plain text or basic HTML
     */
	protected string $content;
    /**
     * @var array<string> $classes
     * @description CSS classes to be applied to the component HTML
     * @supported-values is-style-accent, is-style-lead
     */
    protected ?array $classes = [];


	function __construct(array $attributes, string $content) {
		$this->content = $content;
        $this->classes = isset($attrs['className']) ? explode(' ', $attrs['className']) : [];
		$this->textAlign = isset($attributes['textAlign']) ? Alignment::tryFrom($attributes['textAlign']) : null;
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


	/**
	 * Build the inline styles (style attribute) as a single string
	 * using the relevant supported attributes
	 * @return string
	 */
	function get_inline_styles(): string {
		$styles = '';

		if($this->textAlign) {
			$value = $this->textAlign->value;
			$styles .= "text-align: $value;";
		}

		return $styles;
	}
}
