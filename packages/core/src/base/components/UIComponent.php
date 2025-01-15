<?php
namespace Doubleedesign\Comet\Core;
use RuntimeException;

abstract class UIComponent extends Renderable {
	/**
     * @var array<Renderable> $innerComponents
     * @description Inner components to be rendered within this component
     */
	protected array $innerComponents;

	/**
	 * UIComponent constructor
	 * @param array<string, string|int|array|null> $attributes
	 * @param array<Renderable> $innerComponents
     * @param string $bladeFile
	 */
	function __construct(array $attributes, array $innerComponents, string $bladeFile) {
		parent::__construct($attributes, $bladeFile);
        $this->innerComponents = $innerComponents;
	}


    /**
	 * Generic method to render inner content
	 * (child classes may override this)
	 *
	 * @return string
	 * @throws RuntimeException
	 */
	protected function get_inner_content_html(): string {
		$inner_html = array_reduce($this->innerComponents, function ($acc, $component) {
			ob_start();
			$className = Utils::pascal_case(array_reverse(explode('/', $component['blockName']))[0]);
			$fullClassName = __NAMESPACE__ . '\\' . $className;
			if (class_exists($fullClassName)) {
				$component = new $fullClassName($component['attrs'], $component['innerHTML']); // TODO: Do I need to handle innerBlocks here or does innerHTML take care of that?
				$component->render();
				return $acc . ob_get_clean();
			}
			else {
				throw new RuntimeException("UIComponent could not find class $fullClassName to render an inner component");
			}
		}, '');

		ob_start();
		echo $inner_html;
		return ob_get_clean();
	}

}
