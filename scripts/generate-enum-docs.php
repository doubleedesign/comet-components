<?php

class EnumDocGenerator {
	private string $sourceDirectory;
	private string $outputDirectory;

	public function __construct() {
		require_once(__DIR__ . '/../vendor/autoload.php');
		$this->sourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\base\types';
		$this->outputDirectory = dirname(__DIR__, 1) . '\docs\types';
		// Ensure output directory exists
		if (!is_dir($this->outputDirectory)) {
			mkdir($this->outputDirectory, 0777, true);
		}
	}

	/**
	 * @throws ReflectionException
	 * @throws Error
	 */
	public function runAll(): void {
		$enumFiles = $this->getFiles();

		foreach ($enumFiles as $file) {
			$className = $this->getFullyQualifiedClassName($file);

			if ($className) {
				$mdx = $this->generateMDX($className);
				$shortName = (new ReflectionClass($className))->getShortName();
				$outputFile = "$this->outputDirectory/$shortName.mdx";

				file_put_contents($outputFile, $mdx);
				echo "Generated MDX for $shortName\n";
			}
		}
	}

	/**
	 * @throws ReflectionException
	 * @throws Error
	 */
	public function runSingle(string $enum): void {
		$enumFiles = $this->getFiles();
		$enum = ucfirst($enum);
		$enumFile = null;

		foreach ($enumFiles as $file) {
			$className = $this->getFullyQualifiedClassName($file);

			if ($className === "Doubleedesign\\Comet\\Core\\$enum") {
				$enumFile = $file;
				break;
			}
		}

		if ($enumFile) {
			$mdx = $this->generateMDX("Doubleedesign\\Comet\\Core\\$enum");
			$shortName = (new ReflectionClass("Doubleedesign\\Comet\\Core\\$enum"))->getShortName();
			$outputFile = "$this->outputDirectory/$shortName.mdx";

			file_put_contents($outputFile, $mdx);
			echo "Generated MDX for $shortName\n";
		}
	}

	/**
	 * @throws ReflectionException
	 * @throws Error
	 */
	private function generateMDX(string $enumClass): string {
		$reflectionClass = new ReflectionEnum($enumClass);
		$shortName = $reflectionClass->getShortName();
		$mdx = '';

		// Title and description ---------------------------
		$mdx .= "# {$shortName}\n";
		$mdx .= "Enum used to specify valid values for {$this->lower_sentence_case($shortName)} properties\n\n";
		// -------------------------------------------------

		// Generic basic usage example ---------------------
		$mdx .= "## Basic usage\n";
		$mdx .= "```php\n";
		$mdx .= "use {$reflectionClass->getName()};\n\n";
		$mdx .= "\$result = {$shortName}::{$reflectionClass->getCases()[0]->getValue()->name};\n";
		$mdx .= '$value = $result->value;' . " // returns '{$reflectionClass->getCases()[0]->getValue()->value}'\n";
		$mdx .= "```\n\n";
		// -------------------------------------------------

		// Enum cases --------------------------------------
		$mdx .= '---' . "\n";
		$mdx .= "## Available Values\n";

		// Handle the attributes stuff in the Tag enum, and anything that works the same
		if ($reflectionClass->hasMethod('get_valid_attributes')) {
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
					$link = "[Global attributes](#global-attributes)";
					$uniqueAttributes = array_diff($uniqueAttributes, $enumClass::GLOBAL_ATTRIBUTES);
					$uniqueAttributes = array_map(fn($attribute) => "`$attribute`", $uniqueAttributes);
					$uniqueAttributes = implode(' ', $uniqueAttributes);
					if (empty($uniqueAttributes)) {
						$mdx .= "| `{$case->getValue()->name}` | `{$case->getValue()->value}` | $link |\n";
						continue;
					}
					$mdx .= "| `{$case->getValue()->name}` | `{$case->getValue()->value}` | $link $uniqueAttributes |\n";
					continue;
				}

				$uniqueAttributes = implode(', ', $uniqueAttributes);
				$mdx .= "| `{$case->getValue()->name}` | `{$case->getValue()->value}` | `{$uniqueAttributes}` |\n";
			}
		}
		// Otherwise, just case and value
		else {
			$mdx .= "| Case | Value |\n";
			$mdx .= "|------|-------|\n";
			foreach ($reflectionClass->getCases() as $case) {
				$mdx .= "| `{$case->getValue()->name}` | `{$case->getValue()->value}`|\n";
			}
		}
		// -------------------------------------------------

		// Global attributes for the Tag enum
		// or anything else that also has it ---------------
		if ($reflectionClass->hasMethod('get_valid_attributes') && $enumClass::GLOBAL_ATTRIBUTES) {
			$mdx .= "\n\n---\n\n";
			$mdx .= "## Global Attributes\n";
			$formattedAttributes = array_map(fn($attribute) => "`$attribute`", $enumClass::GLOBAL_ATTRIBUTES);
			$mdx .= implode(' ', $formattedAttributes);
			$mdx .= "\n\n";
		}
		// -------------------------------------------------


		// Expected/known methods --------------------------
		if ($reflectionClass->hasMethod('fromString') || $reflectionClass->hasMethod('get_valid_attributes')) {
			$mdx .= '---' . "\n";
			$mdx .= "## Methods\n";
		}

		if ($reflectionClass->hasMethod('fromString')) {
			$mdx .= '### `fromString(string $value)`' . "\n\n";
			$mdx .= "Converts a string to the corresponding enum case.\n\n";

			$mdx .= "#### Example usage\n";
			$mdx .= "```php\n";
			$mdx .= "use {$reflectionClass->getName()};\n\n";
			$mdx .= "\$result = {$shortName}::fromString('left');\n";
			$mdx .= "```\n\n";

			$mdx .= "#### Supported Input Mappings\n";

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
		}

		if ($reflectionClass->hasMethod('get_valid_attributes')) {
			$mdx .= "### `get_valid_attributes(): array`\n\n";
			$mdx .= "Returns an array of valid attributes\n\n";
			$mdx .= "#### Example usage\n";
			$mdx .= "```php\n";
			$mdx .= "use {$reflectionClass->getName()};\n\n";
			$mdx .= "\$someTag = {$shortName}::{$reflectionClass->getCases()[0]->getValue()->name};\n";
			$mdx .= '$result = $someTag->get_valid_attributes();' . "\n";
			$mdx .= "```\n\n";
		}
		// -------------------------------------------------

		$mdx .= '---' . "\n\n";

		return $mdx;
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

// Usage: php generate-enum-docs.php
//        or php generate-enum-docs.php --enum Tag
try {
	$instance = new EnumDocGenerator();
	if (isset($argv[1]) && $argv[1] === '--enum') {
		$instance->runSingle($argv[2]);
	}
	else {
		$instance->runAll();
	}
	echo "Done!\n";
}
catch (Error|ReflectionException $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
