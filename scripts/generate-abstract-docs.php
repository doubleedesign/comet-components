<?php

class AbstractClassDocGenerator {
	private string $baseSourceDirectory;
	private string $mainComponentSourceDirectory;
	private string $output;

	private string $outputDirectory;

	public function __construct() {
		$this->baseSourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\base\components';
		$this->mainComponentSourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\components';
		$this->outputDirectory = dirname(__DIR__, 1) . '\docs\code-foundations';
		$this->output = "# Abstract Component Classes\n\n";
		$this->output .= 'Foundational PHP classes for defining common fields and methods for components.';

		// Ensure output directory exists
		if (!is_dir($this->outputDirectory)) {
			mkdir($this->outputDirectory, 0777, true);
		}
	}

	/**
	 * @throws Error
	 */
	public function runAll(): void {
		$files = $this->get_files();
		foreach ($files as $file) {
			$componentName = basename($file);
			$this->runSingle($componentName);
		}

		$outputFile = "$this->outputDirectory/Abstract Classes.mdx";
		file_put_contents($outputFile, $this->output);
	}

	/**
	 * @throws Error
	 */
	public function runSingle(string $component): void {
		$file = $this->baseSourceDirectory . '\\' . $component;
		if (!file_exists($file)) {
			throw new Error("Component JSON definition for $component not found");
		}

		$this->output .= $this->generateMDX(json_decode(file_get_contents($file), true));
	}

	private function generateMDX(array $json): string {
		$mdx = '';
		$name = $json['name'];
		$extends = $json['extends'];
		$children = $this->get_child_classes($name);

		$mdx .= "\n## $name\n";
		$extendsLink = "[$extends](#$extends)";

		$mdx .= '<table>';
		$mdx .= '<tbody>';
		$mdx .= '<tr><th scope="row">Extends</th><td>' . $extendsLink . '</td></tr>';
		$mdx .= '<tr><th scope="row">Extended by</th><td>' . implode(' ', $children) . '</td></tr>';
		$mdx .= '</tbody>';
		$mdx .= '</table>';

		return $mdx;
	}

	private function get_files(): array {
		/** @noinspection PhpUnhandledExceptionInspection */
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->baseSourceDirectory));
		$phpFiles = [];
		foreach ($files as $file) {
			if ($file->isFile() && $file->getExtension() === 'json') {
				$phpFiles[] = $file->getPathname();
			}
		}

		return $phpFiles;
	}

	private function get_child_classes($componentName): array {
		$children = [];
		$directories = $this->get_all_component_directories();
		$jsonDefs = array_map(function ($dir) {
			return $dir . '\\' . basename($dir) . '.json';
		}, $directories);

		foreach ($jsonDefs as $jsonDef) {
			if (!file_exists($jsonDef)) continue;

			$json = json_decode(file_get_contents($jsonDef), true);
			if (isset($json['extends']) && $json['extends'] === $componentName) {
				$children[] = $json['name'];
			}
		}

		return $children;
	}

	/**
	 * Get top-level component directories
	 * @return array
	 */
	private function get_top_level_component_directories(): array {
		$contents = scandir($this->mainComponentSourceDirectory);

		$folders = array_filter($contents, function ($dir) {
			return is_dir($this->mainComponentSourceDirectory . '\\' . $dir) && !in_array($dir, ['.', '..']);
		});

		return array_map(function ($dir) {
			return $this->mainComponentSourceDirectory . '\\' . $dir;
		}, array_values($folders));
	}

	/**
	 * Get component directories up to two levels deep
	 * @return array
	 */
	private function get_all_component_directories(): array {
		$topLevelDirs = $this->get_top_level_component_directories();
		$allDirs = $topLevelDirs;

		foreach ($topLevelDirs as $dir) {
			$contents = scandir($dir);

			$subDirs = array_filter($contents, function ($subDir) use ($dir) {
				return is_dir($dir . $subDir) && !in_array($subDir, ['.', '..']);
			});

			foreach ($subDirs as $subDir) {
				$allDirs[] = $dir . $subDir;
			}
		}

		return $allDirs;
	}
}

// Usage: php generate-abstract-docs.php
try {
	$instance = new AbstractClassDocGenerator();
	$instance->runAll();
}
catch (Error|ReflectionException $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
