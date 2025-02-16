<?php

class TraitDocGenerator {
	private string $sourceDirectory;
	private string $outputDirectory;
	private string $output = '';

	public function __construct() {
		require_once(__DIR__ . '/../vendor/autoload.php');
		$this->sourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\base\traits';
		$this->outputDirectory = dirname(__DIR__, 1) . '\docs\code-foundations';
		$this->output .= "<h1>Component Traits</h1>";
		$this->output .= '<p>';
		$this->output .= 'PHP traits are used to provide common implementations of an attribute\'s conversion from $attributes array element to object field.';
		$this->output .= '</p>';
		$this->output .= '<p>';
		$this->output .= 'This provides a central location for validation logic and documentation, reducing duplication and ensuring consistency.';
		$this->output .= '</p>';
		$this->output .= "\n\n";

		// Ensure output directory exists
		if (!is_dir($this->outputDirectory)) {
			mkdir($this->outputDirectory, 0777, true);
		}
	}

	public function runAll(): void {
		$files = $this->get_files();
		foreach ($files as $file) {
			$componentName = str_replace('.php', '', basename($file));
			$this->runSingle($componentName);
		}

		$outputFile = "$this->outputDirectory/Component Traits.mdx";
		file_put_contents($outputFile, $this->output);
	}

	public function runSingle(string $component): void {
		$this->output .= $this->generateMDX($component);
	}

	/** @noinspection PhpUnhandledExceptionInspection */
	private function generateMDX(string $traitName): string {
		$mdx = '';
		$reflectionTrait = new ReflectionClass("Doubleedesign\Comet\Core\\" . $traitName);
		$properties = array_filter($reflectionTrait->getProperties(), fn(ReflectionProperty $property) => !$property->isPrivate());
		$methods = array_filter($reflectionTrait->getMethods(), fn(ReflectionMethod $method) => !$method->isPrivate());

		$mdx .= $this->openDiv('two-column-doc-section');

		$mdx .= $this->openDiv();
		$mdx .= "<h2>$traitName</h2>\n";
		$mdx .= "<dl>\n";
		foreach ($properties as $property) {
			$mdx .= $this->generate_definition_item($property);
		}
		foreach ($methods as $method) {
			$mdx .= $this->generate_definition_item($method);
		}
		$mdx .= "\n</dl>";
		$mdx .= $this->closeDiv();

		$firstMethod = $methods[0]->getName();
		$mdx .= $this->openFigure("Example usage");
		$mdx .= "```php\n";
		$mdx .= "namespace Doubleedesign\Comet\Core;\n\n";
		$mdx .= "class MyComponent {\n";
		$mdx .= "    use $traitName;\n\n";
		$mdx .= "    function __construct(array \$attributes, array \$innerComponents) {\n";
		$mdx .= "        parent::__construct(\$attributes, \$innerComponents);\n";
		$mdx .= "        \$this->$firstMethod(\$attributes);\n";
		$mdx .= "    }\n";
		$mdx .= "}\n";
		$mdx .= "```\n";
		$mdx .= $this->closeFigure();

		$mdx .= $this->closeDiv();
		return $mdx;
	}

	private function generate_definition_item(ReflectionProperty|ReflectionMethod $item): string {
		$description = '';
		// Get description from docblock
		$docComment = $item->getDocComment();
		if ($docComment && preg_match('/@description\s+(.+)/', $docComment, $matches)) {
			$description = trim($matches[1]);
		}

		$rowType = $item instanceof ReflectionProperty ? 'Property' : 'Method';
		$dataType = $item instanceof ReflectionProperty ? $item->getType() : $item->getReturnType();
		$shortTypeLabel = $item instanceof ReflectionProperty ? 'Type' : 'Returns';
		$shortTypeName = $this->strip_namespace($dataType);

		$mdx = "<dt>$rowType</dt>";
		$mdx .= "<dd>";
		$mdx .= "<p>`{$item->getName()}` <strong>$shortTypeLabel: </strong> `$shortTypeName`</p>";
		$mdx .= "<p>$description</p>";
		$mdx .= "</dd>";

		return $mdx;
	}

	private function openDiv(string $className = ''): string {
		if ($className) {
			return "<div className=\"$className\">\n";
		}
		return "<div>\n";
	}

	private function closeDiv(): string {
		return "</div>\n";
	}

	private function openFigure(string $caption): string {
		$output = "\n<figure>\n\n";
		$output .= "<figcaption>$caption</figcaption>\n\n";
		return $output;
	}

	private function closeFigure(): string {
		return "\n</figure>\n";
	}

	private function strip_namespace(string $className): string {
		return array_reverse(explode('\\', $className))[0];
	}

	private function get_files(): array {
		/** @noinspection PhpUnhandledExceptionInspection */
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->sourceDirectory));
		$phpFiles = [];
		foreach ($files as $file) {
			if ($file->isFile() && $file->getExtension() === 'php') {
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
catch (Error|ReflectionException $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
