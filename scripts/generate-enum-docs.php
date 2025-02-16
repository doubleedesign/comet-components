<?php

class EnumDocGenerator {
	private string $sourceDirectory;
	private string $outputDirectory;
	private string $mdx = '';

	public function __construct() {
		require_once(__DIR__ . '/../vendor/autoload.php');
		$this->sourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\base\types';
		$this->outputDirectory = dirname(__DIR__, 1) . '\docs\code-foundations';
		// Ensure output directory exists
		if (!is_dir($this->outputDirectory)) {
			mkdir($this->outputDirectory, 0777, true);
		}

		$this->mdx = "<h1>Data Types</h1>\n\n";
		$this->mdx .= $this->addParagraph('PHP enums are used to specify valid values for properties.');
		$this->mdx .= $this->addParagraph('This provides a central location for validation logic and documentation, as well as reducing duplication, ensuring consistency, and enabling type safety.');
	}


	public function runAll(): void {
		$enumFiles = $this->getFiles();

		foreach ($enumFiles as $file) {
			$className = $this->getFullyQualifiedClassName($file);

			if ($className) {
				$mdx = $this->generateMDX($className);
				$this->mdx .= $mdx;
			}
		}

		$outputFile = "$this->outputDirectory/Data Types.mdx";
		file_put_contents($outputFile, $this->mdx);
	}

