<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::HEADER, Tag::FOOTER, Tag::MAIN, Tag::ARTICLE, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
abstract class UIComponent extends Renderable {
    use BlockElementModifier;
    use Context;

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
        $this->context = isset($attributes['context']) ? (string)$attributes['context'] : $this->context;
        $this->innerComponents = $innerComponents;
        $this->set_context_from_attributes($attributes);
        $this->init_bem_classes($bladeFile);
    }

    public function get_filtered_classes(): array {
        return array_unique(
            array_merge(
                $this->get_bem_classes(),
                parent::get_filtered_classes()
            )
        );
    }
}
