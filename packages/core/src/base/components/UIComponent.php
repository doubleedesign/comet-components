<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::HEADER, Tag::FOOTER, Tag::MAIN, Tag::ARTICLE, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
abstract class UIComponent extends Renderable {
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
        $this->innerComponents = $innerComponents;
    }

}
