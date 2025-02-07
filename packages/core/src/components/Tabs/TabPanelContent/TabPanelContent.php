<?php
namespace Doubleedesign\Comet\Core;

class TabPanelContent extends UIComponent {
	use HasAllowedTags;

	protected ?Tag $tagName = Tag::DIV;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DIV];
	}

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Tabs.TabPanelContent.tab-panel-content'
		);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		// Render the children directly without wrapping this component with its own tag because that would be redundant
		echo $blade->make($this->bladeFile, [
			'children' => $this->innerComponents
		])->render();
	}
}
