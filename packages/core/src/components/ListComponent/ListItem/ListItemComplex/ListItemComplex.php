<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::LI])]
#[DefaultTag(Tag::LI)]
class ListItemComplex extends UIComponent {
    protected ?string $content = null;

    /**
     * ListItemComplex constructor.
     *
     * @param  array  $attributes
     * @param  string  $content
     * @param  array<ListComponent>  $nestedLists
     */
    public function __construct(array $attributes, string $content, array $nestedLists) {
        $bladeFile = 'components.ListComponent.ListItem.list-item';
        $this->content = Utils::sanitise_content($content);
        parent::__construct($attributes, $nestedLists, $bladeFile);
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes_string(),
            'attributes' => $this->get_html_attributes(),
            'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
            'children'   => $this->innerComponents
        ])->render();
    }
}
