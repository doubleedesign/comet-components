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
		require_once __DIR__ . '/../src/base/UIComponent.php';
		require_once __DIR__ . '/../src/base/CoreElementComponent.php';
		require_once __DIR__ . '/../src/base/BasicElementComponent.php';
		require_once __DIR__ . '/../src/base/LayoutComponent.php';
		require_once __DIR__ . '/../src/base/CoreAttributes.php';
		require_once __DIR__ . '/../src/base/BaseAttributes.php';
		require_once __DIR__ . '/../src/base/types/Alignment.php';
		require_once __DIR__ . '/../src/base/types/Tag.php';
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
		
		// Mark as processed to prevent infinite recursion
		$this->processedClasses[$className] = [];
		
		$properties = [];
		
		foreach ($reflectionClass->getProperties() as $property) {
			if ($this->getVisibility($property) !== 'private') {
				$propertyType = $this->getPropertyType($property);
				$propertyName = $property->getName();
				
				$propertyInfo = [
					'name' => $propertyName,
					'type' => $propertyType
				];
				
				// If this is the attributes property, and its type is an *Attributes one
				if ($propertyName === 'attributes' && str_ends_with($propertyType, 'Attributes')) {
					try {
						$attributesClass = new ReflectionClass('Doubleedesign\\Comet\\Components\\BaseAttributes');
						$nestedProperties = [];
						
						foreach ($attributesClass->getProperties() as $attrProperty) {
							if ($this->getVisibility($attrProperty) !== 'private') {
								$nestedProperties[] = [
									'name' => $attrProperty->getName(),
									'type' => $this->getPropertyType($attrProperty)
								];
							}
						}
						
						$propertyInfo['properties'] = $nestedProperties;
					} catch (ReflectionException $e) {
						error_log("Error analyzing BaseAttributes: " . $e->getMessage());
					}
				}
				
				$properties[] = $propertyInfo;
			}
		}
		
		$result = [
			'name' => array_reverse(explode('\\', $className))[0],
			'properties' => $properties
		];
		
		$this->processedClasses[$className] = $result;
		return $result;
	}
	
	private function getVisibility(ReflectionProperty $property): string {
		if ($property->isPrivate()) return 'private';
		if ($property->isProtected()) return 'protected';
		return 'public';
	}
	
	private function getPropertyType(ReflectionProperty $property): string {
		$type = $property->getType();
		if ($type === null) {
			// Try to get type from DocBlock if no type declaration
			$docComment = $property->getDocComment();
			if ($docComment && preg_match('/@var\s+([^\s]+)/', $docComment, $matches)) {
				return $matches[1];
			}
			return 'mixed';
		}
		
		// Handle union types
		if ($type instanceof ReflectionUnionType) {
			return implode('|', array_map(
				fn(ReflectionNamedType $t) => $this->processType($t),
				$type->getTypes()
			));
		}
		
		// Handle single types
		if ($type instanceof ReflectionNamedType) {
			return $this->processType($type);
		}
		
		return 'mixed';
	}
	
	private function processType(ReflectionNamedType $type): string {
		$typeName = $type->getName();
		
		if(!class_exists($typeName)) {
			return $typeName;
		}

		// Handle enums
		// TODO: This isn't handling HeadingTag properly
		$reflectionType = new ReflectionClass($typeName);
		if ($reflectionType->isEnum()) {
			$cases = $reflectionType->getConstants();
			$values = array_map(function($case) {
				return $case->value;
			}, $cases);
			
			// Return the values as a union type
			return "'" . implode("'|'", $values) . "'";
		}
		
		// If it's not an enum or the class doesn't exist, return the original type name
		return $typeName;
	}
	
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
