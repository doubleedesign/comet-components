<?php
namespace Doubleedesign\Comet\Core;

class Link extends TextElement {

    function __construct(array $attributes, string $content) {
        parent::__construct(
			array_merge($attributes, ['tagName' => 'a']),
	        $content,
	        'components.Link.link'
        );
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
