<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ContainerWithNesting extends Container {
    /**
     * @var ContainerSize|mixed|null $innerSize
     * @description The size of the inner container that the base container will wrap; should always be the same or smaller than the outer container size
     */
    protected ?ContainerSize $innerSize = null;

    public function __construct(array $attributes, array $innerComponents) {
        $attributes['isNested'] = true; // avoid an extra layer
        $outerAttrs = array_filter($attributes, function($key) {
            return !in_array($key, ['innerSize']);
        }, ARRAY_FILTER_USE_KEY);

        parent::__construct($outerAttrs, $innerComponents);

        if (isset($attributes['innerSize'])) {
            if ($attributes['innerSize'] instanceof ContainerSize) {
                $this->innerSize = $attributes['size'];
            }
            else {
                $this->innerSize = ContainerSize::tryFrom($attributes['innerSize']);
            }
        }

        $this->innerComponents = [new Container([
            'shortName' => $attributes['shortName'] ?? 'nested',
            'size'      => !isset($this->innerSize) ? null : ($this->innerSize === ContainerSize::FULLWIDTH ? null : $this->innerSize->value), // ignore "full width" and let the content have at it
            'isNested'  => true
        ], $this->innerComponents)];
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        // Modify the outer container class so we don't double up and mess up the BEM structure
        // This is along with setting this to explicitly nested even when it isn't actually nested
        // is all a bit of a hack to get nested containers working quickly.
        // This will do things like make "shared-content__container" into "shared-content container"
        $classes = array_map(function($class) {
            if (str_ends_with($class, '__container')) {
                $split = explode('__', $class);

                return join(' ', $split);
            }

            return $class;
        }, $classes);

        return array_unique($classes); // avoid 'container container' when there's no shortname
    }
}
