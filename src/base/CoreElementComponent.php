<?php
namespace Doubleedesign\Comet\Components;

abstract class CoreElementComponent implements IRenderable {
	protected ?Alignment $textAlign = null;
	/** @var string $content - plain text or basic HTML */
	protected string $content;
	
	function __construct(array $attributes, string $content) {
		$this->content = $content;
		$this->textAlign = isset($attrs['textAlign']) ? Alignment::tryFrom($attrs['textAlign']) : null;
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
