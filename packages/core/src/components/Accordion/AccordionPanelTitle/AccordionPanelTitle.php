<?php
namespace Doubleedesign\Comet\Core;
use Exception;

class AccordionPanelTitle extends TextElement {
	use HasAllowedTags;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::SUMMARY];
	}

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Accordion.AccordionPanelTitle.accordion-panel-title');
		$this->classes = [$this->shortName];
		$this->tag = Tag::SUMMARY;
	}
}
