<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ResponsivePanelContent extends LayoutComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct(
			array_merge($attributes, ['context' => 'responsive-panel']),
			$innerComponents,
			'components.ResponsivePanels.ResponsivePanelContent.responsive-panel-content'
		);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		// Render the children directly without wrapping this component with its own tag,
		// because the parent components will handle that with Vue
		echo $blade->make($this->bladeFile, [
			'children' => $this->innerComponents,
		])->render();
	}
}
