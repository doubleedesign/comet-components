<?php
namespace Doubleedesign\Comet\Components;
use Doubleedesign\Comet\Services\BladeService;
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

		if($this->textAlign) {
			$styles['text-align'] = $this->textAlign->value;
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

        try {
            echo $blade->make($this->bladeFile, [
                'tag'        => $this->tag->value,
                'attributes' => $this->get_html_attributes(),
                'content'    => Utils::sanitise_content($this->content)
            ])->render();
        }
        catch (Exception $e) {
            error_log(print_r($e, true));
        }
    }
}
