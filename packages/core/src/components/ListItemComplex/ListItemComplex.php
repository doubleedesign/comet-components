<?php
namespace Doubleedesign\Comet\Core;
use RuntimeException;

class ListItemComplex extends UIComponent {
	use HasAllowedTags;

	protected ?Tag $tag = Tag::LI;
	protected ?string $content = null;

	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::LI];
	}

	function __construct(array $attributes, string $content, array $nestedLists) {
		$bladeFile = 'components.ListItem.list-item';
		$this->content = Utils::sanitise_content($content);
		parent::__construct($attributes, $nestedLists, $bladeFile);
	}

	function get_inline_styles(): array {
		return [];
	}

	function render(): void {
		$blade = BladeService::getInstance();
		$attrs = $this->get_html_attributes();
		$classes = $attrs['className'] ?? '';

		try {
			echo $blade->make($this->bladeFile, [
				'tag'        => $this->tag->value,
				'classes'    => sprintf('%s', $classes),
				'attributes' => array_filter($attrs, fn($k) => $k !== 'class', ARRAY_FILTER_USE_KEY),
				'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
				'children'   => $this->process_inner_components()
			])->render();
		}
		catch (RuntimeException $e) {
			error_log(print_r($e, true));
		}
	}
}
