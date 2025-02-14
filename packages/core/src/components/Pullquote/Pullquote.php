<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::BLOCKQUOTE])]
#[DefaultTag(Tag::BLOCKQUOTE)]
class Pullquote extends TextElementExtended {
	use BackgroundColor;

	/**
	 * @var string|null $citation
	 * @description Optional citation for the quote
	 */
	protected ?string $citation = null;

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Pullquote.pullquote');
		$this->citation = $attributes['citation'] ?? null;
	}

	function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();
		$classes[] = $this->get_bem_name();

		if (isset($this->backgroundColor)) {
			$classes[] = 'bg-' . $this->backgroundColor->value;
		}

		return $classes;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			// TODO: Need to handle text colour on the paragraph/citation
			'content'    => $this->content,
			'citation'   => $this->citation,
		])->render();
	}
}
