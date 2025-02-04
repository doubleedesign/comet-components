<?php
namespace Doubleedesign\Comet\Core;

class TabPanelTitle extends TextElement {
	use HasAllowedTags;
	protected string $anchor;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::LI];
	}

	function __construct(array $attributes, string $content) {
		parent::__construct(
			array_merge($attributes, ['context' => 'tabs__tab-list']),
			$content, '
			components.Tabs.TabPanelTitle.tab-panel-title'
		);
		$this->tagName = Tag::LI;
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
