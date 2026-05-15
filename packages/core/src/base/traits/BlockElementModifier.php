<?php
namespace Doubleedesign\Comet\Core;
use Exception;

trait BlockElementModifier {
    use ContextHierarchy;
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
    protected function and_bem(?string $shortname): void {
        if (empty($this->bladeFile)) {
            throw new Exception('Blade file not set. Ensure init_context() has been called first, or call init_bem_structure() instead.');
        }

        $this->set_shortname($shortname);
        $context = $this->get_context(); // gets it from the ContextHierarchy trait

        // Determine how many levels deep we are for multi-level elements
        if (!empty($context) && str_contains($context, '__')) {
            $this->levelsDeep = count(explode('__', $context)) - 1;
        }

        // If no context, this is probably a top-level component, so it is the block
        if (empty($context)) {
            $this->set_bem_block($this->get_shortname());

            return;
        }

        // If context and shortname are the same, do not double up for block and element
        if ($context === $this->get_shortname()) {
            $this->set_bem_block($context);

            return;
        }

        $this->set_bem_block($context);
        $this->set_bem_element($this->get_shortname());
    }

    /**
     * @param  string  $context
     * @param  Renderable|null  $parent  - the parent component if relevant
     *
     * @return $this
     * @throws Exception - if base context has not been initialised yet
     */
    public function update_context(string $context, ?Renderable $parent = null): static {
        if (isset($parent) && $parent instanceof UIComponent && $context === $parent->get_context() . '__' . $parent->get_shortname()) {
            $this->explicit_context = $context;
            $this->context = $context;
            $this->set_bem_block($this->get_context());
            $this->set_bem_element($this->get_shortname());

            if (method_exists($this, 'maybe_pass_down_context_to_inner_components')) {
                $this->maybe_pass_down_context_to_inner_components();
            }

            return $this;
        }

        if (!isset($this->explicit_context) && isset($parent) && method_exists($parent, 'get_shortname') && $context === $parent->get_shortname()) {
            $this->explicit_context = $parent->get_shortname();
            $this->context = $parent->get_shortname();
            $this->set_bem_block($this->explicit_context);
            $this->set_bem_element($this->get_shortname());

            if (method_exists($this, 'maybe_pass_down_context_to_inner_components')) {
                $this->maybe_pass_down_context_to_inner_components();
            }

            return $this;
        }

        $this->with_explicit_context($context)->and_bem($this->get_shortname());
        if (method_exists($this, 'maybe_pass_down_context_to_inner_components')) {
            $this->maybe_pass_down_context_to_inner_components();
        }

        // Allow for method chaining like update_context(...)->and_bem(...)
        return $this;
    }

    private function set_bem_block(string $block): static {
        $this->block = $block;

        return $this;
    }

    public function set_bem_element(?string $element): static {
        if ($this->implicit_context === null) {
            $this->element = $element;

            return $this;
        }

        $words_in_element = preg_split('/(-|__)/', $element);
        if (count($words_in_element) === 1) {
            $this->element = $element;

            return $this;
        }

        $context_to_use = $this->explicit_context ? $this->get_context() : $this->implicit_context;
        // If the end of any explicit context provided matches the original (implicit) context,
        // use the latter so that repetition between the end of the block and the start of the element is removed the same either way
        if (str_ends_with($context_to_use, $this->implicit_context)) {
            $context_to_use = $this->implicit_context;
        }

        // Where the element matches the end of the relevant piece of context, remove repetition
        // e.g., menu-list -> menu-list-item becomes menu-list -> item
        // skipping single-word elements so we don't break cases like file-group -> file
        if (count($words_in_element) > 1) {
            $compareTo = preg_split('/(-|__)/', $context_to_use);
            $componentParts = explode('-', $element);
            $endDiff = Utils::array_diff_end($componentParts, $compareTo);
            $element = join('-', $endDiff);
            $this->element = $element;

            // If the end of the element still matches the start of the block, collapse repetition there too
            $blockParts = explode('__', $this->block);
            $blockEnd = end($blockParts);
            if (str_starts_with($element, $blockEnd)) {
                $trimmedElement = str_replace("{$blockEnd}-", '', $element);
                if (!empty($trimmedElement)) {
                    $this->element = $trimmedElement;
                }
            }
        }

        return $this;
    }

    public function set_bem_modifier(?string $modifier): static {
        $this->modifier = $modifier;

        return $this;
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
        return $this->get_bem_classes()[0] ?? '';
    }
}
