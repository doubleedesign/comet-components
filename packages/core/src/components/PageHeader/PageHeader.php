<?php
namespace Doubleedesign\Comet\Core;

/**
 * Pageheader component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render a page header with the page title and optional breadcrumb navigation.
 */
#[AllowedTags([Tag::HEADER, Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::HEADER)]
class PageHeader extends LayoutComponent {
	use BackgroundColor;
    use ColorTheme;

    /**
     * @var string $title
     * @description The title of the page
     */
    protected string $content;

    /**
     * @var array $breadcrumbs
     * @description Indexed array of breadcrumb associative arrays with title, URL, and optional boolean 'current' for if this link is the current page
     */
    protected array $breadcrumbs;

    public function __construct(array $attributes, string $content, array $breadcrumbs = []) {
        $this->set_color_theme($attributes);
        $this->breadcrumbs = $breadcrumbs;
        $this->innerComponents = !empty($breadcrumbs) ? [new Breadcrumbs([], $this->breadcrumbs)] : [];

        $this->innerComponents = array_merge(
            $this->innerComponents,
            [new Heading(['level' => 1], $content)]
        );

        parent::__construct($attributes, $this->innerComponents, 'components.PageHeader.page-header');
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->colorTheme) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

	    if ($this->get_background_color() !== null) {
		    $attributes['data-background'] = $this->get_background_color()->value;
	    }

	    return $attributes;
    }
}
