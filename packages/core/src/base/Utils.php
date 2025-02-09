<?php
namespace Doubleedesign\Comet\Core;
use HTMLPurifier;
use HTMLPurifier_Config;

class Utils {

	/**
	 * If a string value has spaces, convert it to kebab case
	 * @param string $value
	 * @return string
	 */
	public static function kebab_case(string $value): string {
		// Account for PascalCase
		$value = preg_replace('/([a-z])([A-Z])/', '$1 $2', $value);

		// Convert slashes so this works on namespaced block names
		$value = str_replace('/', '-', $value);

		// Also replace double underscores (used for BEM naming manipulations)
		$value = str_replace('__', '-', $value);

		// Convert whitespace to hyphens and make lowercase
		$value = trim(strtolower(preg_replace('/\s+/', '-', $value)));

		// Trim any leading or trailing hyphens
		return trim($value, '-');
	}

	/**
	 * Convert string value to PascalCase
	 * @param string $value
	 * @return string
	 */
	public static function pascal_case(string $value): string {
		$value = str_replace(['-', '_'], ' ', $value);
		return str_replace(' ', '', ucwords($value));
	}

	/**
	 * Sanitise content string using HTMLPurifier
	 * @param string $content The input content to be sanitised
	 * @param ?array<Tag> $allowedTags
	 *
	 * @return string The sanitised content.
	 */
	public static function sanitise_content(string $content, ?array $allowedTags = null): string {
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);

		if (!$allowedTags) {
			return $purifier->purify($content);
		}

		// HTML Purifier does have an option to pass allowed tags to it,
		// but in that case we'd also have to pass all their attributes, which may be overkill
		$allowedTagsAsTags = array_map(fn($tag) => "<$tag->value>", $allowedTags);
		$updatedContent = strip_tags($content, $allowedTagsAsTags);
		return $purifier->purify($updatedContent);
	}

	/**
	 * Convert lowercase, kebab-case, and WP block format component names to PascalCase,
	 * account for components with reserved words as names having "Component" appended (e.g., ListComponent),
	 * account for some other edge cases where I really really want to rename a component,
	 * and add the namespace to return the full class name
	 * @param string $name
	 * @return string
	 */
	public static function get_class_name(string $name): string {
		$reserved_words = ['List'];

		$shortName = array_reverse(explode('/', $name))[0];
		$className = Utils::pascal_case($shortName);
		if (in_array($className, $reserved_words)) {
			$className = $className . 'Component';
		}

		if ($shortName === 'buttons') {
			$className = 'ButtonGroup';
		}

		return sprintf('%s\\%s', __NAMESPACE__, $className);
	}

	public static function array_flat(array $array): array {
		// This is an array of arrays and needs to be flattened
		if(is_array($array[0])) {
			return array_merge(...$array);
		}

		return $array;
	}

	public static function get_array_depth($array) {
		$max_depth = 1;

		foreach ($array as $value) {
			if (is_array($value)) {
				$depth = self::get_array_depth($value) + 1;
				$max_depth = max($max_depth, $depth);
			}
		}

		return $max_depth;
	}
}
