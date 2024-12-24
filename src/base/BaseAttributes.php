<?php
namespace Doubleedesign\Comet\Components;

class BaseAttributes extends CoreAttributes {
	   protected ?Tag $tag = null;

    public function __construct(array $attrs) {
		parent::__construct($attrs);
        $this->tag = isset($attrs['tagName']) ? Tag::tryFrom($attrs['tagName']) : Tag::DIV;
    }

    public function get_tag(): string {
        return $this->tag->value;
    }
	
	/**
	 * Get the valid/supported HTML attributes for the given tag
	 * @return string[]
	 */
	protected function get_valid_html_attributes(): array {
		return $this->tag->get_valid_attributes();
	}
	
	public function get_filtered_attributes(): array {
		return array_merge(
			// Utilise the core attributes filtering
			parent::get_filtered_attributes(),
		
			// Also keep valid HTML attributes for this tag
			array_filter($this->rawAttributes, function ($key) {
				return in_array($key, $this->get_valid_html_attributes());
			}, ARRAY_FILTER_USE_KEY)
		);
	}
}
