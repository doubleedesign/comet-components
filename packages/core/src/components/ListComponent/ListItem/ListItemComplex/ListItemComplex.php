<?php
namespace Doubleedesign\Comet\Core;

class ListItemComplex extends UIComponent {
	use HasAllowedTags;

	protected ?Tag $tagName = Tag::LI;
	protected ?string $content = null;

	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::LI];
	}

	/**
	 * ListItemComplex constructor.
	 * @param array $attributes
	 * @param string $content
	 * @param array<ListComponent> $nestedLists
	 */
	function __construct(array $attributes, string $content, array $nestedLists) {
		$bladeFile = 'components.ListComponent.ListItem.list-item';
		$this->content = Utils::sanitise_content($content);
		parent::__construct($attributes, $nestedLists, $bladeFile);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'tag'        => $this->tagName->value,
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
			'children'   => $this->innerComponents
		])->render();
	}
}
