<?php
namespace Doubleedesign\Comet\Core;

/**
 * Breadcrumbs component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render a breadcrumb navigation list.
 */
#[AllowedTags([Tag::NAV])]
#[DefaultTag(Tag::NAV)]
class Breadcrumbs extends UIComponent {

	/**
	 * @param array $attributes
	 * @param array $breadcrumbs Indexed array of breadcrumb associative arrays with title, URL, and optional boolean 'current' for if this link is the current page
	 */
	function __construct(array $attributes, array $breadcrumbs) {
		$listItems = array_map(function($breadcrumb) {
			$linkAttributes = ['href' => trim($breadcrumb['url'] !== '') ? $breadcrumb['url'] : '#'];
			if(isset($breadcrumb['current']) && $breadcrumb['current']) {
				$linkAttributes['aria-current'] = 'page';
			}

			ob_start();
			$link = new Link($linkAttributes, $breadcrumb['title']);
			$link->render();
			$linkHtml = ob_get_clean();
			return new ListItem(
				['context' => 'breadcrumbs__list'],
				$linkHtml
			);
		}, $breadcrumbs);

		$innerComponents = [new ListComponent(['ordered' => true, 'context' => 'breadcrumbs'], $listItems)];

		parent::__construct($attributes, $innerComponents, 'components.Breadcrumbs.breadcrumbs');
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
