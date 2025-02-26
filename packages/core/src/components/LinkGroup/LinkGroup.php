<?php
namespace Doubleedesign\Comet\Core;

class LinkGroup extends UIComponent {
	use ColorTheme;

	/**
	 * @param array $attributes
	 * @param array<File|array<string,string> $links - Either an array of Link objects or an array of associative arrays corresponding to Link fields
	 */

    function __construct(array $attributes, array $links) {
	    $this->set_color_theme_from_attrs($attributes);
	    $innerComponents = array_map(function($link) {
		    if ($link instanceof Link) {
			    return $link;
		    }
		    return new Link(
				array_merge($link['attributes'], ['context' => 'link-group']),
		        $link['content']
		    );
	    }, $links);

        parent::__construct($attributes, $innerComponents, 'components.LinkGroup.link-group');
    }

	function get_html_attributes(): array {
		return array_merge(
			parent::get_html_attributes(),
			['data-color-theme' => $this->colorTheme->value]
		);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
