<?php
namespace Doubleedesign\Comet\Core;

/**
 * Call-To-Action component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Highlight important information and prompt the user to take action.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE])]
#[DefaultTag(Tag::SECTION)]
class CallToAction extends LayoutComponent {
    use BackgroundColorMulti;
    use ColorTheme;

    /**
     * @param  array  $attributes
     * @param  array<Heading|Paragraph|ButtonGroup|PreprocessedHTML>  $innerComponents
     */
    public function __construct(array $attributes, array $innerComponents) {
        $this->set_background_colors($attributes);

        $content = new Group([
            'context'         => 'call-to-action',
            'shortName'       => 'content',
            'backgroundColor' => $this->get_background_colors()->inner->value ?? null,
            'colorTheme'      => $attributes['colorTheme'] ?? null,
        ], $innerComponents);

        // don't pass colorTheme to parent, it has already been applied to the inner group
        unset($attributes['colorTheme']);
        // only pass the outer background color to parent
        $attributes['backgroundColor'] = $this->get_background_colors()->outer ?? null;
        unset($attributes['backgroundColors']);

        parent::__construct($attributes, [$content], 'components.CallToAction.call-to-action');
    }

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if($this->get_background_colors()->outer !== null) {
			$attributes['data-background'] = $this->get_background_colors()->outer;
		}

		if(isset($this->colorTheme)) {
			$attributes['data-color-theme'] = $this->colorTheme;
		}

		return $attributes;
	}
}
