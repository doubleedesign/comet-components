<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::A])]
#[DefaultTag(Tag::A)]
class Link extends Renderable {
	protected string $content;

    function __construct(array $attributes, string $content) {
        parent::__construct(
			array_merge($attributes, ['tagName' => 'a']),
	        'components.Link.link'
        );

		$this->content = $content;
    }

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			'content'    => $this->content
		])->render();
	}
}
