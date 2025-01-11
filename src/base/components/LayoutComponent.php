<?php
namespace Doubleedesign\Comet\Components;
use RuntimeException;

abstract class LayoutComponent extends UIComponent {
    use HasAllowedTags;
	protected ?Alignment $hAlign = null;
	protected ?Alignment $vAlign = null;

    /**
     * Specify allowed Tags using the HasAllowedTags trait
     * @return array<Tag>
     */
    protected static function get_allowed_html_tags(): array {
        return [Tag::DIV, Tag::SECTION, Tag::HEADER, Tag::FOOTER, Tag::MAIN, Tag::ARTICLE, Tag::ASIDE];
    }

	function __construct(array $attributes, array $children) {
		parent::__construct($attributes, $children);
		$this->hAlign = isset($attrs['hAlign']) ? Alignment::tryFrom($attrs['hAlign']) : null;
		$this->vAlign = isset($attrs['vAlign']) ? Alignment::tryFrom($attrs['vAlign']) : null;
	}


    /**
     * Build the inline styles (style attribute) as a single string
     * using the relevant supported attributes
     * @return string
     */
	function get_inline_styles(): string {
		$styles = '';

		// TODO: Handle layout through classes, not inline styles
		if($this->hAlign) {
			$value = $this->hAlign->value;
			$styles .= "justify-content: $value;";
		}

		if($this->vAlign) {
			$value = $this->vAlign->value;
			$styles .= "align-items: $value;";
		}

		return $styles;
	}


	public function render(): void {
        try {
            $tag = $this->get_tag();
            $content = $this->get_inner_content_html();
            $attrs = $this->get_html_attributes();

            echo "<$tag" . ($attrs ? "$attrs" : "") . ">$content</$tag>";
        }
        catch(RuntimeException $e) {
            echo $e->getMessage();
        }
	}
}
