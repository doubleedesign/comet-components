<?php
namespace Doubleedesign\Comet\Components;

class LayoutComponent extends UIComponent implements IRenderable {
	protected ?Alignment $hAlign = null;
	protected ?Alignment $vAlign = null;
	
	function __construct(array $attributes, array $children) {
		parent::__construct($attributes, '', $children);
		$this->hAlign = isset($attrs['hAlign']) ? Alignment::tryFrom($attrs['hAlign']) : null;
		$this->vAlign = isset($attrs['vAlign']) ? Alignment::tryFrom($attrs['vAlign']) : null;
	}
	
	function get_inline_styles(): string {
		$styles = '';
		
		// TODO: Handle layout through classes, not inline styles
		if($this->hAlign) {
			$value = $this->hAlign->value;
			$styles .= "justify-content: $value;";
		}
		
		if($this->vAlign) {
			$value = $this->vAlign->value;
			$styles .= "align-items: $value;";
		}
		
		return $styles;
	}
	
	function get_html_attributes(): string {
		$baseAttributes = $this->attributes->get_filtered_attributes();
		$baseClasses = $this->attributes->get_filtered_classes();
		$styles = $this->get_inline_styles();
		
		$attrs = array_merge(
			$baseAttributes,
			array(
				'id' => $this->attributes->get_id(),
				'class' => implode(' ', $baseClasses),
				'style' => $styles,
			)
		);
		
		return array_reduce(array_keys($attrs), function($acc, $key) use ($attrs) {
			if($attrs[$key] === null || $attrs[$key] === '') return $acc;
			
			return $acc . ' ' . $key . '="' . Utils::esc_attr($attrs[$key]) . '"';
		}, '');
	}
	
	public function render(): void {
		$tag = $this->attributes->get_tag();
		$content = $this->get_inner_content_html();
		$attrs = $this->get_html_attributes();
		
		echo "<$tag" . ($attrs ? "$attrs" : "") . ">$content</$tag>";
	}
}
