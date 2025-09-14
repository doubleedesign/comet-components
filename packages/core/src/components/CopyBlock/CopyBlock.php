<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE, Tag::ARTICLE])]
#[DefaultTag(Tag::DIV)]
class CopyBlock extends Group {
    use LayoutContainerSize;

    /**
     * @var bool|null $withWrapper
     * @description Whether to wrap the container element so that the background is full-width (only applies if not nested)
     */
    protected ?bool $withWrapper = true;

    /**
     * @var bool $isNested
     * @description Whether this copy block is nested inside another component; determines whether to wrap in a container
     */
    protected bool $isNested = false;

    public function __construct(array $attributes, array $innerComponents) {
        $this->isNested = $attributes['isNested'] ?? $this->isNested;
        // Group allows for custom shortname from $attributes; default to copy-block if not set (otherwise it would be group)
        if (!isset($attributes['shortName'])) {
            $attributes['shortName'] = 'copy-block';
        }

        if (!isset($attributes['tagName']) && !$this->isNested) {
            $this->tagName = Tag::SECTION;
        }

        if (!$this->isNested) {
            // Add anything here that Container doesn't support but Group does
            $innerAttrs = array_merge(
                Utils::array_pick($attributes, ['colorTheme', 'isNested']),
                [
                    'context'   => ($this->shortName ?? 'copy-block') . '__container',
                    'shortName' => $this->shortName ?? '__inner',
                ]
            );
            $containerAttrs = array_merge(
                ['context' => 'copy-block'],
                array_diff($attributes, $innerAttrs)
            );

            $innerComponents = [
                new Container(
                    $containerAttrs,
                    // Container doesn't support colorTheme, so we still need another layer here
                    [new Group($innerAttrs, $innerComponents)]
                )
            ];
        }

        parent::__construct($attributes, $innerComponents);
    }

    protected function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        if ($this->isNested) {
            $classes = array_filter($classes, function($class) {
                return !in_array($class, ['container', 'copy-block__container', 'layout-block']);
            });
        }

        return $classes;
    }

    protected function get_bem_name(): ?string {
        if ($this->isNested) {
            return 'copy-block';
        }

        return parent::get_bem_name();
    }

    public function render(): void {
        // If this is not nested, we don't want to render the Container inside a Group
        // We expect our innerComponents to now be Container -> Group -> innerComponents passed in
        // So we can render the container directly like this
        if (!$this->isNested) {
            $this->innerComponents[0]->render();
        }
        else {
            // For nested instances, render using Group's render method
            parent::render();
        }
    }
}
