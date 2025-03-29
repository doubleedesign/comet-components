<?php
namespace Doubleedesign\Comet\__tests__;
use Doubleedesign\Comet\Core\{AllowedTags, DefaultTag, Tag};
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

class AllComponentsAttributesTest extends TestCase {
	private string $componentDirectory;

	protected function setUp(): void {
		parent::setUp();
		$this->componentDirectory = dirname(__DIR__, 2) . '/components';
	}

	/** @noinspection PhpUnhandledExceptionInspection */
	public function test_have_required_attributes(): void {
		$this->markTestSkipped('This test is skipped for now.');

		$components = $this->get_all_component_classes();
		$errors = [];

		foreach($components as ['class' => $componentClass, 'path' => $filepath]) {
			$reflection = new ReflectionClass($componentClass);
			$className = $reflection->getShortName();

			// Check for AllowedTags attribute
			$allowedTagsAttr = $reflection->getAttributes(AllowedTags::class);
			if(empty($allowedTagsAttr)) {
				$errors[] = "Component {$className} is missing AllowedTags attribute\nfile://{$filepath}";
				continue;
			}

			// Check for DefaultTag attribute
			$defaultTagAttr = $reflection->getAttributes(DefaultTag::class);
			if(empty($defaultTagAttr)) {
				$errors[] = "Component {$className} is missing DefaultTag attribute\nfile://{$filepath}";
				continue;
			}

			// Verify that the default tag is one of the allowed tags
			try {
				$allowedTags = $allowedTagsAttr[0]->newInstance()->tags;
				$defaultTag = $defaultTagAttr[0]->newInstance()->tag;

				if(!in_array($defaultTag, $allowedTags)) {
					$errors[] = "Component {$className} has a default tag that is not in its allowed tags list\nfile://{$filepath}";
				}

				// Check JSON file
				$jsonPath = str_replace('.php', '.json', $filepath);
				if(file_exists($jsonPath)) {
					$jsonContent = json_decode(file_get_contents($jsonPath), true);

					if(isset($jsonContent['attributes']['tagName'])) {
						$jsonTagInfo = $jsonContent['attributes']['tagName'];

						// Check allowed tags match
						$allowedTagValues = array_map(fn(Tag $tag) => $tag->value, $allowedTags);
						$jsonAllowedTags = $jsonTagInfo['supported'] ?? [];

						if(array_diff($allowedTagValues, $jsonAllowedTags) || array_diff($jsonAllowedTags, $allowedTagValues)) {
							$errors[] = "Component {$className} has mismatched allowed tags between attributes and JSON\n" .
								"Attributes: " . implode(', ', $allowedTagValues) . "\n" .
								"JSON: " . implode(', ', $jsonAllowedTags) . "\n" .
								"file://{$filepath}";
						}

						// Check default tag matches
						$defaultTagValue = $defaultTag->value;
						$jsonDefaultTag = $jsonTagInfo['default'] ?? null;

						if($defaultTagValue !== $jsonDefaultTag) {
							$errors[] = "Component {$className} has mismatched default tag between attributes and JSON\n" .
								"Attributes: {$defaultTagValue}\n" .
								"JSON: {$jsonDefaultTag}\n" .
								"file://{$filepath}";
						}
					}
					else {
						$errors[] = "Component {$className} is missing tagName information in JSON file\nfile://{$jsonPath}";
					}
				}
				else {
					$errors[] = "Component {$className} is missing corresponding JSON file\nfile://{$jsonPath}";
				}
			}
			catch(\Throwable $e) {
				$errors[] = "Component {$className} has invalid tag attributes: {$e->getMessage()}\nfile://{$filepath}";
			}
		}

		// Assert no errors were found
		$this->assertEmpty($errors, "Found the following issues:\n\n" . implode("\n\n", $errors));
	}

	/** @noinspection PhpUnhandledExceptionInspection */
	private function get_all_component_classes(): array {
		$components = [];
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($this->componentDirectory)
		);

		foreach($files as $file) {
			if($file->isFile() && $file->getExtension() === 'php' && !str_ends_with($file->getPathname(), 'Test.php')) {
				$className = $this->get_class_name_for_file($file->getPathname());
				if($className) {
					$components[] = [
						'class' => $className,
						'path'  => $file->getPathname()
					];
				}
			}
		}

		return $components;
	}

	private function get_class_name_for_file(string $filepath): ?string {
		$content = file_get_contents($filepath);

		// Extract namespace
		$namespace = '';
		if(preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
			$namespace = $matches[1];
		}

		// Extract class name
		if(preg_match('/class\s+(\w+)/', $content, $matches)) {
			$className = $matches[1];
			return $namespace ? "$namespace\\$className" : $className;
		}

		return null;
	}
}
