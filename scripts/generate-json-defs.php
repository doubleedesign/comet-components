<?php
use Doubleedesign\Comet\Core\AllowedTags;
use Doubleedesign\Comet\Core\DefaultTag;
use Doubleedesign\Comet\Core\HasAllowedTags;
use Doubleedesign\Comet\Core\Tag;

/**
 * This script generates JSON files that summarise the details of component classes written in PHP.
 * They are intended to be used for story generation and testing integrations (e.g., comparing WordPress block.json definitions).
 * Usage: php generate-json-defs.php to generate or regenerate all
 *      php generate-json-defs.php --component MyComponent to generate or regenerate a specific component
 *  NOTE: This script requires PHP 8.4+.
 */
class ComponentClassesToJsonDefinitions {
	private string $mainComponentDirectory;
	private string $baseComponentDirectory;
	private array $processedClasses = [];
	private ReflectionClass $currentClass;
	private ReflectionClass $declaringClass;

	public function __construct() {
		require_once(__DIR__ . '/../vendor/autoload.php');
		$this->mainComponentDirectory = dirname(__DIR__, 1) . '\packages\core\src\components';
		$this->baseComponentDirectory = dirname(__DIR__, 1) . '\packages\core\src\base\components';
	}

	public function runAll(): void {
		// Get all PHP files in the directory
		/** @noinspection PhpUnhandledExceptionInspection */
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->mainComponentDirectory));

		foreach ($files as $file) {
			if ($file->isFile() && $file->getExtension() === 'php') {
				$this->processFile($file->getPathname());
			}
		}
	}


	/** @noinspection PhpUnhandledExceptionInspection */
	public function runSingle($component): void {
		// First try direct path
		$filePath = $this->mainComponentDirectory . '\\' . $component . '\\' . $component . '.php';
		if (file_exists($filePath)) {
			$this->processFile($filePath);
			return;
		}

		// If not found, try to find base folder:
		// try by splitting PascalCase into words - e.g., AccordionPanel is inside Accordion
		preg_match_all('/[A-Z][a-z]*/', $component, $matches);
		$baseFolder = $matches[0][0];
		$filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
		if (file_exists($filePath)) {
			$this->processFile($filePath);
			return;
		}

		// try the other way around, e.g., Button is inside ButtonGroup
		$folders = scandir($this->mainComponentDirectory);
		$baseFolder = array_find($folders, function ($folder) use ($component) {
			return str_starts_with($folder, $component);
		});
		$filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
		if (file_exists($filePath)) {
			$this->processFile($filePath);
			return;
		}

		// try singular to plural, e.g. Column is inside Columns
		$baseFolder = $component . 's';
		$filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
		if (file_exists($filePath)) {
			$this->processFile($filePath);
			return;
		}

		// shortened singular to plural based on PascalCase, e.g. TabPanel is inside Tabs
		$baseFolder = $matches[0][0] . 's';
		$filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
		if (file_exists($filePath)) {
			$this->processFile($filePath);
			return;
		}

		// Specific edge cases
		if (in_array($component, ['ListItem', 'ListItemSimple', 'ListItemComplex'])) {
			if ($component === 'ListItem') {
				$filePath = $this->mainComponentDirectory . '\ListComponent\ListItem\ListItem.php';
			}
			else {
				$filePath = $this->mainComponentDirectory . '\ListComponent\ListItem\\' . $component . "\\$component.php";
			}
			if (file_exists($filePath)) {
				$this->processFile($filePath);
				return;
			}
		}
		else {
			throw new RuntimeException("Component $component not found");
		}
	}

	public function runSingleBase($baseComponent): void {
		$filePath = $this->baseComponentDirectory . '\\' . $baseComponent . '.php';
		if (file_exists($filePath)) {
			$this->processFile($filePath);
		}
		else {
			throw new RuntimeException("Base component $baseComponent not found");
		}
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

	/**
	 * @throws ReflectionException
	 */
	private function analyseClass(ReflectionClass $reflectionClass): array {
		$className = $reflectionClass->getName();
		$parentClass = $reflectionClass->getParentClass() ?? null;

		if (isset($this->processedClasses[$className])) {
			return $this->processedClasses[$className];
		}

		$this->currentClass = $reflectionClass;
		$properties = [];

		foreach ($reflectionClass->getProperties() as $property) {
			if ($this->getVisibility($property) !== 'private') {
				$this->declaringClass = $property->getDeclaringClass(); // get the parent class where the property is declared
				$propertyType = $this->getPropertyType($property);
				$propertyName = $property->getName();

				$properties[$propertyName] = $propertyType;
			}
		}

		$result = [
			'name'       => array_reverse(explode('\\', $className))[0],
			'extends'    => $parentClass
				? ($parentClass->getName() ? array_reverse(explode('\\', $parentClass->getName()))[0] : null)
				: null,
			'abstract'   => $reflectionClass->isAbstract(),
			'attributes' => array_filter($properties, function ($key) {
				return !in_array($key, ['rawAttributes', 'content', 'innerComponents', 'bladeFile', 'shortName']);
			}, ARRAY_FILTER_USE_KEY)
		];

		if (isset($properties['content'])) {
			$result['content'] = $properties['content'];
		}
		if (isset($properties['innerComponents'])) {
			$result['innerComponents'] = $properties['innerComponents'];
		}

		if (array_reverse(explode('\\', $className))[0] === 'Image') {
			unset($result['properties']['tag']);
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
	 * @throws ReflectionException
	 */
	private function getPropertyType(ReflectionProperty $property): array {
		$required = !$property->getType()->allowsNull();
		$type = $property->getType();
		$description = null;
		$defaultValue = $property->hasDefaultValue() ? $property->getDefaultValue() : null;
		$supportedValues = null;
		$content_type = $this->currentClass->hasProperty('innerComponents') ? 'array' : 'string';
		$result = $this->processPropertyType($type);

		// Handle default boolean values
		if ($type->getName() === 'bool' && $defaultValue === false) {
			$defaultValue = 'false';
		}
		else if ($type->getName() === 'bool' && $defaultValue === true) {
			$defaultValue = 'true';
		}

		// If this is the $classes property, compute the actual default classes (e.g. the shortName or BEM name with context)
		if ($property->getName() === 'classes' && !$this->currentClass->isAbstract()) {
			if ($this->currentClass->hasMethod('get_filtered_classes')) {
				try {
					$instance = $this->currentClass->newInstance([], $content_type === 'array' ? [] : '', 'dummy.blade.php');
					$defaultValue = $this->currentClass->getMethod('get_filtered_classes')->invoke($instance);
				}
				catch (ReflectionException $e) {
					// If we can't create an instance of current class, fall back to parent
					if ($this->declaringClass->hasMethod('get_filtered_classes')) {
						$parentInstance = $this->declaringClass->newInstance([], [], 'dummy.blade.php');
						$defaultValue = $this->declaringClass->getMethod('get_filtered_classes')->invoke($parentInstance);
					}
				}
			}
			else if ($this->declaringClass->hasMethod('get_filtered_classes')) {
				// Use parent's method if current class doesn't have it
				$instance = $this->declaringClass->newInstance([], [], 'dummy.blade.php');
				$defaultValue = $this->declaringClass->getMethod('get_filtered_classes')->invoke($instance);
			}
		}

		// Get type details from docblock if available
		$docComment = $property->getDocComment();
		if ($docComment && preg_match('/@description\s+(.+)/', $docComment, $matches)) {
			$description = trim($matches[1]);
		}
		// Try to get description from parent class if it exists
		else {
			$parentClass = $this->declaringClass->getParentClass();
			if ($parentClass) {
				try {
					$parentProperty = $parentClass->getProperty($property->getName());
					$parentDocComment = $parentProperty->getDocComment();
					if ($parentDocComment && preg_match('/@description\s+(.+)/', $parentDocComment, $matches)) {
						$description = trim($matches[1]);
					}
				}
				catch (ReflectionException $e) {
					// Property doesn't exist in parent class
				}
			}
		}
		if ($docComment && preg_match('/@supported-values\s+(.+)/', $docComment, $matches)) {
			$supportedValues = array_map('trim', explode(',', $matches[1]));
		}

		// Use type from docblock if specified, to use declared types like array<string>
		if ($docComment && preg_match('/@var\s+(\S+)/', $docComment, $matches)) {
			$type = trim($matches[1]);
		}
		else {
			$type = $result['type'];
		}

		// Supported and default values may be set in ways other than docblock - e.g., enum values, class attributes
		// If those are returned from processPropertyType, use them
		if (isset($result['supported'])) {
			$supportedValues = $result['supported'];
		}
		if (isset($result['default'])) {
			$defaultValue = $result['default'];
		}

		// Sort supported values so 'default' is always at the top
		if ($supportedValues) {
			usort($supportedValues, function ($a, $b) {
				if ($a === 'default') return -1;
				if ($b === 'default') return 1;
				return 0;
			});
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

		// If it's a Tag property, get default and supported tags from the class attributes
		if ($typeName === Tag::class) {
			try {
				$allowedTagsAttr = $this->currentClass->getAttributes(AllowedTags::class)[0] ?? null;
				$defaultTagAttr = $this->currentClass->getAttributes(DefaultTag::class)[0] ?? null;
				$allowedTags = $allowedTagsAttr->newInstance()->tags;
				$defaultTag = $defaultTagAttr->newInstance()->tag;

				return [
					'type' => str_replace("$namespace\\", '', $typeName),
					'supported' => array_values(array_map(function ($tag) {
						return $tag->value;
					}, $allowedTags)),
					'default' => $defaultTag->value
				];
			}
			catch (\Throwable $e) {
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
	else if (isset($argv[1]) && $argv[1] === '--base') {
		$instance->runSingleBase($argv[2]);
	}
	else {
		$instance->runAll();
	}
	echo "Done!\n";
}
catch (Exception $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
