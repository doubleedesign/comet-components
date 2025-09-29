<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE, Tag::ARTICLE])]
#[DefaultTag(Tag::DIV)]
class CopyBlock extends Group {
    use LayoutContainerSize;
    use NestedState;

    /**
     * @var bool|null $withWrapper
     * @description Whether to wrap the container element so that the background is full-width (only applies if not nested)
     */
    protected ?bool $withWrapper = true;

    public function __construct(array $attributes, array $innerComponents) {
        if (!isset($attributes['shortName'])) {
            $attributes['shortName'] = 'copy';
        }

        $this->set_is_nested(@$attributes['isNested'] ?? false);
        $this->withWrapper = $attributes['withWrapper'] ?? $this->withWrapper;

        if (!isset($attributes['tagName']) && !$this->get_is_nested()) {
            $this->tagName = Tag::SECTION;
        }

        if (!$this->get_is_nested()) {
            $containerAttrs = array_merge(
                ['withWrapper' => $this->withWrapper],
                $attributes
            );

            $innerComponents = [
                new Container(
                    $containerAttrs,
                    // Container doesn't support colorTheme, so we still need another layer here
                    array(
                        new Group([
                            'colorTheme' => $attributes['colorTheme'],
                            'isNested'   => true,
                            'context'    => $this->get_shortname()
                        ], $innerComponents)
                    )
                )
            ];
        }

        parent::__construct($attributes, $innerComponents);
    }

    public function render(): void {
        // If this is not nested, we don't want to render the Container inside a Group
        // We expect our innerComponents to now be Container -> Group -> innerComponents passed in
        // So we can render the container directly like this
        if (!$this->get_is_nested()) {
            $this->innerComponents[0]->render();
        }
        else {
            // For nested instances, render using Group's render method
            parent::render();
        }
    }
}
