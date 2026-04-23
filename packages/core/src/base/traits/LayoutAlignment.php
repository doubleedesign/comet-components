<?php
namespace Doubleedesign\Comet\Core;

trait LayoutAlignment {
    /**
     * @var Alignment|null $hAlign
     * @description Horizontal alignment, if applicable
     */
    protected ?Alignment $hAlign = Alignment::MATCH_PARENT;

    /**
     * @var Alignment|null $vAlign
     * @description Vertical alignment, if applicable
     */
    protected ?Alignment $vAlign = Alignment::MATCH_PARENT;

    /**
     * @param  array  $attributes
     * @param  Alignment|null  $defaultHorizontal
     * @param  Alignment|null  $defaultVertical
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    protected function set_layout_alignment(array $attributes, ?Alignment $defaultHorizontal = null, ?Alignment $defaultVertical = null): void {
		if(isset($attributes['hAlign'])) {
			$this->hAlign = $this->get_from_string_or_alignment($attributes['hAlign'])
				?? $this->get_component_defaults()['hAlign']
				?? $this->get_from_string_or_alignment($defaultHorizontal)
				?? $this->hAlign;
		}


	    if(isset($attributes['vAlign'])) {
		    $this->vAlign = $this->get_from_string_or_alignment($attributes['vAlign'])
			    ?? $this->get_component_defaults()['vAlign']
			    ?? $this->get_from_string_or_alignment($defaultVertical)
			    ?? $this->vAlign;
	    }
    }

    private function get_component_defaults(): array {
        $class = static::class;
        $classShortname = Utils::kebab_case(substr($class, strrpos($class, '\\') + 1));
        $defaults = Config::getInstance()->get_component_defaults($classShortname);

        return array(
            'hAlign' => $defaults['hAlign'] ?? null,
            'vAlign' => $defaults['vAlign'] ?? null,
        );
    }

    private function get_from_string_or_alignment($value): ?Alignment {
        if ($value instanceof Alignment) {
            return $value;
        }
        else if (is_string($value)) {
            return Alignment::tryFrom($value);
        }

        return null;
    }
}
