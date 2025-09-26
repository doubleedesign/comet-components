<?php
namespace Doubleedesign\Comet\Core;

trait Context {
    /**
     * @var ?string $context
     * @description By default, the kebab-case or BEM element chain name of the parent component or variant (if contextually relevant).
     *              Can alternatively be explicitly set at the component level; note this must be set before init_bem_classes() is called.
     */
    protected ?string $context = null;

    protected function set_context_from_attributes(array $attributes): void {
        $this->context = isset($attributes['context']) ? (string)$attributes['context'] : $this->context;
    }

    /**
     * Setter to allow components to dynamically assign context to their children
     *
     * @param  string|null  $context
     *
     * @return void
     */
    public function set_context(?string $context): void {
        $this->context = $context;
    }
}
