<?php
use Doubleedesign\Comet\Core\AllowedTags;
use Doubleedesign\Comet\Core\DefaultTag;
use Doubleedesign\Comet\Core\Tag;
use Doubleedesign\Comet\Core\Utils;

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

		foreach($files as $file) {
			if($file->isFile() && $file->getExtension() === 'php' && !str_ends_with($file->getPathname(), 'Test.php')) {
				$this->processFile($file->getPathname());
			}
		}
	}


	/** @noinspection PhpUnhandledExceptionInspection */
	public function runSingle($component): void {
		// First try direct path
		$filePath = $this->mainComponentDirectory . '\\' . $component . '\\' . $component . '.php';
		if(file_exists($filePath) && !str_ends_with($filePath, 'Test.php')) {
			$this->processFile($filePath);
			return;
		}

		// If not found, try to find base folder:
		// try by splitting PascalCase into words - e.g., AccordionPanel is inside Accordion
		preg_match_all('/[A-Z][a-z]*/', $component, $matches);
		$baseFolder = $matches[0][0];
		$filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
		if(file_exists($filePath) && !str_ends_with($filePath, 'Test.php')) {
			$this->processFile($filePath);
			return;
		}

		// try the other way around, e.g., Button is inside ButtonGroup
		$folders = scandir($this->mainComponentDirectory);
		$baseFolder = array_find($folders, function($folder) use ($component) {
			return str_starts_with($folder, $component);
		});
		$filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
		if(file_exists($filePath)) {
			$this->processFile($filePath);
			return;
		}

		// try singular to plural, e.g. Column is inside Columns
		$baseFolder = $component . 's';
		$filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
		if(file_exists($filePath)) {
			$this->processFile($filePath);
			return;
		}

		// shortened singular to plural based on PascalCase, e.g. TabPanel is inside Tabs
		$baseFolder = $matches[0][0] . 's';
		$filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
		if(file_exists($filePath)) {
			$this->processFile($filePath);
			return;
		}

		// Specific edge cases
		if(in_array($component, ['ListItem', 'ListItemSimple', 'ListItemComplex'])) {
			if($component === 'ListItem') {
				$filePath = $this->mainComponentDirectory . '\ListComponent\ListItem\ListItem.php';
			}
			else {
				$filePath = $this->mainComponentDirectory . '\ListComponent\ListItem\\' . $component . "\\$component.php";
			}
			if(file_exists($filePath)) {
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
		if(file_exists($filePath)) {
			$this->processFile($filePath);
		}
		else {
			throw new RuntimeException("Base component $baseComponent not found");
		}
	}

	private function processFile(string $filePath): void {
		// Get file contents
		$content = file_get_contents($filePath);

		$namespace = '';
		if(preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
			// Extract namespace if exists
			$namespace = $matches[1] . '\\';
		}

		if(preg_match('/class\s+(\w+)/', $content, $matches)) {
			// Extract class name
			$className = $namespace . $matches[1];

			try {
				// Collect the data about the class
				$reflectionClass = new ReflectionClass($className);
				$result = $this->analyseClass($reflectionClass);

				// Check the file path - if it has an extra level between it and the main component directory, it's an inner component
				// we have to go from a folder other than "components" because splitting on "components" will capture the project name in the path too
				$pathParts = explode('packages', $filePath);
				$pathParts = explode("\\", $pathParts[1]);
				$isInner = count($pathParts) > 6;
				if($isInner) {
					$result['isInner'] = true;
					$result['belongsInside'] = $pathParts[4];
				}
				else {
					$result['isInner'] = false;
					if($className === 'Doubleedesign\Comet\Core\Container') {
						$result['belongsInside'] = null;
					}
					else {
						$result['belongsInside'] = 'LayoutComponent';
					}

					// Check if there is a Vue component in this component's directory
					$vueFile = Utils::kebab_case(str_replace('.php', '', basename($filePath)) . '.vue');
					$vueFilePath = dirname($filePath) . '\\' . $vueFile;
					echo $vueFilePath;
					if(file_exists($vueFilePath)) {
						$result['vue'] = true;
					}
					// Workaround for ones known to have a common Vue component
					// TODO: Should check the actual folder of these to get this dynamically
					if(in_array($className, [
						'Doubleedesign\Comet\Core\Accordion',
						'Doubleedesign\Comet\Core\Tabs',
					])) {
						$result['vue'] = true;
					}
				}

				// Sort the result into the desired order
				$result = Utils::sort_associative_array_with_given_key_order(
					$result,
					['name', 'description', 'extends', 'abstract', 'vue', 'isInner', 'belongsInside', 'attributes', 'content', 'innerComponents']
				);

				// Ensure __docs__ folder exists
				$outputDir = dirname($filePath) . '\\__docs__';
				if(!is_dir($outputDir)) {
					mkdir($outputDir, 0777, true);
				}

				// Export the data to a JSON file
				$outputPath = $outputDir . '\\' . $result['name'] . '.json';
				$this->exportToJson($outputPath, $result);
				$this->log("Exported component definition JSON to $outputPath\n", 'success');
			}
			catch(ReflectionException|Exception $e) {
				$this->log("Error processing class $className: " . $e->getMessage(), 'error');
			}
		}
	}

	/**
	 * @throws ReflectionException
	 */
	private function analyseClass(ReflectionClass $reflectionClass): array {
		$className = $reflectionClass->getName();
		$parentClass = $reflectionClass->getParentClass() ?? null;

		if(isset($this->processedClasses[$className])) {
			return $this->processedClasses[$className];
		}

		$this->currentClass = $reflectionClass;
		$properties = [];

		// Collect properties from the class itself
		foreach($reflectionClass->getProperties() as $property) {
			if($this->getVisibility($property) !== 'private') {
				$this->declaringClass = $property->getDeclaringClass(); // get the parent class where the property is declared
				$propertyType = $this->getPropertyType($property);
				$propertyName = $property->getName();

				$properties[$propertyName] = $propertyType;
			}
		}

		// Collect properties from traits
		$traits = $reflectionClass->getTraits();
		foreach($traits as $trait) {
			foreach($trait->getProperties() as $property) {
				if($this->getVisibility($property) !== 'private') {
					$this->declaringClass = $trait;
					$propertyType = $this->getPropertyType($property);
					$propertyName = $property->getName();

					// Only add if not already defined in the class
					if(!isset($properties[$propertyName])) {
						$properties[$propertyName] = $propertyType;
					}
				}
			}
		}

		// Get the description of the class from the docblock at the top
		// (it should be prefixed by @description)
		$docComment = $reflectionClass->getDocComment();
		if($docComment && preg_match('/@description\s+(.+)/', $docComment, $matches)) {
			$description = trim($matches[1]);
		}

		$finalAttrs = array_filter($properties, function($key) {
			return !in_array($key, ['rawAttributes', 'content', 'innerComponents', 'bladeFile', 'shortName']);
		}, ARRAY_FILTER_USE_KEY);
		ksort($finalAttrs);

		$result = [
			'name'        => array_reverse(explode('\\', $className))[0],
			'description' => $description ?? null,
			'extends'     => $parentClass
				? ($parentClass->getName() ? array_reverse(explode('\\', $parentClass->getName()))[0] : null)
				: null,
			'abstract'    => $reflectionClass->isAbstract(),
			'attributes'  => $finalAttrs
		];

		if(isset($properties['content'])) {
			$result['content'] = $properties['content'];
		}
		if(isset($properties['innerComponents'])) {
			$result['innerComponents'] = $properties['innerComponents'];
		}

		if(array_reverse(explode('\\', $className))[0] === 'Image') {
			unset($result['properties']['tag']);
		}

		$this->processedClasses[$className] = $result; // Mark as processed to prevent infinite recursion

		return $result;
	}

	private function getVisibility(ReflectionProperty $property): string {
		if($property->isPrivate()) return 'private';
		if($property->isProtected()) return 'protected';
		return 'public';
	}

	/**
	 * Extracts the default value from constructor calls to set_*_from_attrs methods
	 *
	 * @param ReflectionClass $class The class to analyze
	 * @param string $propertyName The name of the property to find defaults for
	 * @return string|null The default value or null if not found
	 */
	private function extractDefaultFromConstructor(ReflectionClass $class, string $propertyName): ?string {
		if(!$class->hasMethod('__construct')) {
			return null;
		}

		$constructor = $class->getMethod('__construct');
		$filename = $constructor->getFileName();
		$startLine = $constructor->getStartLine();
		$endLine = $constructor->getEndLine();

		if(!$filename) {
			return null;
		}

		$fileContent = file_get_contents($filename);
		$lines = explode("\n", $fileContent);
		$constructorCode = implode("\n", array_slice($lines, $startLine - 1, $endLine - $startLine + 1));

		// Map property names to their setter method patterns
		$propertyToMethodMap = [
			'colorTheme' => 'set_color_theme_from_attrs',
			'size'       => 'set_size_from_attrs',
			'width'      => 'set_width_from_attrs',
			'alignment'  => 'set_alignment_from_attrs',
			'variant'    => 'set_variant_from_attrs',
			'background' => 'set_background_from_attrs',
			'style'      => 'set_style_from_attrs',
			// Add more mappings as needed
		];

		// Get the method name for this property (or use a pattern for unknown properties)
		$methodName = $propertyToMethodMap[$propertyName] ?? "set_{$propertyName}_from_attrs";

		// Look for calls to the setter method with a default parameter
		$pattern = '/\$this->' . preg_quote($methodName, '/') . '\s*\(\s*\$[^,]+\s*,\s*([^)]+)\)/i';

		if(preg_match($pattern, $constructorCode, $matches)) {
			$defaultValue = trim($matches[1]);

			// Extract just the enum value if it's in the form EnumClass::VALUE
			if(strpos($defaultValue, '::') !== false) {
				$parts = explode('::', $defaultValue);
				return trim($parts[1]);
			}

			return $defaultValue;
		}

		return null;
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
		if($this->currentClass == 'Doubleedesign\Comet\Core\Table') {
			$content_type = 'array';
		}

		$result = $this->processPropertyType($type);

		// Handle default boolean values
		if($type instanceof ReflectionNamedType && $type->getName() === 'bool' && $defaultValue === false) {
			$defaultValue = 'false';
		}
		else if($type instanceof ReflectionNamedType && $type->getName() === 'bool' && $defaultValue === true) {
			$defaultValue = 'true';
		}

		// Special handling for properties that might have defaults set in from_attrs trait methods
		$propertyName = $property->getName();
		$knownTraitProperties = [
			'colorTheme', 'backgroundColor', 'hAlign', 'vAlign', 'size', 'orientation', 'textAlign', 'textColor'
		];

		if(in_array($propertyName, $knownTraitProperties) || str_contains($propertyName, 'Theme')) {
			$customDefault = $this->extractDefaultFromConstructor($this->currentClass, $propertyName);
			if($customDefault !== null) {
				$defaultValue = strtolower($customDefault); // this assumes enum cases translate directly from uppercase cases to lowercase values
			}
		}

		// If this is the $classes property, compute the actual default classes (e.g. the shortName or BEM name with context)
		if($property->getName() === 'classes' && !$this->currentClass->isAbstract()) {
			if($this->currentClass->hasMethod('get_filtered_classes')) {
				try {
					// Special handling for some classes that don't follow the usual pattern of parameters
					if($this->currentClass->getName() === 'Doubleedesign\Comet\Core\PageHeader') {
						$instance = $this->currentClass->newInstance([], '', [], 'dummy.blade.php');
					}
					else if($this->currentClass->getName() === 'Doubleedesign\Comet\Core\Table') {
						$instance = $this->currentClass->newInstance([], [], 'dummy.blade.php');
					}
					else if($this->currentClass->getName() === 'Doubleedesign\Comet\Core\ListItemComplex') {
						$instance = $this->currentClass->newInstance([], '', [], 'dummy.blade.php');
					}
					else if($this->currentClass->getName() === 'Doubleedesign\Comet\Core\IconLinks') {
						$instance = $this->currentClass->newInstance([], [], 'dummy.blade.php');
					}
					else {
						$instance = $this->currentClass->newInstance([], $content_type === 'array' ? [] : '', 'dummy.blade.php');
					}

					$defaultValue = $this->currentClass->getMethod('get_filtered_classes')->invoke($instance);
				}
				catch(ReflectionException $e) {
					// If we can't create an instance of current class, fall back to parent
					if($this->declaringClass->hasMethod('get_filtered_classes')) {
						$parentInstance = $this->declaringClass->newInstance([], [], 'dummy.blade.php');
						$defaultValue = $this->declaringClass->getMethod('get_filtered_classes')->invoke($parentInstance);
					}
				}
			}
			else if($this->declaringClass->hasMethod('get_filtered_classes')) {
				// Use parent's method if current class doesn't have it
				$instance = $this->declaringClass->newInstance([], [], 'dummy.blade.php');
				$defaultValue = $this->declaringClass->getMethod('get_filtered_classes')->invoke($instance);
			}
		}

		// Get type details from docblock if available
		$docComment = $property->getDocComment();
		if($docComment && preg_match('/@description\s+(.+)/', $docComment, $matches)) {
			$description = trim($matches[1]);
		}
		// Try to get description from parent class if it exists
		else {
			$parentClass = $this->declaringClass->getParentClass();
			if($parentClass) {
				try {
					$parentProperty = $parentClass->getProperty($property->getName());
					$parentDocComment = $parentProperty->getDocComment();
					if($parentDocComment && preg_match('/@description\s+(.+)/', $parentDocComment, $matches)) {
						$description = trim($matches[1]);
					}
				}
				catch(ReflectionException $e) {
					// Property doesn't exist in parent class
				}
			}
		}
		if($docComment && preg_match('/@supported-values\s+(.+)/', $docComment, $matches)) {
			$supportedValues = array_map('trim', explode(',', $matches[1]));
		}
		// Get default values from docblock if not already set
		if(!isset($result['default']) && $docComment && preg_match('/@default-value\s+(.+)/', $docComment, $matches)) {
			$defaultValue = trim($matches[1]);
		}
		// Use type from docblock if specified, to use declared types like array<string>
		if($docComment && preg_match('/@var\s+(\S+)/', $docComment, $matches)) {
			$type = trim($matches[1]);
		}
		else {
			$type = $result['type'];
		}

		// Supported and default values may be set in ways other than docblock - e.g., enum values, class attributes
		// If those are returned from processPropertyType, use them
		if(isset($result['supported'])) {
			$supportedValues = $result['supported'];
		}
		if(isset($result['default'])) {
			$defaultValue = $result['default'];
		}

		// Sort supported values so 'default' is always at the top
		if($supportedValues) {
			usort($supportedValues, function($a, $b) {
				if($a === 'default') return -1;
				if($b === 'default') return 1;
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
			'default'     => $defaultValue,
			// TODO: Make it clearer what this means - the field name and type is inherited, but the allowed and default values are not
			'inherited'   => $this->declaringClass->getName() !== $this->currentClass->getName()
		];

		return array_filter($result, fn($value) => $value !== null && $value !== false, ARRAY_FILTER_USE_BOTH);
	}


	/**
	 * Processes the type of a property, returning an array with the short type name (no namespace)
	 * and supported values if it's an enum.
	 * @param ReflectionNamedType|ReflectionUnionType $type
	 *
	 * @return array|string[]
	 */
	private function processPropertyType(ReflectionNamedType|ReflectionUnionType $type): array {
		if($type instanceof ReflectionNamedType) {
			$typeName = $type->getName();

			if(!class_exists($typeName)) {
				return ['type' => $typeName];
			}

			$reflectionType = new ReflectionClass($typeName);
			$namespace = $reflectionType->getNamespaceName();

			// If it's a Tag property, get default and supported tags from the class attributes
			if($typeName === Tag::class) {
				try {
					$allowedTagsAttr = $this->currentClass->getAttributes(AllowedTags::class)[0] ?? null;
					$defaultTagAttr = $this->currentClass->getAttributes(DefaultTag::class)[0] ?? null;
					$allowedTags = $allowedTagsAttr->newInstance()->tags;
					$defaultTag = $defaultTagAttr->newInstance()->tag;

					return [
						'type'      => str_replace("$namespace\\", '', $typeName),
						'supported' => array_values(array_map(function($tag) {
							return $tag->value;
						}, $allowedTags)),
						'default'   => $defaultTag->value
					];
				}
				catch(\Throwable $e) {
					$this->log("Error processing AllowedTags or DefaultTag attributes: " . $e->getMessage(), 'error');
				}
			}

			if($reflectionType->isEnum()) {
				$cases = $reflectionType->getConstants();
				$supportedValues = array_map(function($case) {
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
		else if($type instanceof ReflectionUnionType) {
			$types = $type->getTypes();

			$processedTypes = array_map(function($type) {
				return $this->processPropertyType($type);
			}, $types);

			// If Comet classes are an option, assume that's what we want and just return that
			// e.g., in Table, the caption can be a TableCaption or an array corresponding to a TableCaption, so we just list TableCaption here
			// TODO: Should this allow for more than one type?
			return array_filter($processedTypes, function($type) {
				return str_starts_with($type['type'], 'Doubleedesign\Comet\Core');
			})[0];
		}

		return [];
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

	private static function log(string $message, string $type): void {
		// ANSI colour codes
		$red = "\033[0;31m";
		$green = "\033[0;32m";
		$yellow = "\033[0;33m";
		$cyan = "\033[0;36m";
		$white = "\033[0;37m";
		$reset = "\033[0m";

		$color = match ($type) {
			'info' => $cyan,
			'success' => $green,
			'error' => $red,
			'warning' => $yellow,
			default => $white,
		};

		echo $color . $message . $reset;
	}
}


// Usage: php generate-json-defs.php
//        or php generate-json-defs.php --component MyComponent
//        or php generate-json-defs.php --base MyBaseComponent for base abstract component classes
try {
	$instance = new ComponentClassesToJsonDefinitions();
	if(isset($argv[1]) && $argv[1] === '--component') {
		$instance->runSingle($argv[2]);
		shell_exec('php scripts/generate-xml.php');
	}
	else if(isset($argv[1]) && $argv[1] === '--base') {
		$instance->runSingleBase($argv[2]);
	}
	else {
		$instance->runAll();
		shell_exec('php scripts/generate-xml.php');
	}
	echo "Done!\n";
}
catch(Exception $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