	/** @noinspection PhpUnhandledExceptionInspection */
	private function generateMDX(string $enumClass): string {
		$reflectionClass = new ReflectionEnum($enumClass);
		$shortName = $reflectionClass->getShortName();
		// A place to store stuff from the first column's loop that we want to display in the second column
		$holdForSecondColumn = '';

		$mdx = $this->openDiv('two-column-doc-section');

		// FIRST COLUMN ------------------------------------------------------------------------------------------------
		$mdx .= $this->openDiv();
		// Title and description ---------------------------
		$mdx .= "<h2>$shortName</h2>\n";
		$mdx .= $this->addParagraph("Enum used to specify valid values for {$this->lower_sentence_case($shortName)} properties.");
		// -------------------------------------------------

		// Enum cases --------------------------------------
		// Handle the attributes stuff in the Tag enum, and anything that works the same
		if ($reflectionClass->hasMethod('get_valid_attributes')) {
			$mdx .= "<details>\n";
			$mdx .= '<summary>Supported values</summary>';
			$mdx .= "\n\n";

			$mdx .= "| Case | Value | Valid attributes |\n";
			$mdx .= "|------|-------|------------------|\n";
			foreach ($reflectionClass->getCases() as $case) {
				// Get the enum name and case name
				$enumName = $reflectionClass->getName();
				$caseName = $case->getName();

				// Create the actual enum case instance like $tag = Tag::DIV
				$enumCase = constant("$enumName::$caseName");

				// Now we can call get_valid_attributes() on it
				$uniqueAttributes = $enumCase->get_valid_attributes();
				if ($enumClass::GLOBAL_ATTRIBUTES) {
					$uniqueAttributes = array_diff($uniqueAttributes, $enumClass::GLOBAL_ATTRIBUTES);
					$uniqueAttributes = array_map(fn($attribute) => "`$attribute`", $uniqueAttributes);
					$uniqueAttributes = implode(', ', $uniqueAttributes);
					if (empty($uniqueAttributes)) {
						$mdx .= "| `{$case->getValue()->name}` | `{$case->getValue()->value}` | Global attributes |\n";
						continue;
					}
					$mdx .= "| `{$case->getValue()->name}` | `{$case->getValue()->value}` | Global attributes, $uniqueAttributes, |\n";
					continue;
				}

				$uniqueAttributes = implode(', ', $uniqueAttributes);
				$mdx .= "| `{$case->getValue()->name}` | `{$case->getValue()->value}` | `{$uniqueAttributes}` |\n";
			}
			$mdx .= "\n\n";
			$mdx .= '</details>';

			// Global attributes for the Tag enum
			// or anything else that also has it ---------------
			if ($reflectionClass->hasMethod('get_valid_attributes') && $enumClass::GLOBAL_ATTRIBUTES) {
				$mdx .= "<details>\n";
				$mdx .= '<summary>Global attributes</summary>';
				$mdx .= "\n\n";
				$formattedAttributes = array_map(fn($attribute) => "`$attribute`", $enumClass::GLOBAL_ATTRIBUTES);
				$mdx .= implode(' ', $formattedAttributes);
				$mdx .= "\n\n";
				$mdx .= '</details>';
			}
			// -------------------------------------------------
		}
		// Otherwise, just case and value
		else {
			$mdx .= $this->openFigure('Supported values');
			$mdx .= "| Case | Value |\n";
			$mdx .= "|------|-------|\n";
			foreach ($reflectionClass->getCases() as $case) {
				$mdx .= "| `{$case->getValue()->name}` | `{$case->getValue()->value}`|\n";
			}
			$mdx .= $this->closeFigure();
		}
		// -------------------------------------------------

		$mdx .= $this->openFigure('Methods');
		// Expected/known methods --------------------------
		if ($reflectionClass->hasMethod('fromString')) {
			$mdx .= '<dl>';
			$mdx .= '<dt><code>fromString(string $value)</code></dt>';
			$mdx .= '<dd>';
			$mdx .= 'Converts a string to the corresponding enum case.';
			$mdx .= '</dd>';
			$mdx .= '</dl>';

			$mdx .= "\n\n";
			$mdx .= "<details>\n";
			$mdx .= '<summary>Supported input mappings</summary>';
			$mdx .= "\n\n";
			// Try to extract input mappings from the method
			$methodSource = file_get_contents($reflectionClass->getFileName());
			preg_match('/match\(\$value\)\s*{(.*?)}/s', $methodSource, $matchBlock);

			if (!empty($matchBlock[1])) {
				$mdx .= "| Input | Result |\n";
				$mdx .= "|-------|--------|\n";
				$lines = explode("\n", $matchBlock[1]);
				foreach ($lines as $line) {
					$line = trim($line);
					if (empty($line)) {
						continue;
					}

					[$input, $result] = explode('=>', $line);
					$inputs = array_filter(explode(',', $input), fn($input) => $input !== 'default');
					$trimmedResult = trim(trim($result, ','));

					foreach ($inputs as $input) {
						$input = trim($input);
						$mdx .= "| `$input` | `$trimmedResult` |\n";
					}
				}
			}
			$mdx .= "\n\n";
			$mdx .= '</details>';

			$holdForSecondColumn .= $this->openFigure('Method usage');
			$holdForSecondColumn .= "```php\n";
			$holdForSecondColumn .= "use {$reflectionClass->getName()};\n\n";
			$holdForSecondColumn .= "\$result = {$shortName}::fromString('left');\n";
			$holdForSecondColumn .= "```\n\n";
			$holdForSecondColumn .= $this->closeFigure();
		}
		// If there is no fromString, we probably use PHP's tryFrom
		else {
			$mdx .= '<dl>';
			$mdx .= '<dt><code>tryFrom(string $value): ?self</code></dt>';
			$mdx .= '<dd>';
			$mdx .= 'Built-in PHP enum method that converts a string to the corresponding enum case, or returns null if the string is not a valid value.';
			$mdx .= '</dd>';
			$mdx .= '</dl>';
			$mdx .= "\n\n";

			$firstCase = $reflectionClass->getCases()[0]->getValue()->value;
			$holdForSecondColumn .= $this->openFigure('Method usage');
			$holdForSecondColumn .= "```php\n";
			$holdForSecondColumn .= "use {$reflectionClass->getName()};\n\n";
			$holdForSecondColumn .= "\$result = {$shortName}::tryFrom('$firstCase');\n";
			$holdForSecondColumn .= "```\n\n";
			$holdForSecondColumn .= $this->closeFigure();

		}

		if ($reflectionClass->hasMethod('get_valid_attributes')) {
			$mdx .= '<dl>';
			$mdx .= '<dt><code>get_valid_attributes(): array</code></dt>';
			$mdx .= '<dd>';
			$mdx .= 'Returns an array of valid attributes';
			$mdx .= '</dd>';
			$mdx .= '</dl>';

			$holdForSecondColumn .= "```php\n";
			$holdForSecondColumn .= "use {$reflectionClass->getName()};\n\n";
			$holdForSecondColumn .= "\$someTag = {$shortName}::{$reflectionClass->getCases()[0]->getValue()->name};\n";
			$holdForSecondColumn .= '$result = $someTag->get_valid_attributes();' . "\n";
			$holdForSecondColumn .= "```\n\n";
		}
		// -------------------------------------------------

		$mdx .= $this->closeFigure();
		$mdx .= $this->closeDiv();
		// END FIRST COLUMN --------------------------------------------------------------------------------------------

		// SECOND COLUMN -----------------------------------------------------------------------------------------------
		$mdx .= $this->openDiv();
		// Generic basic usage example ---------------------
		$mdx .= $this->openFigure('Basic usage');
		$mdx .= "```php\n";
		$mdx .= "use {$reflectionClass->getName()};\n\n";
		$mdx .= "\$result = {$shortName}::{$reflectionClass->getCases()[0]->getValue()->name};\n";
		$mdx .= '$value = $result->value;' . " // returns '{$reflectionClass->getCases()[0]->getValue()->value}'\n\n";
		$mdx .= "```\n";
		$mdx .= $this->closeFigure();
		// -------------------------------------------------
		$mdx .= $holdForSecondColumn;
		$mdx .= $this->closeDiv();
		// END SECOND COLUMN -------------------------------------------------------------------------------------------

		$mdx .= $this->closeDiv();
		// END TWO COLUMN DOC SECTION ----------------------------------------------------------------------------------

		return $mdx;
	}

	private function addParagraph(string $text): string {
		return "<p>$text</p>\n";
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

	private function getFiles(): array {
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

	private function getFullyQualifiedClassName(string $file): ?string {
		$contents = file_get_contents($file);

		// Extract namespace
		preg_match('/namespace\s+([^;]+);/', $contents, $namespaceMatches);
		$namespace = $namespaceMatches[1] ?? '';

		// Extract class name
		preg_match('/enum\s+(\w+)/', $contents, $classMatches);
		$className = $classMatches[1] ?? null;

		if ($className) {
			return $namespace ? "$namespace\\$className" : $className;
		}

		return null;
	}


	private function sentence_case(string $string): string {
		// PascalCase to kebab-case
		$words = preg_replace('/([a-z])([A-Z])/', '$1 $2', $string);
		// Handle kebab case
		$words = implode(' ', explode('-', $words));
		// Handle snake case
		$words = implode(' ', explode('_', $words));

		// capitalize the first word and join the rest
		return ucfirst($words);
	}

	private function lower_sentence_case(string $string): string {
		return strtolower($this->sentence_case($string));
	}
}

// Usage: php scripts/generate-enum-docs.php
try {
	$instance = new EnumDocGenerator();
	$instance->runAll();
	echo "Done!\n";
}
catch (Error|ReflectionException $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
