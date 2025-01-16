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
		// If no whitespace characters, return as is (preserves snake_case and PascalCase)
		if (!preg_match('/\s/', $value)) {
			return $value;
		}

		// Convert whitespace to hyphens and make lowercase
		return trim(strtolower(preg_replace('/\s+/', '-', $value)));
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
	public static function sanitise_content(string $content, array $allowedTags = null): string {
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
	 * Convert lowercase, kebab-case, and WP block format component names to PascalCase
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

		return sprintf('%s\\%s', __NAMESPACE__, $className);
	}
}
