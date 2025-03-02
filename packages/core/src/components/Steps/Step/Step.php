<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::LI])]
#[DefaultTag(Tag::LI)]
class Step extends UIComponent {
	/**
	 * @var array<Heading|Paragraph|ListComponent|Image|ButtonGroup> $innerComponents
	 */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Steps.Step.step');
		$this->context = 'steps';
	}

	protected function get_inner_classes(): array {
		return [$this->get_bem_name() . '__inner'];
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'      => $this->get_filtered_classes_string(),
			'innerClasses' => implode(' ', $this->get_inner_classes()),
			'attributes'   => $this->get_html_attributes(),
			'children'     => $this->innerComponents
		])->render();
	}
}
