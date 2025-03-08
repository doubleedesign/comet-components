<?php

class Healthcheck {
	private string $componentDir;
	private string $testPageDir;
	private string $storyDir;
	private string $unitTestDir;

	public function __construct() {
		require_once(__DIR__ . '/../vendor/autoload.php');
		$this->componentDir = dirname(__DIR__, 1) . '\packages\core\src\components\\';
		$this->testPageDir = dirname(__DIR__, 1) . '\test\browser\components\\';
		$this->storyDir = dirname(__DIR__, 1) . '\test\browser\stories\\';
		$this->unitTestDir = dirname(__DIR__, 1) . '\test\browser\unit\\';
	}

	public function run(): void {
		$missingFiles = $this->get_missing_files();
		$unimplementedRender = $this->get_unimplemented_render_functions();
		$missingScss = $this->get_wp_blocks_missing_scss_imports();

		$this->log_missing_files($missingFiles);
		$this->log_unimplemented_render_functions($unimplementedRender);
		$this->log_wp_blocks_missing_scss_imports($missingScss);

		$this->log_with_colour("=================================================", 'cyan');
		$this->log_with_colour("Summary: ", 'cyan');
		$this->log_with_colour("Top-level components should have: Blade template, CSS, JSON definition, browser test page, story, unit test.", 'cyan');
		$this->log_with_colour('Sub-components should have: Blade template, JSON definition.', 'cyan');
		foreach($missingFiles as $key => $value) {
			if(count($value) > 0) {
				$this->log_with_colour('Missing ' . $key . ': ' . count($value), 'yellow');
			}
			else {
				$this->log_with_colour('Missing ' . $key . ': 0', 'green');
			}
		}

		if(count($unimplementedRender) > 0) {
			$this->log_with_colour('Unimplemented render methods: ' . count($unimplementedRender), 'yellow');
		}
		else {
			$this->log_with_colour('Unimplemented render methods: 0', 'green');
		}

		if(count($missingScss) > 0) {
			$this->log_with_colour('Missing SCSS imports in WordPress plugin: ' . count($missingScss), 'yellow');
		}
		else {
			$this->log_with_colour('Missing SCSS imports in WordPress plugin: 0', 'green');
		}

		$this->log_with_colour("\nScroll up for details. ", 'cyan');
		$this->log_with_colour("=================================================", 'cyan');
	}

	private function get_missing_files(): array {
		$topLevel = $this->get_top_level_component_directories();
		$all = $this->get_all_component_directories();

		$fileCollections = [
			'JSON'           => [],
			'CSS'            => [],
			'Blade template' => [],
			'test page'      => [],
			'stories'        => [],
			'unit test'      => []
		];

		foreach($all as $dir) {
			$componentName = basename($dir);
			if(!file_exists($dir . '\\' . $componentName . '.json')) {
				$fileCollections['JSON'][] = $componentName;
			}
			if(!glob($dir . '\\*.blade.php')) {
				$fileCollections['Blade template'][] = $componentName;
			}
			if(!file_exists($this->unitTestDir . $componentName . 'Test.php')) {
				$fileCollections['unit test'][] = $componentName;
			}
		}

		foreach($topLevel as $dir) {
			$shouldnotHaveOwnCSS = ['Heading', 'ListComponent', 'Paragraph', 'Link', 'Group'];
			$componentName = basename($dir);
			if(!file_exists($this->componentDir . $componentName . '\\' . self::kebab_case($componentName) . '.css')) {
				if(!in_array($componentName, $shouldnotHaveOwnCSS)) {
					$fileCollections['CSS'][] = $componentName;
				}
			}
			if(!file_exists($this->testPageDir . self::kebab_case($componentName) . '.php')) {
				$fileCollections['test page'][] = $componentName;
			}
			if(!file_exists($this->storyDir . self::kebab_case($componentName) . '.stories.json')) {
				$fileCollections['stories'][] = $componentName;
			}
		}

		return $fileCollections;
	}

	private function log_missing_files($fileCollections): void {
		$topLevel = $this->get_top_level_component_directories();
		$all = $this->get_all_component_directories();
		$this->log_with_colour("You have " . count($topLevel) . " top level components and " . count($all) - count($topLevel) . " sub-components", 'green');

		foreach($fileCollections as $key => $value) {
			if(count($value) > 0) {
				$this->log_with_colour(count($value) . ' missing ' . $key . ':', 'yellow');
				print_r($value);
			}
		}
	}

