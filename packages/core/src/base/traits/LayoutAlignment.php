<?php
namespace Doubleedesign\Comet\Core;

trait LayoutAlignment {
    /**
     * @var Alignment|null $hAlign
     * @description Horizontal alignment, if applicable
     */
    protected ?Alignment $hAlign = Alignment::MATCH_PARENT;

    /**
     * @var Alignment|null $vAlign
     * @description Vertical alignment, if applicable
     */
    protected ?Alignment $vAlign = Alignment::MATCH_PARENT;

    /**
     * @param  array  $attributes
     * @param  Alignment  $defaultHorizontal
     * @param  Alignment  $defaultVertical
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    protected function set_layout_alignment_from_attrs(array $attributes, Alignment $defaultHorizontal = Alignment::MATCH_PARENT, Alignment $defaultVertical = Alignment::MATCH_PARENT): void {
        // hAlign and vAlign are the preferred attributes,
        // but in WordPress in the block editor they are different; some blocks have $attributes['theSetting']
        // and some have $attributes['layout']['theSetting'] so we need to account for both
        // Also different blocks have different attributes for vertical alignment that we need to handle.

        if (isset($attributes['hAlign']) && $attributes['hAlign'] instanceof Alignment) {
            $this->hAlign = $attributes['hAlign'];
        }
        else {
            $hAlign = $attributes['hAlign'] ?? $attributes['justifyContent'] ?? null;
            $this->hAlign = isset($hAlign) ? Alignment::fromString($hAlign) : $defaultHorizontal;
        }

        if (isset($attributes['vAlign']) && $attributes['vAlign'] instanceof Alignment) {
            $this->vAlign = $attributes['vAlign'];
        }
        else {
            $vAlign = $attributes['vAlign'] ?? $attributes['verticalAlignment'] ?? $attributes['alignItems'] ?? null;
            $this->vAlign = isset($vAlign) ? Alignment::fromString($vAlign) : $defaultVertical;
        }
    }
}
