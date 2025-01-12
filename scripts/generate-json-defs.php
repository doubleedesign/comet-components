<?php

use Doubleedesign\Comet\Components\HasAllowedTags;
use Doubleedesign\Comet\Components\Tag;

/**
 * This script generates JSON files that summarise the details of component classes written in PHP.
 * They are intended to be used for story generation and testing integrations (e.g., comparing WordPress block.json definitions).
 * Usage: php generate-json-defs.php to generate or regenerate all
 *      php generate-json-defs.php --component MyComponent to generate or regenerate a specific component
 */
class ComponentClassesToJsonDefinitions {
	private string $directory;
	private array $processedClasses = [];
	private ReflectionClass $currentClass;

	public function __construct() {
		require_once(__DIR__ . '/../vendor/autoload.php');
		$this->directory = dirname(__DIR__, 1) . '\src\components';
	}

	public function runAll(): void {
		// Get all PHP files in the directory
		/** @noinspection PhpUnhandledExceptionInspection */
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->directory));

		foreach ($files as $file) {
			if ($file->isFile() && $file->getExtension() === 'php') {
				$this->processFile($file->getPathname());
			}
		}
	}


	/** @noinspection PhpUnhandledExceptionInspection */
	public function runSingle($component): void {
		$filePath = $this->directory . '\\' . $component . '\\' . $component . '.php';
		if (!file_exists($filePath)) {
			throw new RuntimeException("Component class $component not found");
		}

		$this->processFile($filePath);
	}

	private function processFile(string $filePath): void {
		// Get file contents
		$content = file_get_contents($filePath);

		// Extract namespace if exists
		$namespace = '';
		if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
			$namespace = $matches[1] . '\\';
		}

		// Extract class name
		if (preg_match('/class\s+(\w+)/', $content, $matches)) {
			$className = $namespace . $matches[1];

			try {
				// Collect the data about the class
				$reflectionClass = new ReflectionClass($className);
				$result = $this->analyseClass($reflectionClass);

				// Export the data to a JSON file
				$outputPath = str_replace('.php', '.json', $reflectionClass->getFileName());
				$this->exportToJson($outputPath, $result);
				print_r("Exported component definition JSON to $outputPath\n");
			}
			catch (ReflectionException $e) {
				error_log("Error analyzing class $className: " . $e->getMessage());
			}
		}
	}

	private function analyseClass(ReflectionClass $reflectionClass): array {
		$className = $reflectionClass->getName();
		if (isset($this->processedClasses[$className])) {
			return $this->processedClasses[$className];
		}

		$properties = [];

		foreach ($reflectionClass->getProperties() as $property) {
			if ($this->getVisibility($property) !== 'private') {
				$propertyType = $this->getPropertyType($property);
				$propertyName = $property->getName();

				// If this is the attributes property, and its type is an *Attributes one,
				// add all the nested attributes to the $properties array
				if ($propertyName === 'attributes' && str_ends_with($propertyType['type'], 'Attributes')) {
					try {
						$attributesClass = new ReflectionClass($propertyType['type']);
						foreach ($attributesClass->getProperties() as $attrProperty) {
							if ($this->getVisibility($attrProperty) !== 'private') {
								$properties[$attrProperty->getName()] = $this->getPropertyType($attrProperty);
							}
						}
					}
					catch (ReflectionException $e) {
						error_log("Error analyzing attributes: " . $e->getMessage());
					}
				}
				else {
					$properties[$propertyName] = $propertyType;
				}
			}
		}

		$result = [
			'name'       => array_reverse(explode('\\', $className))[0],
			'attributes' => array_filter($properties, function ($key) {
				return !in_array($key, ['rawAttributes', 'content', 'bladeFile', 'shortName']);
			}, ARRAY_FILTER_USE_KEY)
		];

		if(isset($properties['content'])) {
			$result['content'] = $properties['content'];
		}

		if(array_reverse(explode('\\', $className))[0] === 'Image') {
			unset($result['attributes']['tag']);
		}

		$this->processedClasses[$className] = $result; // Mark as processed to prevent infinite recursion

		return $result;
	}

	private function getVisibility(ReflectionProperty $property): string {
		if ($property->isPrivate()) return 'private';
		if ($property->isProtected()) return 'protected';
		return 'public';
	}


	/**
	 * Extracts the type of a property, including whether it's required, the default value, and the description.
	 * @param ReflectionProperty $property
	 * @return array
	 */
	private function getPropertyType(ReflectionProperty $property): array {
		$this->currentClass = $property->getDeclaringClass();

		$required = !$property->getType()->allowsNull();
		$defaultValue = $property->hasDefaultValue() ? $property->getDefaultValue() : null;
		$type = $property->getType();
		$description = null;
		$supportedValues = null;
		$result = $this->processPropertyType($type);

		// Get type details from docblock if available
		$docComment = $property->getDocComment();
		if ($docComment && preg_match('/@description\s+(.+)/', $docComment, $matches)) {
			$description = trim($matches[1]);
		}
		if ($docComment && preg_match('/@supported-values\s+(.+)/', $docComment, $matches)) {
			$supportedValues = array_map('trim', explode(',', $matches[1]));
		}

		// Use type from docblock if specified, to use declared types like array<string>
		if ($docComment && preg_match('/@var\s+([^\s]+)/', $docComment, $matches)) {
			$type = trim($matches[1]);
		}
		else {
			$type = $result['type'];
		}

		// Supported values may be set in ways other than docblock - e.g., enum values
		// If those are returned from processPropertyType, use them
		if (isset($result['supported'])) {
			$supportedValues = $result['supported'];
		}

		// $required takes care of these rather than having them in the field names
		$trimmedType = str_replace('?', '', $type);
		$trimmedType = str_replace('|null', '', $trimmedType);

		$result = [
			'type'        => $trimmedType,
			'description' => $description,
			'required'    => $required,
			'supported'   => $supportedValues,
			'default'     => $defaultValue
		];

		return array_filter($result, fn($value) => $value !== null && $value !== false, ARRAY_FILTER_USE_BOTH);
	}


	/**
	 * Processes the type of a property, returning an array with the short type name (no namespace)
	 * and supported values if it's an enum.
	 * @param ReflectionNamedType $type
	 *
	 * @return array|string[]
	 */
	private function processPropertyType(ReflectionNamedType $type): array {
		$typeName = $type->getName();

		if (!class_exists($typeName)) {
			return ['type' => $typeName];
		}

		$reflectionType = new ReflectionClass($typeName);
		$namespace = $reflectionType->getNamespaceName();

		// If it's a Tag property and the class uses HasAllowedTags, get the allowed tags
		if ($typeName === Tag::class && $this->classUsesTraitRecursive($this->currentClass, HasAllowedTags::class)) {
			try {
				$allowedTags = $this->currentClass->getMethod('get_allowed_html_tags')->invoke(null);
				$supportedValues = array_map(function ($tag) {
					return $tag->value;
				}, $allowedTags);

				return [
					'type'      => str_replace("$namespace\\", '', $typeName),
					'supported' => array_values($supportedValues)
				];
			}
			catch (ReflectionException $e) {
				error_log($e->getMessage());
			}
		}

		if ($reflectionType->isEnum()) {
			$cases = $reflectionType->getConstants();
			$supportedValues = array_map(function ($case) {
				return $case->value;
			}, $cases);

			return [
				'type'      => str_replace("$namespace\\", '', $typeName),
				'supported' => array_values($supportedValues)
			];
		}

		// If it's not an enum or the class doesn't exist, return the original type name
		return ['type' => $typeName];
	}


	/**
	 * Check if a class uses a trait, including traits used by parent classes
	 */
	private function classUsesTraitRecursive(ReflectionClass $class, string $traitName): bool {
		do {
			if (in_array($traitName, array_keys($class->getTraits()))) {
				return true;
			}
		} while ($class = $class->getParentClass());

		return false;
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
}


// Usage: php generate-json-defs.php
//        or php generate-json-defs.php --component MyComponent
try {
	$instance = new ComponentClassesToJsonDefinitions();
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
