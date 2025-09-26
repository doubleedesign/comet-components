<?php
namespace Doubleedesign\Comet\Core;

use Exception;

trait ContextHierarchy {
    private string $shortName = '';
    private array $hierarchy = [];
    private ?string $assumed_context = '';

    /**
     * Initially set context according to some assumptions based on the blade file path.
     * Some components may override this method to provide more specific or customised context.
     * This supports components that are intended to be nested as shown by being nested in the directory structure,
     * such as accordion panels, columns, etc., to have a multi-element BEM class structure out-of-the-box.
     *
     * @param  string  $bladeFile
     *
     * @return ContextHierarchy
     */
    protected function init_context_from_blade_file(string $bladeFile): static {
        $this->shortName = array_reverse(explode('.', $bladeFile))[0];
        $this->prepare_hierarchy($bladeFile);

        // Top-level component -> no context
        if (count($this->hierarchy) <= 1) {
            $this->assumed_context = null;

            return $this;
        }

        // First-level child -> return the parent component name
        if (count($this->hierarchy) == 2) {
            $this->assumed_context = strtolower($this->hierarchy[0]);

            return $this;
        }

        // Deeper nesting: Strip off the current component name
        $hierarchy = array_slice($this->hierarchy, 0, count($this->hierarchy) - 1);

        // ... and then return a chain of ancestors
        $simplified = array_reduce($hierarchy, function($carry, $item) {
            // Split PascalCase component names into words
            $kebab = Utils::kebab_case($item);
            $words = explode('-', $kebab);
            // Filter out words already in the accumulated hierarchy array if they're in the same order, so we just get the end bit
            $end = Utils::array_diff_end($words, $carry);
            // If there's no difference, just return what we have now
            if (empty($end)) {
                return $carry;
            }
            // If there's more than one word left, kebab-case them into one (e.g., sub-menu)
            $end = join('-', $end);

            return array_merge($carry, [$end]);
        }, [strtolower($hierarchy[0])]);

        $this->assumed_context = join('__', array_map('strtolower', $simplified));

        return $this;
    }

    private function prepare_hierarchy(string $bladeFile): void {
        $hierarchy = explode('.', $bladeFile);

        // Strip off "components" at the start
        if ($hierarchy[0] === 'components') {
            array_shift($hierarchy);
        }
        // ...and the current component filename at the end
        array_pop($hierarchy);

        $this->hierarchy = $hierarchy;
    }

    /**
     * Account for explicitly set context when determining the final context to use.
     *
     * @param  string|null  $explicit_context
     *
     * @return string|null
     * @throws Exception
     */
    protected function with_explicit_context(?string $explicit_context): ?string {
        if (empty($this->hierarchy) || empty($this->shortName)) {
            throw new Exception('ContextHierarchy trait not initialised. Please call init_context_from_blade_file() before calling add_explicit_context().');
        }

        // If the explicit context is the same as the current component name or the assumed context, don't double up
        if ($this->shortName === $explicit_context || $this->assumed_context === $explicit_context) {
            return $this->assumed_context;
        }

        // If this is a top-level component or has no assumed context for some other reason, return the explicitly provided context as-is
        if ($this->assumed_context === null) {
            return $explicit_context;
        }

        return $explicit_context . '__' . $this->assumed_context;
    }

    public function get_basic_context(): ?string {
        return $this->assumed_context;
    }
}
