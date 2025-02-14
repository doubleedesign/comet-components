<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::LI])]
#[DefaultTag(Tag::LI)]
class TabPanelTitle extends TextElement {
	protected string $anchor;

	function __construct(array $attributes, string $content) {
		parent::__construct(
			array_merge($attributes, ['context' => 'tabs__tab-list']),
			$content, '
			components.Tabs.TabPanelTitle.tab-panel-title'
		);
		$this->anchor = substr(Utils::kebab_case(strip_tags($content)), 0, 15);
	}

	public function get_anchor(): string {
		return $this->anchor;
	}

	function get_bem_name(): ?string {
		return $this->context . '__item';
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'anchor'	 => $this->anchor,
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
		])->render();
	}
}
