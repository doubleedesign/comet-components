<?php

class EnumDocGenerator {
	private string $sourceDirectory;
	private string $outputDirectory;
	private string $output;

	public function __construct() {
		require_once(__DIR__ . '/../vendor/autoload.php');
		$this->sourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\base\types';
		$this->outputDirectory = dirname(__DIR__, 1) . '\docs-site\docs\development\architecture';
		// Ensure output directory exists
		if(!is_dir($this->outputDirectory)) {
			mkdir($this->outputDirectory, 0777, true);
		}

		$this->output = <<<EOT
		# Data Types
		PHP enums are used to specify valid values for properties. This provides a central location for validation logic and documentation, as well as reducing duplication, ensuring consistency, and enabling type safety.
		
		EOT;
	}


	public function runAll(): void {
		$enumFiles = $this->getFiles();

		foreach($enumFiles as $file) {
			$className = $this->getFullyQualifiedClassName($file);

			if($className) {
				$mdx = $this->generateOutput($className);
				$this->output .= $mdx;
			}
		}

		$outputFile = "$this->outputDirectory/data-types.md";
		file_put_contents($outputFile, $this->output);
	}

	/** @noinspection PhpUnhandledExceptionInspection */
	private function generateOutput(string $enumClass): string {
		$reflectionClass = new ReflectionEnum($enumClass);
		$shortName = $reflectionClass->getShortName();
		$methods = '';
		$basicUsage = '';
		$methodUsage = '';
		$globalAttributes = '';

		// Enum cases --------------------------------------
		// Handle the attributes stuff in the Tag enum, and anything that works the same
		if($reflectionClass->hasMethod('get_valid_attributes')) {
			$cases = join('', array_map(function($case) use ($enumClass, $reflectionClass) {
				$returnOutput = '';
				// Get the enum name and case name
				$enumName = $reflectionClass->getName();
				$caseName = $case->getName();
				// Create the actual enum case instance like $tag = Tag::DIV
				$enumCase = constant("$enumName::$caseName");
				// Now we can call get_valid_attributes() on it
				$uniqueAttributes = $enumCase->get_valid_attributes();
				$uniqueAttributes = array_diff($uniqueAttributes, $enumClass::GLOBAL_ATTRIBUTES);
				$uniqueAttributes = array_map(fn($attribute) => "`$attribute`", $uniqueAttributes);
				$uniqueAttributes = implode(', ', $uniqueAttributes);
				if($enumClass::GLOBAL_ATTRIBUTES) {
					if(empty($uniqueAttributes)) {
						$returnOutput .= "| {$case->getValue()->name} | {$case->getValue()->value} | Global attributes |\n";
					}
				}
				$returnOutput .= "| {$case->getValue()->name} | {$case->getValue()->value} | $uniqueAttributes |\n";

				return $returnOutput;
			}, $reflectionClass->getCases()));

			// Global attributes for the Tag enum
			// or anything else that also has it ---------------
			if($enumClass::GLOBAL_ATTRIBUTES) {
				$globalAttributes = join('', array_map(fn($attribute) => "<li><code>$attribute</code></li>", $enumClass::GLOBAL_ATTRIBUTES));
			}

			$enumStuff = <<<EOT
			::: details Supported values
			
			| Case | Value | Valid attributes |
			|------|-------|------------------|
			$cases
			:::
			
			::: details Global attributes
			<ul>
			$globalAttributes
			</ul>
			:::
			EOT;
		}
		// Otherwise, just case and value
		else {
			$cases = join('', array_map(function($case) {
				return "| <code>{$case->getValue()->name}</code> | <code>{$case->getValue()->value}</code> |\n";
			}, $reflectionClass->getCases()));

			$enumStuff = <<<EOT
			::: details Supported values
			
			| Case | Value |
			|------|-------|
			$cases
			:::
			EOT;
		}

		$basicUsage .= <<<EOT
		
		::: note Basic usage
		```php
		use {$reflectionClass->getName()};
		\$result = {$shortName}::{$reflectionClass->getCases()[0]->getValue()->name};
		\$value = \$result->value; // returns '{$reflectionClass->getCases()[0]->getValue()->value}'
		```
		:::
		EOT;

		// Expected/known methods --------------------------
		if($reflectionClass->hasMethod('fromString')) {
			$inputMappings = '';
			// Try to extract input mappings from the method
			$methodSource = file_get_contents($reflectionClass->getFileName());
			preg_match('/return\s+match\s*\(\$value\)\s*{([\s\S]*?)}/s', $methodSource, $matchBlock);
			if(!empty($matchBlock[1])) {
				$lines = explode("\n", $matchBlock[1]);
				$linesOutput = join('', array_map(function($line) {
					$line = trim($line);
					if(empty($line)) return '';

					[$input, $result] = explode('=>', $line);
					$inputs = array_filter(explode(',', $input), fn($input) => $input !== 'default');
					$trimmedResult = trim(trim($result, ','));
					return join('', array_map(function($input) use ($trimmedResult) {
						return "| `$input` | `$trimmedResult` |\n";
					}, $inputs));
				}, $lines));

				$inputMappings = <<<EOT
						
				| Input | Result |
				|-------|--------|
				$linesOutput
				EOT;
			}

			$methods .= <<<EOT
			<dt><code>fromString(string \$value)</code></dt>
			<dd>
			Converts a string to the corresponding enum case.
			
			::: details Supported input mappings
			$inputMappings
			:::
			</dd>
			EOT;
		}
		// If there is no fromString, we probably use PHP's tryFrom
		else {
			$methods .= <<<EOT
			<dt><code>tryFrom(string \$value): ?self</code></dt>
			<dd>Built-in PHP enum method that converts a string to the corresponding enum case, or returns null if the string is not a valid value.</dd>
			EOT;

			$firstCase = $reflectionClass->getCases()[0]->getValue()->value;

			$methodUsage .= <<<EOT
			
			```php
			use {$reflectionClass->getName()};
			\$result = {$shortName}::tryFrom('$firstCase');
			```
			EOT;
		}

		if($reflectionClass->hasMethod('get_valid_attributes')) {
			$methods .= <<<EOT
			<dt><code>get_valid_attributes(): array</code></dt>
			<dd>Returns an array of valid attributes</dd>
			EOT;

			$methodUsage .= <<<EOT
			
			```php
			use {$reflectionClass->getName()}; 
			\$someTag = {$shortName}::{$reflectionClass->getCases()[0]->getValue()->name};
			\$result = \$someTag->get_valid_attributes();
			```
			EOT;
		}

		return <<<EOT
		\n<div class="data-type-doc">
		<div>
		\n## $shortName
		
		Enum used to specify valid values for {$this->lower_sentence_case($shortName)} properties.
		
		$enumStuff
		### Methods
		<dl>
		$methods
		</dl>
		</div>
		
		<div>
		$basicUsage
		::: note Method usage
		$methodUsage
		:::
		</div>
		</div>
		EOT;
	}

	private function getFiles(): array {
		/** @noinspection PhpUnhandledExceptionInspection */
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->sourceDirectory));
		$phpFiles = [];
		foreach($files as $file) {
			if($file->isFile() && $file->getExtension() === 'php') {
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

		if($className) {
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
catch(Exception $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
