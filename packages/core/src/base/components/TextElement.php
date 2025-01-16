<?php
namespace Doubleedesign\Comet\Core;
use Exception;

abstract class TextElement extends Renderable {
	/**
	 * @var Alignment|null $textAlign
	 */
	protected ?Alignment $textAlign = Alignment::MATCH_PARENT;
	/**
	 * @var string $content
	 * @description plain text or basic HTML
	 */
	protected string $content;

	function __construct(array $attributes, string $content, string $bladeFile) {
		parent::__construct($attributes, $bladeFile);
		$this->shortName = array_reverse(explode($this->bladeFile, '.'))[0];
		$this->content = $content;
		$this->textAlign = isset($attributes['textAlign']) ? Alignment::tryFrom($attributes['textAlign']) : null;
	}

	/**
	 * Collect the inline styles using the relevant supported attributes
	 *
	 * @return array<string, string>
	 */
	function get_inline_styles(): array {
		$styles = [];

		if ($this->textAlign) {
			$styles['text-align'] = $this->textAlign->value;
		}

		if($this->style) {
			$color =
				$this->style['color']
				?? $this->style['elements']['link']['color']['text'] // WordPress format
				?? null;

			if ($color) {
				// If it's a hex colour, leave as-is
				if (preg_match('/^#[0-9A-F]{6}$/i', $color)) {
					$styles['color'] = $color;
				}
				else {
					// Transform expected formats to CSS variable format
					// WordPress format is like var:preset|color|vivid-cyan-blue
					$stripped = str_replace('var:', '', $color);
					$color = str_replace('|', '--', $stripped);
					// Hack in if we're in WP context
					if (defined('WPINC')) {
						$color = "wp--$color";
					}

					$styles['color'] = "var(--$color)";
				}
			}
		}

		return $styles;
	}

	/**
	 * Default render method (child classes may override this)
	 *
	 * @return void
	 */
	public function render(): void {
		$blade = BladeService::getInstance();
		$attrs = $this->get_html_attributes();
		$classes = $attrs['className'] ?? '';

		try {
			echo $blade->make($this->bladeFile, [
				'tag'        => $this->tag->value,
				'classes'    => $classes,
				'attributes' => array_filter($attrs, fn($k) => $k !== 'className', ARRAY_FILTER_USE_KEY),
				'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
			])->render();
		}
		catch (Exception $e) {
			error_log(print_r($e, true));
		}
	}
}
