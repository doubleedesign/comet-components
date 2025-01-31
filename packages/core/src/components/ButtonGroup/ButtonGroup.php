<?php
namespace Doubleedesign\Comet\Core;

class ButtonGroup extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ButtonGroup.button-group');
		$this->tagName = Tag::DIV;
	}

	public function render(): void {
		$blade = BladeService::getInstance();
		
		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();

	}
}
