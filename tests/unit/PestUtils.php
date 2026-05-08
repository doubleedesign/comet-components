<?php
namespace Doubleedesign\Comet\TestUtils;
use DOMDocument;
use DOMElement;

class PestUtils {

    public static function getElementsByClassName(DOMDocument|DomElement $dom, string $className, bool $reIndex = true): array {
        $nodes = iterator_to_array($dom->getElementsByTagName('*'));

        $elements = array_filter($nodes, function($element) use ($className) {
            return in_array($className, explode(' ', $element->getAttribute('class')));
        });

        return $reIndex ? array_values($elements) : $elements;
    }

    public static function getHtmlHierarchy(DOMDocument|DomElement $dom, $depth = 5): array {
        $hierarchy = array();

        $counter = 0;
        $element = $dom;
        while ($counter < $depth) {
            if (!$element->childNodes->length) break;

            $hierarchy[] = !empty($element->className) ? "{$element->nodeName}.{$element->className}" : $element->nodeName;

            $children = array_values(array_filter(iterator_to_array($element->childNodes), fn($child) => $child instanceof DOMElement));
            $element = $children[0];
            $counter++;
        }

        return array_values(array_filter($hierarchy, fn($item) => !str_starts_with($item, 'body')));
    }
}
