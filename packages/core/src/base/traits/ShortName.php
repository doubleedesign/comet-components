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

    protected function set_shortname(?string $shortName = null): void {
        if ($shortName) {
            $this->shortName = $shortName;
        }
        else {
            $this->set_shortname_from_blade_file($this->bladeFile);
        }
    }

    protected function set_shortname_from_blade_file(string $bladeFile): void {
        $this->shortName = array_reverse(explode('.', $bladeFile))[0];
    }

    public function get_shortname(): string {
        return $this->shortName;
    }
}
