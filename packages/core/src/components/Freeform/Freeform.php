<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Freeform extends Renderable {
	/**
	 * @var string $content
	 * @description HTML content from TinyMCE editor or similar
	 */
	protected string $content;

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, 'components.Freeform.freeform');
		$this->content = $content;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			'content'    => $this->content,
		])->render();
	}
}
