<?php
namespace Doubleedesign\Comet\TestUtils;
use DOMDocument, DOMElement;

class PestUtils {

	public static function getElementsByClassName(DOMDocument|DomElement $dom, string $className, bool $reIndex = true): array {
		$nodes = iterator_to_array($dom->getElementsByTagName('*'));

		$elements = array_filter($nodes, function($element) use ($className) {
			return in_array($className, explode(' ', $element->getAttribute('class')));
		});

		return $reIndex ? array_values($elements) : $elements;
	}
}
