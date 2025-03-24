<?php
namespace Doubleedesign\Comet\Core;
use DOMDocument, DOMElement, DOMNode, ReflectionClass, Exception;
use Doubleedesign\Comet\WordPress\PreprocessedHTML;

class TychoService {
	/**
	 * @var DOMDocument|null The DOM parser
	 */
	private static ?DOMDocument $dom = null;

	/**
	 * Parse a JSX-like template string into component objects
	 * @param string $template The template string to parse
	 * @param array $variables Variables to make available in the template scope // TODO: This is not implemented yet
	 * @throws Exception If the template is invalid
	 *
	 * @return Renderable The root component object
	 */
	public static function parse(string $template, array $variables = []): mixed {
		if (self::$dom === null) {
			self::$dom = new DOMDocument();
		}

		// Extract variables to make them available in the scope
		extract($variables);

		// Clean the template and prepare for parsing
		$template = trim($template);

		// Disable error reporting temporarily as DOMDocument will complain about HTML5 tags
		$internalErrors = libxml_use_internal_errors(true);

		// Add a root element to ensure we have valid XML
		self::$dom->loadXML("<root>{$template}</root>", LIBXML_NOERROR);

		// Clear errors and restore reporting
		libxml_clear_errors();
		libxml_use_internal_errors($internalErrors);

		// Get the first child (should be our component)
		$rootNode = self::$dom->documentElement->firstChild;

		if (!$rootNode) {
			throw new Exception("No valid component found in template");
		}

		// Convert the DOM node to a component
		return self::node_to_component($rootNode, $variables);
	}

	/**
	 * Convert a DOM node to a component object
	 * @param DOMNode $node The DOM node to convert
	 * @param array $variables Variables from the parent scope
	 * @throws Exception If the component class is not found
	 *
	 * @return Renderable The component object
	 */
	private static function node_to_component(DOMNode $node, array $variables = []): mixed {
		// Wrap plain text content in a paragraph and return it
		if($node->nodeType === XML_TEXT_NODE) {
			return new Paragraph([], $node->nodeValue);
		}

		$tagName = $node->nodeName;
		$ComponentClass = "Doubleedesign\\Comet\\Core\\$tagName";

		if(!class_exists($ComponentClass)) {
			// If the tagName is all lowercase, this is probably a basic HTML element
			// This does some rudimentary matching, but ideally we should just output these as-is.
			// This needs a way to identify if there are Comet components nested in it and process them accordingly though.
			if (strtolower($tagName) === $tagName) {
				$ComponentClass = match ($tagName) {
					'div' => 'Doubleedesign\\Comet\\Core\\Group',
					default => throw new Exception("Component class $ComponentClass not found"),
				};
			}
		}

		// Extract attributes
		$attributes = self::extract_attributes($node, $variables);

		// If this is a basic text component, return the component with string content
		$reflection = new ReflectionClass($ComponentClass);
		$parentClass = $reflection->getParentClass()->getName();
		if($parentClass === 'Doubleedesign\Comet\Core\TextElement' || $parentClass === 'Doubleedesign\Comet\Core\TextElementExtended') {
			$content = self::$dom->saveHTML($node); // Gets the inner HTML of the node including inline tags like strong and em
			return new $ComponentClass($attributes, $content);
		}

		// Otherwise, process inner components while maintaining nested hierarchy
		$children = [];
		if ($node->hasChildNodes()) {
			foreach ($node->childNodes as $childNode) {
				// Skip pure whitespace text nodes
				if ($childNode->nodeType === XML_TEXT_NODE && trim($childNode->nodeValue) === '') {
					continue;
				}

				// Process each inner component recursively
				$children[] = self::node_to_component($childNode, $variables);
			}
		}

		// Create and return the component with its properly nested children
		return new $ComponentClass($attributes, $children);
	}

	/**
	 * Extract attributes from a DOM element
	 * @param DOMNode $node The DOM node
	 * @param array $variables Variables from the parent scope
	 *
	 * @return array The extracted attributes
	 */
	private static function extract_attributes(DOMNode $node, array $variables = []): array {
		if (!$node->hasAttributes()) {
			return [];
		}

		$attributes = [];

		/** @var DOMElement $node */
		foreach ($node->attributes as $attr) {
			$name = $attr->nodeName;
			$value = htmlspecialchars($attr->nodeValue);

			// Type cast booleans
			if ($value === 'true' || $value === 'false') {
				$value = $value === 'true';
			}

			// Type cast numbers
			if(is_numeric($value)) {
				$value = str_contains($value, '.') ? (float)$value : (int)$value;
			}

			// TODO: Handle arrays such as inline styles

			$attributes[$name] = $value;
		}

		return $attributes;
	}
}
