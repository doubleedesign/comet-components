<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE, Tag::ARTICLE])]
#[DefaultTag(Tag::SECTION)]
class Copy extends UIComponent {
    use ColorPair;
    use LayoutContainerSize;
    use NestedState {
		set_is_nested as private trait_set_is_nested;
    }

    /**
     * @var array<PreprocessedHTML|Heading|ButtonGroup|ImageComponent> $innerComponents
     * @description Inner column components
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Copy.copy');
        $this->set_color_pair($attributes);
        $this->set_is_nested($attributes['isNested'] ?? null);
        $this->set_size($attributes);
    }

    public function set_is_nested($isNested): void {
        $this->trait_set_is_nested($isNested);
        if ($this->get_is_nested()) {
            $this->tagName = Tag::DIV;
        }
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->size) && !$this->get_is_nested()) {
            $attributes['data-size'] = $this->size->value;
        }

	    if ($this->colorTheme) {
		    $attributes['data-color-theme'] = $this->colorTheme->value;
	    }

		if($this->get_background_color() !== null) {
			$attributes['data-background'] = $this->get_background_color()->value;
		}

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
