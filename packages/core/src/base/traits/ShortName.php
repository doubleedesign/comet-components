<?php
namespace Doubleedesign\Comet\Core;

trait ShortName {
    /**
     * @var string $shortName
     * @description The name of the component without any namespacing, prefixes, etc.
     *              Used for BEM block/element naming.
     *              Derived from the Blade filename if not explicitly set.
     */
    private string $shortName = '';

    protected function set_shortname(string $shortName): void {
        $this->shortName = $shortName;
    }

    public function get_shortname(): string {
        return $this->shortName;
    }
}
