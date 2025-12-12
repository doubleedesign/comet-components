<?php
namespace Doubleedesign\Comet\Core;

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
        $this->init_bem_structure($bladeFile, (isset($attributes['context']) && $attributes['context']), (isset($attributes['shortName']) && $attributes['shortName']));
        $this->innerComponents = $innerComponents;
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
}
