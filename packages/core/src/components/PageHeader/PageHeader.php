<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::HEADER, Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::HEADER)]
class PageHeader extends UIComponent {
	use BackgroundColor;
	use LayoutContainerSize;

	/**
	 * @var string $title
	 * @description The title of the page
	 */
	protected string $title;
	/**
	 * @var array $breadcrumbs
	 * @description Indexed array of breadcrumb associative arrays with title, URL, and optional boolean 'current' for if this link is the current page
	 */
	protected array $breadcrumbs;

    function __construct(array $attributes, string $title, array $breadcrumbs = []) {
		$this->set_background_color_from_attrs($attributes);
		$this->set_size_from_attrs($attributes);
		$this->breadcrumbs = $breadcrumbs;
		$this->innerComponents = !empty($breadcrumbs) ?  [new Breadcrumbs([], $this->breadcrumbs)] : [];

		$this->innerComponents = array(
			new Container(
				// Attributes
				[
					'size' => $this->size->value,
				],
				// Inner components
				array_merge(
					$this->innerComponents,
					[new Heading(['level' => 1], $title)]
				)
			)
		);

        parent::__construct($attributes, $this->innerComponents, 'components.PageHeader.page-header');
    }

	protected function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();

		if (isset($this->backgroundColor)) {
			$classes[] = 'bg-' . $this->backgroundColor->value;
		}

		return $classes;
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
