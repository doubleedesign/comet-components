<?php
namespace Doubleedesign\Comet\Core;

use Exception;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::HEADER, Tag::FOOTER, Tag::MAIN, Tag::ARTICLE, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
abstract class UIComponent extends Renderable {
    use BlockElementModifier;

    /**
     * @var array<Renderable> $innerComponents
     * @description Inner components to be rendered within this component
     */
    protected array $innerComponents;

    /**
     * UIComponent constructor
     *
     * @param  array<string, string|int|array|null>  $attributes
     * @param  array<Renderable>  $innerComponents
     * @param  string  $bladeFile
     */
    public function __construct(array $attributes, array $innerComponents, string $bladeFile) {
        parent::__construct($attributes, $bladeFile);
        $this->init_bem_structure($bladeFile, $attributes['context'] ?? null, $attributes['shortName'] ?? null);
        $this->innerComponents = $innerComponents;
        $this->maybe_pass_down_context_to_inner_components();
    }

    public function get_filtered_classes(): array {
        $classes = array_unique(
            array_merge(
                $this->get_bem_classes(),
                parent::get_filtered_classes()
            )
        );

        // Sort them so the context is always first if present
        usort($classes, function($a, $b) {
            if ($a === $this->get_context()) {
                return -1;
            }
            if ($b === $this->get_context()) {
                return 1;
            }

            return 0;
        });

        return $classes;
    }

    /**
     * In most cases, automatically pass down context to inner components that don't have their own context defined.
     * This is a separate method because when done when assigning $this->innerComponents in the constructor,
     * the context that should be used is not always available yet.
     *
     * @return void
     */
    protected function maybe_pass_down_context_to_inner_components(): void {
        if (empty($this->innerComponents)) return;
        if ($this instanceof Columns) return;
        if ($this instanceof WrappedPanelGroup) return;
        if ($this instanceof PanelGroupComponent) return;
        if (!method_exists($this, 'get_context')) return;

        $context_to_use = $this->get_default_context_for_inner_components();
        if ($context_to_use === null) {
            return;
        }

        array_walk($this->innerComponents, function($component) use ($context_to_use) {
            if ($component === null) return;
            if (!$component instanceof Renderable) return;
            if (!method_exists($component, 'update_context')) return;
            if (!$this->should_update_context($component)) return;

            try {
                $component->update_context($context_to_use);
            }
            catch (Exception $e) {
                $classname = get_class($component);
                if (function_exists('dump')) {
                    dump($classname . ": " . $e->getMessage());
                }
                else {
                    error_log($classname . ": " . $e->getMessage());
                }
            }
        });
    }

    private function should_update_context(Renderable $component): bool {
        if ($component instanceof Columns) return false;
        if ($component instanceof WrappedPanelGroup) return false;
        if ($component instanceof PanelGroupComponent) return false;
        if (!method_exists($component, 'update_context')) return false;
        if (!method_exists($component, 'get_shortname')) return false;
        if (!method_exists($component, 'get_context')) return false;

        // Do not add context to elements that do not already have classes by default, such as headings
        if (method_exists($component, 'get_bem_classes') && empty($component->get_bem_classes())) return false;

        $context_to_use = $this->get_default_context_for_inner_components();
        $component_context = $component->get_context();
        if ($component_context === $context_to_use) return false;

        return true;
    }

    private function get_default_context_for_inner_components(): ?string {
        $context = $this->get_context();

        // For containers, we don't want to pass down __container, just the context of the container itself
        if ($this instanceof Container && $context !== null) {
            return $context;
        }

        if (method_exists($this, 'get_element_class') && $this->get_element_class() !== null) {
            return $this->get_element_class();
        }

        if (method_exists($this, 'get_shortname') && $this->get_shortname() !== null) {
            return $this->get_shortname();
        }

        return null;
    }

}
