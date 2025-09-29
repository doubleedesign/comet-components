<?php
namespace Doubleedesign\Comet\Core;
use Exception;

/**
 *  TODO: This and ContextHierarchy are very tightly coupled.
 *        The separation is largely for dev readability/understanding (as well as not having BEM stuff in classes that won't use it),
 *        but given they call each other's methods it's probably a code smell that should be tidied up.
 */
trait BlockElementModifier {
    use ContextHierarchy;

    /**
     * @var string $shortName
     * @description The name of the component without any namespacing, prefixes, etc.
     *              Derived from the Blade filename if not explicitly set.
     */
    private string $shortName = '';
    private ?string $explicit_context = null;
    private string $block = '';
    private ?string $element = null;
    private ?string $modifier = null;
    private int $levelsDeep = 0;

    protected function init_bem_structure(string $bladeFile, ?string $override_context = null, ?string $override_shortname = null): static {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->init_context($bladeFile)
            ->with_explicit_context($override_context)
            ->and_bem($override_shortname);

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function and_bem(?string $override_shortname): void {
        if (empty($this->bladeFile)) {
            throw new Exception('Blade file not set. Ensure init_context() has been called first, or call init_bem_structure() instead.');
        }

        if ($override_shortname) {
            $attributes['shortName'] = $override_shortname;
        }
        $this->shortName = isset($attributes['shortName']) ? (string)$attributes['shortName'] : array_reverse(explode('.', $this->bladeFile))[0];

        $final_context = $this->get_context(); // gets it from the Context trait

        // Determine how many levels deep we are for multi-level elements
        if (!empty($final_context) && str_contains($final_context, '__')) {
            $this->levelsDeep = count(explode('__', $final_context));
        }

        // If no context, this is probably a top-level component, so it is the block
        if (empty($final_context)) {
            $this->set_bem_block($this->get_shortname());
        }
        // If context and shortname are the same, do not double up for block and element
        elseif ($final_context === $this->get_shortname()) {
            $this->set_bem_block($final_context);
        }
        else {
            $this->set_bem_block($final_context);
            $this->set_bem_element($this->get_shortname());
        }
    }

    // TODO: If this is set from outside, do we then need to update the element?
    public function set_bem_block(string $block): void {
        $this->block = $block;
    }

    // TODO: If this is set from outside, how to handle context? Do we need to?
    public function set_bem_element(?string $element): void {
        if ($this->implicit_context === null) {
            $this->element = $element;

            return;
        }

        // Where the element matches the block, remove repetition
        // e.g., menu-list -> menu-list-item becomes menu-list -> item
        $compareTo = preg_split('/(-|__)/', $this->implicit_context);
        $componentParts = explode('-', $element);
        $endDiff = Utils::array_diff_end($componentParts, $compareTo);
        $element = join('-', $endDiff);

        // Handle where a kebab-cased element matches the end of a block after the above transformation
        // e.g., Menu -> SubMenu -> SubMenuItem would be menu__sub-menu -> sub-menu-item, but we want just item for the element
        if ($this->levelsDeep > 1) {
            $blockParts = explode('__', $this->block);
            $blockEnd = end($blockParts);
            $blockEndParts = explode('-', $blockEnd);
            $itemParts = explode('-', $element);
            if (count($blockEndParts) > 1 && count($itemParts) > 1) {
                $endDiff = Utils::array_diff_end($itemParts, $blockEndParts);
                $element = join('-', $endDiff);
            }
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

    public function get_filtered_classes(): array {
        return array_unique(
            array_merge(
                $this->get_bem_classes(),
                $this->classes
            )
        );
    }

    public function get_bem_prefix(): string {
        return $this->get_bem_classes()[0];
    }

    public function get_shortname(): string {
        return $this->shortName;
    }
}
