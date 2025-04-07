<?php
namespace Doubleedesign\Comet\Core;

// Note: This component's tag changes responsively,
// determined by the Vue component that renders within ResponsivePanels
#[AllowedTags([Tag::DIV, Tag::SUMMARY])]
#[DefaultTag(Tag::DIV)]
class ResponsivePanelTitle extends TextElement {
	function __construct(array $attributes, string $content) {
		parent::__construct(
			array_merge($attributes, ['context' => 'responsive-panel']),
			$content,
			'components.ResponsivePanels.ResponsivePanelTitle.responsive-panel-title'
		);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		// Render the children directly without wrapping this component with its own tag,
		// because the parent components will handle that with Vue
		echo $blade->make($this->bladeFile, [
			'content' => Utils::sanitise_content($this->content)
		])->render();
	}
}
