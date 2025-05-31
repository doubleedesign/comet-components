<?php
namespace Doubleedesign\Comet\TestUtils;
use DOMDocument, DOMElement, ReflectionClass, ReflectionException;

class PestUtils {

    public static function get_elements_by_class_name(DOMDocument|DomElement $dom, string $className, bool $reIndex = true): array {
        $nodes = iterator_to_array($dom->getElementsByTagName('*'));

        $elements = array_filter($nodes, function($element) use ($className) {
            return in_array($className, explode(' ', $element->getAttribute('class')));
        });

        return $reIndex ? array_values($elements) : $elements;
    }

    /**
     * Helper function to call protected methods on a given object using reflection
     *
     * @throws ReflectionException
     *
     * @noinspection PhpExpressionResultUnusedInspection
     */
    public static function call_protected_method(object $object, string $methodName, array $args = []): mixed {
        $reflection = new ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }
}
