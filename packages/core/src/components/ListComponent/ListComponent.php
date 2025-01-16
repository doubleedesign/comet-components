<?php
namespace Doubleedesign\Comet\Core;
use RuntimeException;

class ListComponent extends UIComponent {
	private bool $ordered;

	function __construct(array $attributes, array $innerComponents) {
		$this->ordered = $attributes['ordered'] ?? false;
		$this->tag = $this->ordered ? Tag::OL : Tag::UL;
		parent::__construct($attributes, $innerComponents, 'components.ListComponent.list');
	}

	function get_inline_styles(): array {
		$styles = [];

		$color =
			$this->style['color']
			?? $this->style['elements']['link']['color']['text'] // WordPress format
			?? null;

		if($color) {
			// If it's a hex colour, leave as-is
			if (preg_match('/^#[0-9A-F]{6}$/i', $color)) {
				$styles['color'] = $color;
			}
			else {
				// Transform expected formats to CSS variable format
				// WordPress format is like var:preset|color|vivid-cyan-blue
				$stripped = str_replace('var:', '', $color);
				$color = str_replace('|', '--', $stripped);
				// Hack in if we're in WP context
				if(defined('WPINC')) {
					$color = "wp--$color";
				}

				$styles['color'] = "var(--$color)";
			}
		}

		return $styles;
	}

	function render(): void {
		$blade = BladeService::getInstance();
		$attrs = $this->get_html_attributes();
		$classes = implode(',', $this->get_filtered_classes());

		try {
			echo $blade->make($this->bladeFile, [
				'ordered'    => $this->ordered,
				'classes'    => $classes,
				'attributes' => array_filter($attrs, fn($k) => $k !== 'class', ARRAY_FILTER_USE_KEY),
				'children'   => $this->process_inner_components()
			])->render();
		}
		catch (RuntimeException $e) {
			error_log(print_r($e, true));
		}
	}
}
