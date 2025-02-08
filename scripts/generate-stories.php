<?php
/**
 * This script generates JSON-format Storybook story files for use with @storybook/server-webpack5.
 * They use component JSON definitions generated by generate-json-defs.php.
 * Usage: php scripts/generate-stories.php to generate or regenerate all
 *        php scripts/generate-stories.php --component MyComponent to generate or regenerate stories for a specific component
 * NOTE: This script requires PHP 8.4+.
 */

class ComponentStoryGenerator {
	private string $directory;
	private string $outputDirectory;

	public function __construct() {
		$this->directory = dirname(__DIR__, 1) . '\packages\core\src\components';
		$this->outputDirectory = dirname(__DIR__, 1) . '\test\browser\stories';
	}

	public function runAll(): void {
		// Get all JSON files in the directory
		/** @noinspection PhpUnhandledExceptionInspection */
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->directory));

		foreach ($files as $file) {
			if ($file->isFile() && $file->getExtension() === 'json') {
				$this->processFile($file->getPathname());
			}
		}
	}

	/** @noinspection PhpUnhandledExceptionInspection */
	public function runSingle($component): void {
		$filePath = $this->directory . '\\' . $component . '\\' . $component . '.json';
		if ($component === 'Button') {
			$filePath = $this->directory . '\\ButtonGroup\\Button\\Button.json';
		}
		if (!file_exists($filePath)) {
			throw new RuntimeException("Component class $component not found");
		}

		$this->processFile($filePath);
	}

	private function processFile(string $filePath): void {
		// Read JSON from the file
		$content = file_get_contents($filePath);
		$json = json_decode($content, true);
		$shortName = self::kebab_case($json['name']);
		// Exclude some attributes handled internally , not suitable for Storybook, or not suitable to autogenerate
		$attributes = array_diff_key($json['attributes'], array_flip(['id', 'style', 'context']));
		// Get the category from the WordPress plugin's block support JSON file
		$category = ucfirst(self::get_category_from_wordpress_plugin_json($json['name'])) ?? 'Uncategorised';

		$storyFile = [
			'title'      => sprintf('Components/%s/%s', $category, $json['name']),
			'parameters' => [
				'docs'   => [
					"description" => [
						"component" => self::get_description_from_wordpress_block_json($json['name'])
					]
				],
				'server' => [
					"id"     => $shortName,
					'url'    => sprintf('http://localhost:6001/components/%s.php', strtolower($shortName)),
					'params' => [
						"__debug" => true
					]
				]
			]
		];

		$storyFile['args'] = array_reduce(array_keys($attributes), function ($acc, $attr) use ($attributes) {
			// Do not show a specified class name by default (the auto-generated ones within the component will still be where they need to be)
			if ($attr === 'classes') return $acc;

			$acc[$attr] = $attributes[$attr]['default'] ?? '';
			return $acc;
		}, []);

		$storyFile['argTypes'] = array_reduce(array_keys($attributes), function ($acc, $attr) use ($attributes) {
			// Special handling for classes - include "supported" values but not the default or generated classes
			// This allows auto-generation of options like "accent heading" and "lead paragraph" without also including BEM classnames and the like
			if ($attr === 'classes' && isset($attributes[$attr]['supported'])) {
				$acc['class'] = [
					'description' => $attributes[$attr]['description'] ?? '',
					'control'     => ['type' => 'select'],
					'options'     => ['', ...$attributes[$attr]['supported']],
					'table'       => [
						'defaultValue' => ['summary' => ''],
						'type'         => ['summary' => 'string']
					]
				];

				return $acc;
			}

			$data = $attributes[$attr];
			$propType = isset($data['supported']) ? 'array' : $data['type'];

			$acc[$attr] = [
				'description' => $data['description'] ?? '',
				'control'     => $attr === 'classes' ? false : ['type' => self::propertyTypeToControl($propType)],
				'table'       => [
					'defaultValue' => [
						'summary' => $data['default'] ?? ''
					],
					'type'         => [
						'summary' => $data['type']
					]
				]
			];

			if ($propType === 'array') {
				$acc[$attr]['options'] = $data['supported'];
			}

			return $acc;
		}, []);

		$stories = [
			[
				'name' => 'Playground',
				'args' => [],
				'tags' => ['docsOnly']
			]
		];

		// Collect boolean attributes to generate variations for each of the options
		$booleanAttributes = array_filter($attributes, function ($attr) {
			return $attr['type'] === 'bool';
		});

		foreach ($storyFile['argTypes'] as $propName => $settings) {
			// Properties that we don't want individual stories for
			if (in_array($propName, ['tagName', 'classes', 'backgroundColor', 'textAlign'])) continue;

			// Generate stories for properties with options specified
			if (!isset($settings['options'])) continue;

			foreach ($settings['options'] as $option) {
				// Adjust label for "is-style-*" class names
				$displayName = $option;
				if (str_starts_with($option, 'is-style-')) {
					$displayName = substr($option, 9) . ' style';
				}
				$stories[] = [
					'name' => ucfirst($displayName),
					'args' => [
						$propName => $option
					]
				];

				if (!empty($booleanAttributes)) {
					foreach ($booleanAttributes as $boolAttrName => $boolAttrSettings) {
						$boolAttrDisplayName = $boolAttrName;
						// Adjust label for things like "isOutline" be "Outline" etc
						if (str_starts_with($boolAttrName, 'is')) {
							$boolAttrDisplayName = substr($boolAttrName, 2);
						}

						$stories[] = [
							'name' => ucfirst($option) . ' - ' . $boolAttrDisplayName,
							'args' => [
								$propName     => $option,
								$boolAttrName => true
							]
						];
					}
				}
			}
		}
		$storyFile['stories'] = array_values(array_filter($stories, function ($story) {
			return !empty($story['name']);
		}));

		// Export the processed data as a JSON file
		$outputPath = $this->outputDirectory . '\\' . strtolower($shortName) . '.stories.json';
		$this->exportToJson($outputPath, $storyFile);
	}

	/**
	 * Exports the processed data as a JSON file to the specified output path.
	 * @param string $outputPath Where to save the file.
	 * @param array $data The array of data to be encoded into JSON and exported.
	 *
	 * @return void
	 */
	public function exportToJson(string $outputPath, array $data): void {
		$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		file_put_contents($outputPath, $json);
	}

	private static function propertyTypeToControl($propType): string {
		return match ($propType) {
			'number' => 'number',
			'bool' => 'boolean',
			'array', 'Tag', 'Alignment', 'AspectRatio' => 'select',
			default => 'text',
		};
	}

	private static function kebab_case(string $value): string {
		// Account for PascalCase
		$value = preg_replace('/([a-z])([A-Z])/', '$1 $2', $value);

		// Convert whitespace to hyphens and make lowercase
		return trim(strtolower(preg_replace('/\s+/', '-', $value)));
	}

	private static function get_description_from_wordpress_block_json($block_name): string {
		// Look for my blocks first
		$cometBlocksDir = dirname(__DIR__, 1) . '/packages/comet-components-wp/src/blocks';
		$blockJsonPath = $cometBlocksDir . '/' . $block_name . '/block.json';
		if (file_exists($blockJsonPath)) {
			$blockJson = json_decode(file_get_contents($blockJsonPath), true);
			return $blockJson['description'];
		}

		// Then core blocks
		$coreBlocksDir = dirname(__DIR__, 1) . '/wordpress/wp-includes/blocks'; // WP installed as Composer dependency
		$blockJsonPath = $coreBlocksDir . '/' . $block_name . '/block.json';
		if (file_exists($blockJsonPath)) {
			$blockJson = json_decode(file_get_contents($blockJsonPath), true);
			return $blockJson['description'];
		}

		return '';
	}

	private static function get_category_from_wordpress_plugin_json($block_name): string {
		$json = file_get_contents(dirname(__DIR__, 1) . '/packages/comet-components-wp/src/block-support.json');
		$categories = json_decode($json, true)['categories'];

		return array_find($categories, function ($category) use ($block_name) {
			$block_name_kebab = self::kebab_case($block_name);
			return in_array("comet/$block_name_kebab", $category['blocks']) || in_array("core/$block_name_kebab", $category['blocks']);
		})['slug'] ?? 'Uncategorised';
	}

	private static function move_default_to_top(array $items): array {
		usort($items, function ($a, $b) {
			if ($a === 'default') return -1;
			if ($b === 'default') return 1;

			return 0;
		});

		return $items;
	}
}


// Usage: php generate-stories.php
//     or php generate-stories.php --component MyComponent
try {
	$instance = new ComponentStoryGenerator();
	if (isset($argv[1]) && $argv[1] === '--component') {
		$instance->runSingle($argv[2]);
	}
	else {
		$instance->runAll();
	}
	echo "Done!\n";
}
catch (Exception $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
