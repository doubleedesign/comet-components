<?php
namespace Doubleedesign\Comet\Core;

/**
 * SiteFooter component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render a footer with inner components such as a Menu.
 */
#[AllowedTags([Tag::FOOTER])]
#[DefaultTag(Tag::FOOTER)]
class SiteFooter extends LayoutComponent {
	use BackgroundColor;

    /**
     * @var array{siteName: string, startYear: int, endYear: int} $copyright Name of the site/organisation/content owner and the years to display in the copyright notice.
     */
    protected array $copyright;

    /**
     * @var array{authorName: string, authorUrl: string} $devCredit Name and URL of the developer to display in the footer.
     */
    protected array $devCredit;

    public function __construct(array $attributes, array $innerComponents) {
		$this->set_background_color($attributes);
        $this->copyright = $attributes['copyright'] ?? [];
        $this->devCredit = $attributes['devCredit'] ?? [];

        $creditText = '';
        if ($this->copyright) {
            $creditText .= <<<HTML
				<small>Content copyright &copy; {$this->copyright['startYear']}-{$this->copyright['endYear']} {$this->copyright['siteName']}.</small>
			HTML;
        }

        if ($this->devCredit) {
            $creditText .= <<<HTML
				<small>Website by <a href="{$this->devCredit['authorUrl']}" target="_blank">{$this->devCredit['authorName']}</a>.</small>
			HTML;
        }

        $creditNotice = new Group([
            'context'   => $attributes['context'] ?? 'site-footer',
            'shortName' => 'credits',
        ], [
            new PreprocessedHTML([], Utils::sanitise_content($creditText)),
        ]);

        parent::__construct($attributes, [...$innerComponents, $creditNotice], 'components.SiteFooter.site-footer');
    }

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if ($this->get_background_color() !== null) {
			$attributes['data-background'] = $this->get_background_color()->value;
		}


		return $attributes;
	}
}
