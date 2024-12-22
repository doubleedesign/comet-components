<?php
namespace Doubleedesign\Comet\Components;

// Using enums as a way to limit the valid values of some string attributes

class BaseAttributes {
    protected array $rawAttributes = [];
    protected ?string $id = null;
    /* @var string[] */
    protected array $classes = [];
    protected string|array|null $style = null;
    protected ?Alignment $align = null; // TODO: is this the same as textAlign? Or is it flexbox?
    protected ?Alignment $textAlign = null;
    protected ?Tag $tag = null;

    public function __construct(array $attrs) {
        $this->rawAttributes = $attrs;
        $this->id = isset($attrs['id']) ? $this->transform_string_value($attrs['id']) : null;
        $this->classes = isset($attrs['className']) ? explode(' ', $attrs['className']) : [];
        $this->style = $attrs['style'] ?? null;
        $this->align = isset($attrs['align']) ? Alignment::tryFrom($attrs['align']) : null;
        $this->textAlign = isset($attrs['textAlign']) ? Alignment::tryFrom($attrs['textAlign']) : null;
        $this->tag = isset($attrs['tagName']) ? Tag::tryFrom($attrs['tagName']) : Tag::DIV;
    }

    public function get_tag(): string {
        return $this->tag->value;
    }

    public function get_html_attributes(): string {
        $filtered_classes = $this->get_filtered_classes();
        $filtered_attributes = $this->get_filtered_attributes();
        $inline_styles = $this->get_inline_styles();

        $attrs = array_merge(
            array(
                'id' => $this->id,
                'class' => implode(' ', $filtered_classes),
                'style' => $inline_styles,
            ),
            $filtered_attributes
        );

        return array_reduce(array_keys($attrs), function($acc, $key) use ($attrs) {
            if($attrs[$key] === null || $attrs[$key] === '') return $acc;

            return $acc . ' ' . $key . '="' . $this->esc_attr($attrs[$key]) . '"';
        }, '');
    }

    private function get_filtered_attributes(): array {
        $class_properties = array_keys(get_class_vars(self::class));

        // Filter out:
        // 1. attributes that are handled as separate properties in this class
        // 2. nested arrays such as layout and focalPoint (which should be handled elsewhere)
        // 3. attributes that are not valid/supported HTML attributes for the given tag
        // Explicitly keep:
        // 1. attributes that start with 'data-' (custom data attributes)
        return array_filter($this->rawAttributes, function($key) use ($class_properties) {
            return (
                // Stuff to filter out
                $key !== 'class' &&
                $key !== 'style' &&
                !in_array($key, $class_properties) &&
                !is_array($this->rawAttributes[$key]) &&
                // Other stuff to keep
                (in_array($key, $this->get_valid_html_attributes()) || str_starts_with($key, 'data-'))
            );
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get the valid/supported HTML attributes for the given tag
     * @return string[]
     */
    private function get_valid_html_attributes(): array {
        return $this->tag->get_valid_attributes();
    }

    private function get_filtered_classes(): array {
        $redundant_classes = ['is-style-default'];

        return array_filter($this->classes, function($class) use ($redundant_classes) {
            return !in_array($class, $redundant_classes);
        });
    }

    private function get_inline_styles(): string {
        $styles = '';

        if($this->textAlign) {
            $value = $this->textAlign->value;
            $styles .= "text-align: $value;";
        }
        if($this->align) {
            $value = $this->align->value;
            $styles .= "text-align: $value;";
        }

        return $styles;
    }

    /**
     * If a string value has spaces, convert it to kebab case
     * @param string $value
     * @return string
     */
    protected function transform_string_value(string $value): string {
        // If no whitespace characters, return as is (preserves snake_case and PascalCase)
        if (!preg_match('/\s/', $value)) {
            return $value;
        }

        // Convert whitespace to hyphens and make lowercase
        return trim(
            strtolower(
                preg_replace('/\s+/', '-', $value)
            )
        );
    }

    /**
     * Implementation of similar to WordPress' esc_attr function
     * @param $text
     * @return array|string|null
     */
    protected function esc_attr($text): array|string|null {
        // Convert special characters to HTML entities
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        // Replace specific characters that need special handling
        $replacements = array(
            '%' => '%25',    // Percent sign
            '\'' => '&#039;', // Single quote
            '"' => '&quot;',  // Double quote
            '<' => '&lt;',    // Less than
            '>' => '&gt;',    // Greater than
            '&' => '&amp;',   // Ampersand
            "\r" => '',       // Remove carriage returns
            "\n" => '',       // Remove newlines
            "\t" => ' '       // Convert tabs to spaces
        );

        // Apply replacements
        $text = strtr($text, $replacements);

        // Remove any null bytes
        $text = str_replace("\0", '', $text);

        // Remove control characters and return the result
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $text);
    }

}
