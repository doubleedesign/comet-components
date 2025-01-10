<?php
/**
 * This script generates JSON files that summarise the details of component classes written in PHP.
 * They are intended to be used for story generation and testing integrations (e.g., comparing WordPress block.json definitions).
 * Usage: php generate-json-defs.php to generate or regenerate all
 *  	  php generate-json-defs.php --component MyComponent to generate or regenerate a specific component
 */
class ComponentClassesToJsonDefinitions {
	private string $directory;
	private array $processedClasses = [];

	public function __construct() {
		$this->directory = dirname(__DIR__, 1) . '\src\components';
	}

	private function includeBaseClasses(): void {
		require_once __DIR__ . '/../src/base/IRenderable.php';
		require_once __DIR__ . '/../src/base/types/ITag.php';
		require_once __DIR__ . '/../src/base/types/Tag.php';
		require_once __DIR__ . '/../src/base/types/HeadingTag.php';
		require_once __DIR__ . '/../src/base/UIComponent.php';
		require_once __DIR__ . '/../src/base/CoreElementComponent.php';
		require_once __DIR__ . '/../src/base/BasicElementComponent.php';
		require_once __DIR__ . '/../src/base/LayoutComponent.php';
		require_once __DIR__ . '/../src/base/CoreAttributes.php';
		require_once __DIR__ . '/../src/base/BaseAttributes.php';
		require_once __DIR__ . '/../src/base/types/Alignment.php';
	}

	public function runAll(): void {
		$this->includeBaseClasses();

		// Get all PHP files in the directory
		/** @noinspection PhpUnhandledExceptionInspection */
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->directory));

		foreach ($files as $file) {
			if ($file->isFile() && $file->getExtension() === 'php') {
				$this->processFile($file->getPathname());
			}
		}
	}

	public function runSingle($component): void {
		$this->includeBaseClasses();

		$filePath = $this->directory . '\\' . $component . '\\' . $component . '.php';
		if (!file_exists($filePath)) {
			throw new Exception("Component class $component not found");
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

			// Include the file to make the class available for reflection
			require_once $filePath;

			try {
				$reflectionClass = new ReflectionClass($className);
				$result = $this->analyseClass($reflectionClass);
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
			'name' => array_reverse(explode('\\', $className))[0],
            'content' => $properties['content'],
			'attributes' => array_filter($properties, function ($key) {
                return !in_array($key, ['rawAttributes', 'content']);
            }, ARRAY_FILTER_USE_KEY)
		];

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
        if(isset($result['supported'])) {
            $supportedValues = $result['supported'];
        }

        // $required takes care of these rather than having them in the field names
        $trimmedType = str_replace('?', '', $type);
        $trimmedType = str_replace('|null', '', $trimmedType);

        $result = [
            'type' => $trimmedType,
            'description' => $description,
            'required' => $required,
            'supported' => $supportedValues,
            'default' => $defaultValue
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

        if(!class_exists($typeName)) {
            return ['type' => $typeName];
        }

        $reflectionType = new ReflectionClass($typeName);
        $namespace = $reflectionType->getNamespaceName();

        if ($reflectionType->isEnum()) {
            $cases = $reflectionType->getConstants();
            $supportedValues = array_map(function($case) {
                return $case->value;
            }, $cases);

            return [
                'type' => str_replace("$namespace\\", '', $typeName),
                'supported' => array_values($supportedValues)
            ];
        }

        // If it's not an enum or the class doesn't exist, return the original type name
        return ['type' => $typeName];
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
try {
	$instance = new ComponentClassesToJsonDefinitions();
	if(isset($argv[1]) && $argv[1] === '--component') {
		$instance->runSingle($argv[2]);
	} else {
		$instance->runAll();
	}
	echo "Done!\n";
}
catch (Exception $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
