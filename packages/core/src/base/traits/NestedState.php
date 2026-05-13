<?php
namespace Doubleedesign\Comet\Core;

trait NestedState {
    /**
     * @var bool $isNested
     * @description Whether this component is nested inside another element;
     *              used for simplifying layout and HTML structure handling
     */
    private bool $isNested = false;

    public function set_is_nested(?bool $isNested): void {
        $this->isNested = $isNested ?? $this->isNested;

        if ($this->isNested) {
            if (method_exists($this, 'set_size')) {
                $this->set_size(null);
            }
        }
    }

    public function get_is_nested(): bool {
        return $this->isNested;
    }
}
