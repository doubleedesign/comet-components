<?php

namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::SECTION, Tag::MAIN, Tag::DIV, Tag::ARTICLE, Tag::FOOTER])]
#[DefaultTag(Tag::SECTION)]
abstract class WrappedLayoutComponent extends LayoutComponent {
    /**
     * @var Tag|null $wrapperTag
     * @description Store a reference to the provided tag for use in the wrapping PageSection if applicable
     *              because child classes may override $this->tagName to avoid nesting sections in sections and similar
     */
    private ?Tag $wrapperTag;
    private array $wrapperAttrs;
    private array $containerAttrs;

    public function __construct(array $attributes, array $innerComponents, string $bladeFile, bool $withContainer = true) {
        parent::__construct($attributes, $innerComponents, $bladeFile);
        $this->wrapperTag = $this->tagName;
        $this->wrapperAttrs = Utils::array_pick($attributes, ['id', 'backgroundColor', 'colorTheme']);
        $this->containerAttrs = Utils::array_pick($attributes, ['hAlign', 'vAlign', 'size', 'orientation']);

        $orphanedAttrs = array_diff(
            array_keys($attributes),
            array_merge(
                array_keys($this->wrapperAttrs),
                array_keys($this->containerAttrs),
                ['shortName', 'context', 'isNested', 'tagName', 'classes'] // handled elsewhere
            )
        );
        if (count($orphanedAttrs) > 0) {
            trigger_error('WrappedLayoutComponent: The following attributes were not recognised and will be ignored: ' . join(', ', $orphanedAttrs), E_USER_WARNING);
        }

        // If this is not already a Container, wrap its contents in one
        // this saves having to add a container in almost every component that extends this class
        if (!$this instanceof Container && $withContainer) {
            $this->innerComponents = array(
                new Container([
                    'isNested' => true,
                    'context'  => $this->get_shortname(),
                    ...$this->containerAttrs
                ], $this->innerComponents)
            );
        }
    }

    protected function render_standalone(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'             => $this->tagName->value,
            'attributes'      => $this->get_html_attributes(),
            'classes'         => $this->get_filtered_classes(),
            'children'        => $this->innerComponents
        ])->render();
    }

    final protected function render_with_wrapper(): void {
        $inner = $this;
        $inner->set_is_nested(true); // Prevent infinite loop

        $withWrapper = new PageSection([
            'shortName'       => $this->get_shortname(),
            'tagName'         => $this->wrapperTag->value,
            ...$this->wrapperAttrs
        ], [$inner]);
        $withWrapper->render();
    }

    final public function render(): void {
        if (!$this->get_is_nested()) {
            $this->render_with_wrapper();
        }
        else {
            $this->render_standalone();
        }
    }
}
