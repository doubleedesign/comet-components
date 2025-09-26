<?php
namespace Doubleedesign\Comet\Core;

trait BlockElementModifier {
    use Context;
    use ContextHierarchy;
    private string $block = '';
    private ?string $element = null;
    private ?string $modifier = null;
    private int $levelsDeep = 0;

    /**
     * @var string $shortName
     * @description The short name of the component, derived from the blade file name by default.
     */
    protected string $shortName = '';

    protected function init_bem_classes($bladeFile): void {
        $this->shortName = !empty($this->shortName) ? $this->shortName : array_reverse(explode('.', $bladeFile))[0];
        // If the component does not have explicitly set context, derive it from the blade file path
        $this->context = $this->init_context_from_blade_file($bladeFile, $this->context);

        // Determine how many levels deep we are for multi-level elements
        if (!empty($this->context) && str_contains($this->context, '__')) {
            $this->levelsDeep = count(explode('__', $this->context));
        }

        // If no context, this is probably a top-level component, so it is the block
        if (empty($this->context)) {
            $this->set_bem_block($this->shortName);
        }
        else {
            $this->set_bem_block($this->context);
            $this->set_bem_element($this->shortName);
        }

        $this->classes = array_unique(
            array_merge(
                $this->get_bem_classes(),
                $this->classes
            )
        );
    }

    public function set_bem_block(string $block): void {
        $this->block = $block;
    }

    public function set_bem_element(?string $element): void {

        // Handle multi-level e.g., menu-list-item with context (and block) menu__list should be "item" for the element
        if ($this->levelsDeep > 1 && str_contains($element, '-')) {
            $kebabBlock = str_replace('__', '-', $this->block);
            // Transform cases like MenuList -> MenuListItem to MenuList -> Item
            if (str_starts_with($element, $kebabBlock)) {
                $element = str_replace($kebabBlock . '-', '', $element);
            }
            // Transform cases like CardContent -> CardImage to CardContent -> Image
            else {
                $blockParts = explode('__', $this->block);
                $componentParts = explode('-', $element);
                $end = Utils::array_diff_end($componentParts, $blockParts);
                $element = join('-', $end);
            }
        }

        // Transform duplicate naming in one level so that things like card -> card-body will become card__body not card__card-body
        else if (str_contains($element, $this->context)) {
            $element = str_replace($this->context . '-', '', $element);
        }

        // ...also if there was explicit context added in any case, handle that
        $splitContext = $this->context ? explode('__', $this->context) : [];
        if (!empty($splitContext) && str_contains($element, end($splitContext))) {
            $element = str_replace(end($splitContext) . '-', '', $element);
        }

        $this->element = $element;
    }

    public function set_bem_modifier(?string $modifier): void {
        $this->modifier = $modifier;
    }

    private function get_block_class(): string {
        return $this->block;
    }

    private function get_element_class(): ?string {
        return $this->element ? "{$this->block}__{$this->element}" : null;
    }

    private function get_modifier_class(): ?string {
        if ($this->element && $this->modifier) {
            return $this->block . "__{$this->element}--{$this->modifier}";
        }

        return $this->block && $this->modifier ? "{$this->block}--{$this->modifier}" : null;
    }

    public function get_bem_structure(): array {
        return array(
            'block'    => $this->block,
            'element'  => $this->element,
            'modifier' => $this->modifier
        );
    }

    public function get_bem_classes(): array {
        if ($this->element) {
            return array_filter([
                $this->get_element_class(),
                $this->get_modifier_class()
            ]);
        }

        return array_filter([
            $this->get_block_class(),
            $this->get_modifier_class()
        ]);
    }

    public function get_bem_prefix(): string {
        return $this->get_bem_classes()[0];
    }

}
