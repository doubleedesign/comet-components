<?php
namespace Doubleedesign\Comet\Core;

/**
 * Card component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description
 */
#[AllowedTags([Tag::DIV, Tag::ARTICLE, Tag::FOOTER])]
#[DefaultTag(Tag::DIV)]
class Card extends UIComponent {
    use BackgroundColor;
    use ColorTheme;
    use ImageCropProperties;
    use LayoutOrientation;

    /**
     * @var array<Renderable> $aboveContentComponents
     * @description Optional array of components to render above the main content; allows for things like tags, badges, etc
     */
    protected ?array $aboveContentComponents = [];

    /**
     * @var array{src:string, alt:string} $image
     */
    protected array $image;

    /**
     * @var string $heading
     * @description Heading text of the card; will be put into an H3 by default; expects plain text / simple inline HTML
     */
    protected string $heading;

    /**
     * @var string $bodyText
     * @description Text content of the card; will be put into a paragraph; expects plain text / simple inline HTML
     */
    protected string $bodyText;

    /**
     * @var array{href:string, content:string, target:string, isOutline:false} $link
     * @description Optional link for the card
     */
    protected array $link = [];

    /**
     * @var bool $cardAsLink
     * @description Whether to render the entire card as a link (if a link is provided); otherwise a button will be used
     */
    protected bool $cardAsLink = false;
    protected bool $isNested = true;
    protected bool $withWrapper = true;

    public function __construct(array $attributes, ?array $aboveContentComponents = null) {
        $this->image = $this->validate_image_attributes($attributes['image'] ?? []);
        $this->heading = $attributes['heading'] ?? '';
        $this->bodyText = $attributes['bodyText'] ?? '';
        $this->link = $attributes['link'] ?? [];
        $this->cardAsLink = $attributes['cardAsLink'] ?? $this->cardAsLink;
        $this->isNested = true; // Cards are typically used as nested components
        $this->aboveContentComponents = $aboveContentComponents ?? null;
        $this->withWrapper = $attributes['withWrapper'] ?? $this->withWrapper;

        $innerComponents = $this->aboveContentComponents ?? [];
        if (!empty($this->heading)) {
            array_push($innerComponents, new Heading(['level' => 3], $this->heading));
        }
        if (!empty($this->bodyText)) {
            array_push($innerComponents, new Paragraph([], $this->bodyText));
        }
        if (!empty($this->link) && !$this->cardAsLink) {
            $linkAttrs = [
                'href'      => $this->link['href'] ?? '#',
                'target'    => $this->link['target'] ?? null,
                'isOutline' => $this->link['isOutline'] ?? false
            ];
            array_push($innerComponents, new Button($linkAttrs, $this->link['content'] ?? 'Read more'));
        }

        parent::__construct($attributes, $innerComponents, 'components.Card.card');

        $this->set_color_theme_from_attrs($attributes);
        $this->set_background_color_from_attrs($attributes, ThemeColor::WHITE);
        $this->set_orientation_from_attrs($attributes);
        // Optional advanced image configuration
        $this->set_aspect_ratio_from_attrs($attributes, AspectRatio::STANDARD);
        $this->set_focal_point_from_attrs($attributes);
        $this->set_image_offset_from_attrs($attributes);
    }

    private function validate_image_attributes(array $image): array {
        return Utils::array_pick($image, ['src', 'alt']);
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->colorTheme)) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }
        if (isset($this->orientation)) {
            $attributes['data-layout-orientation'] = $this->orientation->value;
        }
        if (isset($this->backgroundColor)) {
            $attributes['data-background'] = $this->backgroundColor->value;
        }

        return $attributes;
    }

    protected function get_image_wrapper_attributes(): array {
        $attributes = [];

        if (isset($this->aspectRatio)) {
            $attributes['data-aspect-ratio'] = strtolower($this->aspectRatio->name);
        }
        if (isset($this->focalPoint) || isset($this->offset)) {
            $attributes['style'] = $this->get_local_css_properties();
        }

        return $attributes;
    }

    protected function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        // Include shortname even if there is also context
        return array_unique([
            $this->get_bem_name(),
            ...$classes
        ]);
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'               => $this->tagName->value,
            'withWrapper'       => $this->withWrapper,
            'isLink'            => !empty($this->link) && $this->cardAsLink,
            'linkAttrs'         => $this->link,
            'bemName'           => $this->get_bem_name(),
            'context'           => $this->context,
            'shortName'         => $this->shortName,
            'classes'           => $this->get_filtered_classes(),
            'attributes'        => $this->get_html_attributes(),
            'imageWrapperAttrs' => $this->get_image_wrapper_attributes(),
            'image'             => $this->image,
            'children'          => $this->innerComponents
        ])->render();
    }
}
