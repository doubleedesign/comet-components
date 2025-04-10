<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::LI])]
#[DefaultTag(Tag::LI)]
class TabPanelTitle extends TextElement {
	protected string $anchor;

	function __construct(array $attributes, string $content) {
		parent::__construct(
			array_merge($attributes, ['context' => 'tabs']),
			$content,
			'components.Tabs.TabPanelTitle.tab-panel-title'
		);
	}

	protected function get_bem_name(): ?string {
		return 'tabs__tab-list__item';
	}

	function render(): void {
		$blade = BladeService::getInstance();

		// The template just renders the inner content because the rest is taken care of by Vue
		echo $blade->make($this->bladeFile, [
			'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
		])->render();
	}
}
