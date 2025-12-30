<?php
namespace Doubleedesign\Comet\Core;

use Exception;

/**
 * Trait to manage component context and shortName.
 * Used within the BlockElementModifier trait for BEM naming,
 * but can also be used by components not implementing BEM that require context/shortname awareness and overriding ability.
 *
 * TODO: This and BlockElementModifier are very tightly coupled.
 *       The separation is largely for dev readability/understanding (as well as not having BEM stuff in classes that won't use it),
 *       but given they call each other's methods it's probably a code smell that should be tidied up.
 */
trait ContextHierarchy {
    /**
     * @var ?string $context
     * @description The kebab-case or BEM element chain name of the parent component or variant (if contextually relevant).
     *
     * @dev-notes   Can alternatively be explicitly set at the component level; kebab-case format is expected.
     *              Note: For components that use the BEM trait, this must be set before init_bem_classes() is called for class naming to work as expected.
     */
    private ?string $context = null;
    private ?string $implicit_context = null;
    private ?string $explicit_context = null;
    private ?string $default_shortName = null;

    /**
     * @var array $hierarchy
     * @description The hierarchy of component names derived from the Blade file path
     */
    private array $hierarchy = [];

    /**
     * The dot-delimited path to the Blade template file
     *
     * @var string
     */
    protected string $bladeFile = '';

    public function get_context(): ?string {
        return $this->context;
    }

    protected function init_context(string $bladeFile): static {
        $this->bladeFile = $bladeFile;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->init_from_blade_file($bladeFile);

        return $this;
    }

    /**
     * Initially set context according to some assumptions based on the blade file path.
     * This supports components that are intended to be nested as shown by being nested in the directory structure,
     * such as accordion panels, columns, etc., to have a multi-element BEM class structure out-of-the-box.
     *
     * @param  string  $bladeFile
     *
     * @return ContextHierarchy
     * @throws Exception
     */
    private function init_from_blade_file(string $bladeFile): static {
        if (empty($this->bladeFile)) {
            throw new Exception('ContextHierarchy: Blade filename not set. Ensure init_context() has been called first.');
        }

        $this->default_shortName = array_reverse(explode('.', $bladeFile))[0];
        $this->prepare_hierarchy($bladeFile);

        // Top-level component -> no implicit context
        if (count($this->hierarchy) <= 1) {
            $this->implicit_context = null;

            return $this;
        }

        // First-level child -> return the parent component name
        if (count($this->hierarchy) == 2) {
            $this->implicit_context = Utils::kebab_case($this->hierarchy[0]);
            $this->context = $this->implicit_context;

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
        }, [Utils::kebab_case($hierarchy[0])]);

        $this->implicit_context = join('__', array_map(fn($item) => Utils::kebab_case($item), $simplified));
        $this->context = $this->implicit_context;

        return $this;
    }

    /**
     * Account for explicitly set context when determining the final context to use.
     *
     * @param  string|null  $explicit_context
     *
     * @return ContextHierarchy
     * @throws Exception
     */
    protected function with_explicit_context(?string $explicit_context): static {
        if (empty($this->hierarchy)) {
            throw new Exception('Context hierarchy not prepared. Ensure init_from_blade_file() has been called first.');
        }

        // If no explicit context was given, do nothing
        if ($explicit_context === null) {
            return $this;
        }

        // If the explicit context matches the component's own shortName, ignore it
        // Note: On first call, shortName might not be set yet because BEM initialisation happens after context initialisation
        // - that's why we also check against default_shortName; this can result in some edge cases with explicit shortnames that need handling.
        if ($explicit_context === $this->default_shortName || (method_exists($this, 'get_shortname') && $explicit_context === $this->get_shortname())) {
            return $this;
        }

        // Don't double up
        if ($this->implicit_context === $explicit_context) {
            return $this;
        }

        // If this is a top-level component or has no implicit context for some other reason, return the explicitly provided context as-is
        if ($this->implicit_context === null) {
            $this->explicit_context = $explicit_context;
            $this->context = $explicit_context;

            return $this;
        }

        $this->explicit_context = $explicit_context;
        $this->context = $explicit_context . '__' . $this->implicit_context;

        return $this;
    }

    public function update_context(string $context, ?bool $clear_previous = false): static {
        if ($clear_previous) {
            $this->context = $context;
        }
        else {
            /** @noinspection PhpUnhandledExceptionInspection */
            $this->with_explicit_context($context)->and_bem($this->shortName);
        }

        // Allow for method chaining like update_context(...)->and_bem(...)
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
}
