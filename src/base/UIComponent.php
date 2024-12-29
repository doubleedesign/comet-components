<?php
namespace Doubleedesign\Comet\Components;
use RuntimeException;

abstract class UIComponent {
	protected BaseAttributes $attributes;
	
	/** @var string $content - plain text or innerHTML */
	protected string $content;
	
	/** @var UIComponent[] $innerComponents */
	protected array $innerComponents;
	
	/**
	 * UIComponent constructor
	 *
	 * @param array $attributes
	 * @param string $content
	 * @param UIComponent[] $innerComponents
	 */
	function __construct(array $attributes, string $content = '', array $innerComponents = []) {
		$this->attributes = new BaseAttributes($attributes);
		$this->content = $content;
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
