<?php
namespace Doubleedesign\Comet\Components;

class UIComponent implements Renderable {
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
     *
     * @return string
     */
    private function get_inner_content_html(): string {
        $inner_html = array_reduce($this->innerComponents, function($acc, $component) {
            ob_start();
            $short_blockName = array_reverse(explode('/', $component['blockName']))[0];
            $template = locate_template('components/' . sanitize_file_name($short_blockName) . '.php');
            // The variables are extracted to make them available to the included file
            extract(['attributes' => $component['attrs'], 'content' => $component['innerHTML'], 'innerComponents' => $component['innerBlocks']]);
            // Render by calling the template
            include $template ?: locate_template('blocks/default.php');
            return $acc . ob_get_clean();
        }, '');

        ob_start();
        echo $inner_html;
        return ob_get_clean();
    }


    /**
     * Default render method (child classes may override this)
     *
     * @return void
     */
    public function render(): void {
        $output = '';
        $tag = $this->attributes->get_tag() ?? 'div';
        $attrs = $this->attributes->get_html_attributes();

        // Add the opening tag with a space between tag and attributes if there are attributes
        $output .= "<$tag" . ($attrs ? "$attrs" : "") . ">";

        if($this->content) {
            // Strip out HTML tags except inline inner ones
            // (there's some assumptions here about the content that will be received here)
            $allowed_tags = '<a><em><strong><span><i><sup><sub><small>'; // TODO: There are more tags that should be allowed
            $text = strip_tags($this->content, $allowed_tags);

            //$output .= wp_kses_post($text); // TODO: Vanilla equivalent of wp_kses_post
            $output .= $text;
        }
        else if($this->innerComponents) {
            $output .= $this->get_inner_content_html();
        }

        $output .= "</$tag>";

        echo $output;
    }
}
