<?php

class TraitDocGenerator {
	private string $sourceDirectory;
	private string $outputDirectory;
	private string $output = '';

	public function __construct() {
		require_once(__DIR__ . '/../vendor/autoload.php');
		$this->sourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\base\traits';
		$this->outputDirectory = dirname(__DIR__, 1) . '\docs-site\docs\technical-deep-dives\php-architecture';
		// Ensure output directory exists
		if(!is_dir($this->outputDirectory)) {
			mkdir($this->outputDirectory, 0777, true);
		}

		$this->output = <<<EOT
		# Component Traits
		
		PHP traits are used to provide common implementations of an attribute's conversion from <code>\$attributes</code> array element to object field.
		This provides a central location for validation logic and documentation, reducing duplication and ensuring consistency.\n
		EOT;
	}

	public function runAll(): void {
		$files = $this->get_files();
		foreach($files as $file) {
			$componentName = str_replace('.php', '', basename($file));
			$this->runSingle($componentName);
		}

		$outputFile = "$this->outputDirectory/traits.md";
		file_put_contents($outputFile, $this->output);
	}

	public function runSingle(string $component): void {
		$this->output .= $this->generateMarkdown($component);
	}

	/** @noinspection PhpUnhandledExceptionInspection */
	private function generateMarkdown(string $traitName): string {
		$reflectionTrait = new ReflectionClass("Doubleedesign\Comet\Core\\" . $traitName);
		$properties = array_filter($reflectionTrait->getProperties(), fn(ReflectionProperty $property) => !$property->isPrivate());
		$methods = array_filter($reflectionTrait->getMethods(), fn(ReflectionMethod $method) => !$method->isPrivate());
		$firstMethod = $methods[0]->getName();

		$propertiesOutput = implode('\n', array_map(fn(ReflectionProperty $property) => $this->generate_definition_item($property), $properties));
		$methodsOutput = implode('\n', array_map(fn(ReflectionMethod $method) => $this->generate_definition_item($method), $methods));

		return <<<EOT
		\n<div class="trait-class-doc">
		\n<div>
		\n\n## $traitName
		
		<dl>
		$propertiesOutput
		$methodsOutput
		</dl>
		\n</div>
		
		::: note Example usage
		```php:no-line-numbers
		namespace Doubleedesign\Comet\Core;
		class MyComponent {
			use $traitName;
			
			function __construct(array \$attributes, array \$innerComponents) {
				parent::__construct(\$attributes, \$innerComponents);
				\$this->$firstMethod(\$attributes);
			}
		}
		```
		:::
		</div>
		EOT;
	}

	private function generate_definition_item(ReflectionProperty|ReflectionMethod $item): string {
		$description = '';
		// Get description from docblock
		$docComment = $item->getDocComment();
		if($docComment && preg_match('/@description\s+(.+)/', $docComment, $matches)) {
			$description = trim($matches[1]);
		}

		$rowType = $item instanceof ReflectionProperty ? 'Property' : 'Method';
		$dataType = $item instanceof ReflectionProperty ? $item->getType() : $item->getReturnType();
		$shortTypeLabel = $item instanceof ReflectionProperty ? 'Type' : 'Returns';
		$shortTypeName = $this->strip_namespace($dataType);
		$itemName = $item->getName();

		return <<<EOT

		<dt>$rowType</dt>
		<dd>
			<code>$itemName</code> 
			<strong>$shortTypeLabel:</strong> <code>$shortTypeName</code>
			\n<p>$description</p>
		</dd>
		EOT;
	}

	private function strip_namespace(string $className): string {
		return array_reverse(explode('\\', $className))[0];
	}

	private function get_files(): array {
		/** @noinspection PhpUnhandledExceptionInspection */
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->sourceDirectory));
		$phpFiles = [];
		foreach($files as $file) {
			if($file->isFile() && $file->getExtension() === 'php' && !str_ends_with($file->getPathname(), 'Test.php')) {
				$phpFiles[] = $file->getPathname();
			}
		}

		return $phpFiles;
	}
}

// Usage: php scripts/generate-trait-docs.php
try {
	$instance = new TraitDocGenerator();
	$instance->runAll();
	echo "Done!\n";
}
catch(Error|ReflectionException $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
