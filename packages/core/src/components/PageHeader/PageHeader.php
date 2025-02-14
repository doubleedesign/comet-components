<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::HEADER, Tag::DIV])]
#[DefaultTag(Tag::HEADER)]
class PageHeader extends UIComponent {

	/**
	 * @var string $title
	 * @description The title of the page
	 */
	protected string $title;
	/**
	 * @var array<int, array{title: string, url: string|null}> $breadcrumbs
	 * @description Array of title and URL pairs
	 */
	protected array $breadcrumbs;
	/**
	 * @var ?ContainerSize $size
	 * @description Keyword specifying the relative width of the container for the inner content
	 */
	protected ?ContainerSize $size = ContainerSize::DEFAULT;
	/**
	 * @var ?ThemeColor $backgroundColor
	 * @description Background colour keyword
	 */
	protected ?ThemeColor $backgroundColor;

    function __construct(array $attributes, string $title, array $breadcrumbs = []) {
	    $this->backgroundColor = isset($attributes['backgroundColor']) ? ThemeColor::tryFrom($attributes['backgroundColor']) : ThemeColor::WHITE;
	    $this->size = isset($attributes['size']) ? ContainerSize::tryFrom($attributes['size']) : ContainerSize::DEFAULT;
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
