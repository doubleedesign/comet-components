<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::TD])]
#[DefaultTag(Tag::TD)]
class TableCell extends TextElement {
    use BackgroundColor;
    use BlockElementModifier;

    /**
     * @var string|null $verticalAlign
     * @description Vertical alignment of the cell content
     * @supported-values top, middle, bottom
     */
    protected ?string $verticalAlign;
    protected array $innerComponents;

    public function __construct(array $attributes, string|LabelWithTooltip $content) {
        parent::__construct($attributes,
            gettype($content) === 'string' ? $content : $content->content,
            'components.Table.TableCell.table-cell'
        );
        $this->innerComponents = gettype($content) === 'string' ? [] : [$content];
        $this->set_text_align_from_attrs($attributes);
        $this->set_background_color($attributes);
        $this->verticalAlign = $attributes['verticalAlign'] ?? null;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->backgroundColor)) {
            $attributes['data-background'] = $this->backgroundColor->value;
        }

        return $attributes;
    }

    public function get_inline_styles(): array {
        $styles = parent::get_inline_styles();

        if (isset($this->verticalAlign)) {
            $styles['vertical-align'] = $this->verticalAlign;
        }

        return $styles;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'             => $this instanceof TableHeaderCell ? 'th' : 'td',
            'classes'         => $this->get_filtered_classes(),
            'attributes'      => $this->get_html_attributes(),
            // FIXME allow data-* attributes in inline HTML in content
            // 'content'         => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
            'content'         => $this->content,
            'innerComponents' => $this->innerComponents,
        ])->render();
    }
}
