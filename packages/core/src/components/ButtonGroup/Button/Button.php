<?php
namespace Doubleedesign\Comet\Core;

class Button extends Renderable {
	use HasAllowedTags;

	/**
	 * @var ?Tag
	 * @description The HTML tag to use for the component
	 */
	protected ?Tag $tagName = Tag::A;
	/**
	 * @var ?ThemeColor $colorTheme
	 * @description Colour keyword for the fill or outline colour
	 */
	protected ?ThemeColor $colorTheme = ThemeColor::PRIMARY;
	/**
	 * @var ?bool $isOutline
	 * @description Whether to use outline style instead of solid/filled
	 */
	protected ?bool $isOutline = false;
	/**
	 * @var string $content
	 * @description Plain text or basic HTML
	 */
	protected string $content;

	/**
	 * Specify default allowed Tags using the HasAllowedTags trait
	 * (Many child classes will override this with specific tags)
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::A, Tag::BUTTON];
	}

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, 'components.ButtonGroup.Button.button');
		$this->tagName = isset($attributes['tagName']) ? Tag::tryFrom($attributes['tagName']) : Tag::A;
		$this->colorTheme = isset($attributes['colorTheme']) ? ThemeColor::tryFrom($attributes['colorTheme']) : ThemeColor::PRIMARY;
		$this->isOutline = $attributes['isOutline'] ?? false;
		$this->content = $content;
	}

	function get_filtered_classes(): array {
		$classes = array_filter(parent::get_filtered_classes(), function ($class) {
			return !str_starts_with($class, 'is-style-outline') && $class !== 'is-style-outline';
		});

		$result = array_merge(
			[
				'button',
				"button--{$this->colorTheme->value}",
				...($this->isOutline ? ["button--{$this->colorTheme->value}--outline"] : []),
			],
			$classes
		);

		return array_unique($result);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'tag'        => $this->tagName->value,
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
		])->render();
	}
}
