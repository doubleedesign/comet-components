<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::SPAN, Tag::LABEL])]
#[DefaultTag(Tag::SPAN)]
class LabelWithTooltip extends TextElement {
    use Icon;

    /**
     * @var string $tooltip
     * @description The text to use in the tooltip; if not provided, the icon + tooltip will not be rendered, only the label text will
     */
    protected string $tooltip;

    public function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.LabelWithTooltip.label-with-tooltip');
        $this->set_icon_from_attrs($attributes, 'fa-circle-info');
        $this->tooltip = $attributes['tooltip'] ?? '';
        $this->id = $this->generate_id_for_tooltip();
    }

    /**
     * The Tippy JS library used for tooltips only adds its tooltips to the DOM on hover/focus.
     * The tooltip added does have the role of "tooltip" and adds aria-describedby to the target element,
     * but that is not ideal for assistive technologies and other machine processing that just takes the DOM as-is.
     *
     * So, in the Blade template we add plain elements that meet the role/attribute requirements as well,
     * and for that we need a unique ID for that tooltip element.
     *
     * @return string
     */
    private function generate_id_for_tooltip(): string {
        if (!$this->id) {
            $this->id = "tooltip-" . Utils::kebab_case($this->content);
        }

        return $this->id;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        // Remove ID from the overall element's attributes as we're using it for the tooltip
        unset($attributes['id']);

        return $attributes;
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        return array_unique(array_merge($classes, [$this->get_bem_name()]));
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'id'         => $this->id,
            'tooltip'    => $this->tooltip,
            'iconPrefix' => $this->iconPrefix,
            'icon'       => $this->icon,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
        ])->render();
    }
}