	private function get_unimplemented_render_functions(): array {
		$all = $this->get_all_component_directories();
		$unimplemented = [];

		foreach($all as $filePath) {
			// Get file contents
			$componentName = basename($filePath);
			$content = file_get_contents($filePath . '\\' . $componentName . '.php');

			// Extract namespace if exists
			$namespace = '';
			if(preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
				$namespace = $matches[1] . '\\';
			}
			// Extract class name
			$className = null;
			if(preg_match('/class\s+(\w+)/', $content, $matches)) {
				$className = $namespace . $matches[1];
			}

			if(!isset($className) || !class_exists($className)) {
				$this->log_with_colour('Could not find class ' . $className, 'red');
				continue;
			}

			$reflectionClass = new ReflectionClass($className);
			if($reflectionClass->hasMethod('render')) {
				$renderMethod = $reflectionClass->getMethod('render');
				$attributes = $renderMethod->getAttributes();
				if(count($attributes) > 0) {
					if($attributes[0]->getName() === "Doubleedesign\Comet\Core\NotImplemented") {
						$unimplemented[] = $className;
					}
				}
			}
		}

		return $unimplemented;
	}

	private function log_unimplemented_render_functions(array $unimplemented): void {
		if(count($unimplemented) > 0) {
			$this->log_with_colour('The following components have unimplemented render methods', 'cyan');
			print_r($unimplemented);
		}
	}

	private function get_scss_files(): array {
		$all = $this->get_all_component_directories();
		$scssFiles = [];

		foreach($all as $dir) {
			$componentName = basename($dir);
			$scssFileName = self::kebab_case($componentName) . '.scss';
			if(file_exists($dir . '\\' . $scssFileName)) {
				$scssFiles[] = trim($scssFileName);
			}
		}

		return $scssFiles;
	}

	private function get_wp_blocks_missing_scss_imports(): array {
		$scssFiles = $this->get_scss_files();
		$pluginBlocksFile = dirname(__DIR__, 1) . '\packages\comet-plugin\src\blocks.scss';
		$pluginTemplatePartsFile = dirname(__DIR__, 1) . '\packages\comet-plugin\src\template-parts.scss';
		$pluginFileContents = file_get_contents($pluginBlocksFile) . file_get_contents($pluginTemplatePartsFile);
		$imported = explode("\n", $pluginFileContents);
		array_walk($imported, function(&$value) {
			$value = array_reverse(explode('/', $value))[0];
			$value = trim(str_replace('";', '', $value));
		});

		return array_diff($scssFiles, $imported);
	}

	private function log_wp_blocks_missing_scss_imports(array $missingImports): void {
		if(count($missingImports) > 0) {
			$this->log_with_colour('The following SCSS files exist but are not imported in the WordPress plugin blocks.scss or template-parts.scss file:', 'yellow');
			print_r($missingImports);
		}
	}

	/**
	 * Get top-level component directories
	 * @return array
	 */
	private function get_top_level_component_directories(): array {
		$contents = scandir($this->componentDir);

		$folders = array_filter($contents, function($dir) {
			return is_dir($this->componentDir . '\\' . $dir) && !in_array($dir, ['.', '..']);
		});

		return array_map(function($dir) {
			return $this->componentDir . '\\' . $dir;
		}, array_values($folders));
	}

	/**
	 * Get component directories up to two levels deep
	 * @return array
	 */
	private function get_all_component_directories(): array {
		$topLevelDirs = $this->get_top_level_component_directories();
		$allDirs = $topLevelDirs;

		foreach($topLevelDirs as $dir) {
			$contents = scandir($dir);

			$subDirs = array_filter($contents, function($subDir) use ($dir) {
				return is_dir($dir . $subDir) && !in_array($subDir, ['.', '..']);
			});

			foreach($subDirs as $subDir) {
				$allDirs[] = $dir . $subDir;
			}
		}

		return $allDirs;
	}

	private function log_with_colour(string $text, string $color): void {
		$colors = [
			'red'     => "\e[31m",
			'green'   => "\e[32m",
			'yellow'  => "\e[33m",
			'blue'    => "\e[34m",
			'magenta' => "\e[35m",
			'cyan'    => "\e[36m",
		];

		print_r($colors[$color] . $text . "\e[0m\n");
	}

	private static function kebab_case(string $value): string {
		// Account for PascalCase
		$value = preg_replace('/([a-z])([A-Z])/', '$1 $2', $value);

		// Convert whitespace to hyphens and make lowercase
		return trim(strtolower(preg_replace('/\s+/', '-', $value)));
	}
}

try {
	$instance = new Healthcheck();
	$instance->run();
}
catch(Exception $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
