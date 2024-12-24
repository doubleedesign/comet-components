<?php
namespace Doubleedesign\Comet\Components;

abstract class UIComponent {
    protected BaseAttributes $attributes;

    /** @var string $content - plain text or innerHTML */
    protected string $content;

    /** @var UIComponent[] $innerComponents */
    protected array $innerComponents;

    /**
     * UIComponent constructor
     *
     * @param array $attributes
     * @param string $content
     * @param UIComponent[] $innerComponents
     */
    function __construct(array $attributes, string $content = '', array $innerComponents = []) {
        $this->attributes = new BaseAttributes($attributes);
        $this->content = $content;
        $this->innerComponents = $innerComponents;
    }


    /**
     * Generic method to render inner content
     * (child classes may override this)
     * TODO: Un-WordPress this
     *
     * @return string
     */
    protected function get_inner_content_html(): string {
        $inner_html = array_reduce($this->innerComponents, function($acc, $component) {
            ob_start();
            //$short_blockName = array_reverse(explode('/', $component['blockName']))[0];
            // The variables are extracted to make them available to the included file
            //extract(['attributes' => $component['attrs'], 'content' => $component['innerHTML'], 'innerComponents' => $component['innerBlocks']]);
            // Render by calling the template
            // include($template);
            return $acc . ob_get_clean();
        }, '');

        ob_start();
        echo $inner_html;
        return ob_get_clean();
    }
	
}
