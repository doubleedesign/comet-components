<?php
namespace Doubleedesign\Comet\Core;

/**
 * Gallery component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.1
 * @description Display a grid of images with optional captions, with a range of layout options.
 */
#[AllowedTags([Tag::SECTION, Tag::DIV])]
#[DefaultTag(Tag::SECTION)]
class Gallery extends LayoutComponent {
    use BackgroundColor;
    use NestedState;

    /**
     * @var AspectRatio|null $aspectRatio
     * @description Crop images to the given aspect ratio, or scale them to fit within it (depending on the imageCrop setting); overrides explicitly set aspect ratios on individual images
     */
    protected ?AspectRatio $aspectRatio = null;

    /**
     * @var int $maxPerRow
     * @description The preferred number of columns to use for the layout (may be overridden to fewer in small containers/viewports)
     * @supported-values 1, 2, 3, 4, 5, 6, 7, 8
     */
    protected int $maxPerRow = 3;

    /**
     * @var string|null $caption
     * @description Caption describing the whole gallery; supports inline phrasing HTML tags such as <em> and <strong>
     */
    protected ?string $caption;

    /**
     * @var bool $captionAsHeading
     * @description Whether to render the caption as a heading above the gallery, or a figcaption below the gallery
     */
    protected bool $captionAsHeading = false;

    /**
     * @var bool $imageCrop
     * @description Whether to crop images to fill their grid cells (true) or contain them within their grid cells (false)
     */
    protected bool $imageCrop = true;

    /**
     * @var bool $lightbox
     * @description Whether to enable lightbox functionality for the images in the gallery; note that for this to work the images must have a href attribute with the URL to the image file set
     */
    protected bool $lightbox = false;

    /**
     * @var array<ContentImageBasic> $innerComponents
     * @description The image components to display in the gallery
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        $this->imageCrop = $attributes['imageCrop'] ?? $this->imageCrop;
        $this->maxPerRow = $attributes['maxPerRow'] ?? $attributes['columns'] ?? $this->maxPerRow;
        $this->caption = (isset($attributes['caption']) && !empty(trim($attributes['caption']))) ? trim($attributes['caption']) : null;
        $this->lightbox = $attributes['lightbox'] ?? $this->lightbox;
        $this->aspectRatio = isset($attributes['aspectRatio']) ? AspectRatio::fromString($attributes['aspectRatio']) : null;
        $this->captionAsHeading = $attributes['captionAsHeading'] ?? $this->captionAsHeading;
        $this->set_is_nested($attributes['isNested'] ?? false);
        $this->set_background_color($attributes);

        $updatedInnerComponents = array_map(function(ContentImageBasic $component) use ($attributes) {
            $component->set_behaviour($this->imageCrop ? 'cover' : 'contain');
            if (isset($this->aspectRatio)) {
                $component->set_aspect_ratio($this->aspectRatio);
            }

            return $component;
        }, $innerComponents);

        $groupAttrs = [
            'tagName'           => isset($this->caption) ? 'figure' : 'div',
            'shortName'         => 'images',
            'data-group-layout' => 'grid',
            'data-max-per-row'  => $attributes['maxPerRow'] ?? $attributes,
            'data-lightbox'     => $this->lightbox ? 'true' : null,
            'role'              => isset($this->caption) ? null : 'group'
        ];

        $wrappedImages = new Group($groupAttrs, $updatedInnerComponents);

        parent::__construct(
            $attributes,
            [$wrappedImages],
            'components.Gallery.gallery'
        );

        // Add caption after parent constructor runs so we have access to the correct BEM context
        if (!empty($this->caption)) {
            $captionClass = $this->get_bem_prefix() . '__caption';

            if ($this->captionAsHeading) {
                array_unshift($this->innerComponents, new Heading(['classes' => [$captionClass]], $this->caption));
            }
            else {
                array_push($wrappedImages->innerComponents, new PreprocessedHTML([],
                    "<figcaption class=\"{$captionClass}\">" . $this->caption . "</figcaption>"
                ));
            }
        }
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->get_background_color() !== null) {
            $attributes['data-background'] = $this->get_background_color()->value;
        }

        return $attributes;
    }
}
