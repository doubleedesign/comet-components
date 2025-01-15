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
		return trim(ucwords(preg_replace('/\s+/', ' ', $value)));
	}

	/**
	 * Implementation of similar to WordPress' esc_attr function
	 * @param $text
	 * @return array|string|null
	 */
	public static function esc_attr($text): array|string|null {
		// Convert special characters to HTML entities
		$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

		// Replace specific characters that need special handling
		$replacements = array('%'  => '%25',    // Percent sign
		                      '\'' => '&#039;', // Single quote
		                      '"'  => '&quot;',  // Double quote
		                      '<'  => '&lt;',    // Less than
		                      '>'  => '&gt;',    // Greater than
		                      '&'  => '&amp;',   // Ampersand
		                      "\r" => '',       // Remove carriage returns
		                      "\n" => '',       // Remove newlines
		                      "\t" => ' '       // Convert tabs to spaces
		);

		// Apply replacements
		$text = strtr($text, $replacements);

		// Remove any null bytes
		$text = str_replace("\0", '', $text);

		// Remove control characters and return the result
		return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $text);
	}

    /**
     * Sanitise content string using HTMLPurifier
     * @param string $content The input content to be sanitised.
     * @return string The sanitised content.
     */
    public static function sanitise_content($content): string {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($content);
    }
}
