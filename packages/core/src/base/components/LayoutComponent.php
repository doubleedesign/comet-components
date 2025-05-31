<?php

namespace Doubleedesign\Comet\Core;

abstract class LayoutComponent extends UIComponent {
    use BackgroundColor;
    use LayoutAlignment;

    public function __construct(array $attributes, array $innerComponents, string $bladeFile) {
        parent::__construct($attributes, $innerComponents, $bladeFile);
        $this->set_layout_alignment_from_attrs($attributes);

        if (!$this instanceof Container) {
            $this->set_background_color_from_attrs($attributes);
        }

        if (!$this->exclude_from_background_simplification()) {
            if ($this instanceof Container && !$this->withWrapper) {
                $this->remove_redundant_background_colors();
            }
            else {
                $this->simplify_all_background_colors();
            }
        }
    }

    protected function get_filtered_classes(): array {
        if ((!$this instanceof Column) && (!$this instanceof Steps)) {
            return array_merge(
                parent::get_filtered_classes(),
                ['layout-block']
            );
        }

        return parent::get_filtered_classes();
    }

    private function exclude_from_background_simplification(): bool {
        foreach ($this->innerComponents as $component) {
            if ($component instanceof CallToAction) {
                return true;
            }
        }

        return false;
    }

    /**
     * Default render method (child classes may override this)
     */
    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes_string(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents,
        ])->render();
    }
}
