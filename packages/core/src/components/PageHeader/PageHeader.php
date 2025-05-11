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
class PageHeader extends UIComponent {
    use BackgroundColor;
    use LayoutContainerSize;

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
        $this->set_background_color_from_attrs($attributes);
        $this->set_size_from_attrs($attributes);
        $this->breadcrumbs = $breadcrumbs;
        $this->innerComponents = !empty($breadcrumbs) ? [new Breadcrumbs([], $this->breadcrumbs)] : [];

        $this->innerComponents = array(
            new Container(
                [
                    'size'        => $this->size->value,
                    'withWrapper' => false,
                    'tagName'     => Tag::DIV->value,
                ],
                array_merge(
                    $this->innerComponents,
                    [new Heading(['level' => 1], $content)]
                )
            )
        );

        parent::__construct($attributes, $this->innerComponents, 'components.PageHeader.page-header');
        $this->simplify_all_background_colors();
    }

    protected function get_filtered_classes(): array {
        return array_merge(
            parent::get_filtered_classes(),
            ['page-section']
        );
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->backgroundColor)) {
            $attributes['data-background'] = $this->backgroundColor->value;
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes_string(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
