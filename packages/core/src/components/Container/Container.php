<?php
namespace Doubleedesign\Comet\Core;

class Container extends LayoutComponent {
	use HasAllowedTags;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::SECTION, Tag::MAIN, Tag::DIV];
	}

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Container.container');
	}

	protected function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();
		$replacements = [
			'is-style-wide'      => 'container--wide',
			'is-style-fullwidth' => 'container--fullwidth',
			'is-style-narrow'    => 'container--narrow',
		];

		array_walk($classes, function (&$value) use ($replacements) {
			if (isset($replacements[$value])) {
				$value = $replacements[$value];
			}
		});
		
		return $classes;
	}
}
