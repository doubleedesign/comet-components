<?php
namespace Doubleedesign\Comet\Core;
use Exception;

abstract class TextElement extends Renderable {
	/**
	 * @var string $content
	 * @description plain text or basic HTML
	 */
	protected string $content;
	/**
	 * @var Alignment|null $textAlign
	 */
	protected ?Alignment $textAlign = Alignment::MATCH_PARENT;
	/**
	 * @var string|null $textColor
	 */
	protected ?string $textColor = null;

	function __construct(array $attributes, string $content, string $bladeFile) {
		parent::__construct($attributes, $bladeFile);
		$this->shortName = array_reverse(explode($this->bladeFile, '.'))[0];
		$this->content = $content;
		$this->textAlign = isset($attributes['textAlign']) ? Alignment::tryFrom($attributes['textAlign']) : null;
		$this->textColor = isset($attributes['textColor']) ?? null;
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

		return $styles;
	}

	/**
	 * Get the valid/supported classes for this component
	 * @return array<string>
	 */
	function get_filtered_classes(): array {
		$current_classes = parent::get_filtered_classes();

		if ($this->textColor) {
			$current_classes[] = 'text-' . $this->textColor;
		}

		return $current_classes;
	}

	/**
	 * Default render method (child classes may override this)
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
