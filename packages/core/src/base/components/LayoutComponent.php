<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::MAIN, Tag::HEADER, Tag::FOOTER, Tag::ASIDE, Tag::NAV, Tag::ARTICLE])]
#[DefaultTag(Tag::DIV)]
abstract class LayoutComponent extends UIComponent {
    use BackgroundColor;
    use LayoutAlignment;
    use NestedState;

    public function __construct(array $attributes, array $innerComponents, string $bladeFile) {
        parent::__construct($attributes, $innerComponents, $bladeFile);
        $this->set_layout_alignment($attributes);
        $this->set_background_colors($attributes);
        $this->set_is_nested(isset($attributes['isNested']) && (bool)$attributes['isNested']);

        if (isset($this->backgroundColor)) {
            if (!$this->exclude_from_background_simplification()) {
                if ($this instanceof Container && $this->isNested) {
                    $this->remove_redundant_background_colors();
                }
                else {
                    $this->simplify_all_background_colors();
                }
            }
        }
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        // Have container take care of where to put alignments itself
        if (!$this instanceof Container) {
            if (isset($this->hAlign) && !$this->hAlign->isDefault()) {
                $attributes['data-halign'] = $this->hAlign->value;
            }

            if (isset($this->vAlign) && !$this->vAlign->isDefault()) {
                $attributes['data-valign'] = $this->vAlign->value;
            }
        }

        if (isset($this->backgroundColor)) {
            $attributes['data-background'] = $this->backgroundColor->value;
        }
        else if (isset($this->gradient)) {
            $attributes['data-background'] = 'gradient-' . $this->gradient;
        }

        return $attributes;
    }

    private function exclude_from_background_simplification(): bool {
        foreach ($this->innerComponents as $component) {
            if ($component instanceof CallToAction && $component->isNested === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Default render method (child classes may override this)
     *
     * @return void
     */
    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
