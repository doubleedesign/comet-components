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
		$this->output = "<h1>Abstract Component Classes</h1>\n";
		$this->output .= "<p>Foundational PHP classes for defining common fields and methods for components.</p>\n";
		$this->output .= "\n\n";

		// Ensure output directory exists
		if (!is_dir($this->outputDirectory)) {
			mkdir($this->outputDirectory, 0777, true);
		}
	}

	/**
	 * @throws Error
	 */
	public function runAll(): void {
		$files = $this->get_abstract_def_files();
		// Custom sort order
		usort($files, function ($a, $b) {
			$sortOrder = [
				'Renderable',
				'UIComponent',
				'TextElement',
				'LayoutComponent',
				'TextElementExtended',
			];

			$a = str_replace('.json', '', basename($a));
			$b = str_replace('.json', '', basename($b));

			$aIndex = array_search($a, $sortOrder);
			$bIndex = array_search($b, $sortOrder);

			if ($aIndex === false) $aIndex = 999;
			if ($bIndex === false) $bIndex = 999;

			return $aIndex - $bIndex;
		});

		$this->output .= "<div id=\"abstract-classes\">\n\n";

		foreach ($files as $file) {
			$componentName = basename($file);
			$this->runSingle($componentName);
		}

		$this->output .= "\n</div>\n";

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
		$extends = '`' . $json['extends'] . '`';
		$children = $this->get_child_classes($name);
		$properties = $json['attributes'];

		$mdx .= "<div id=\"$name\" className=\"abstract-class-doc\">\n";

		$mdx .= "<div>\n";
		$mdx .= "<h2>$name</h2>\n";
		// TODO: Somehow get an auto-generated description in here
		$mdx .= '<table>';
		$mdx .= '<tbody>';
		if ($extends !== '``') {
			$mdx .= '<tr><th scope="row">Extends</th><td>' . $extends . '</td></tr>';
		}
		if (count($children['abstract']) > 0) {
			$mdx .= '<tr><th scope="row" rowspan="2">Extended by</th>';
			$mdx .= '<td>' . implode(' ', $children['abstract']) . '</td></tr>';
			$mdx .= '<tr><td>' . implode(' ', $children['component']) . '</td></tr>';
		}
		else {
			$mdx .= '<tr><th scope="row">Extended by</th>';
			$mdx .= '<td>' . implode(' ', $children['component']) . '</td></tr>';
		}

		if ($properties) {
			$properties = array_filter($properties, function ($property) {
				return !isset($property['inherited']) && $property['inherited'] !== false;
			});
			$count = count($properties) + 1;
			if ($extends !== '``') {
				$mdx .= '<tr>';
				$mdx .= "<th scope='row' rowspan='$count'>Properties</th>";
				$mdx .= "<td>All properties from $extends</td>";
				$mdx .= '</tr>';
			}
			else {
				$mdx .= '<tr>';
				$mdx .= "<th scope='row' rowspan='$count'>Properties</th>";
				$mdx .= "<td></td>";
				$mdx .= '</tr>';
			}
			if ($count > 1) {
				foreach ($properties as $key => $property) {
					$mdx .= '<tr>';
					$mdx .= '<td>';
					$mdx .= "`$key`";
					$mdx .= $property['description'];
					$mdx .= '</td>';
					$mdx .= '</tr>';
				}
			}
		}

		$mdx .= '</tbody>';
		$mdx .= '</table>';
		$mdx .= "\n</div>\n\n";

		$mdx .= "</div>\n\n";
		return $mdx;
	}

	private function get_abstract_def_files(): array {
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
		$children = [
			'abstract'  => [],
			'component' => [],
		];
		$directories = $this->get_all_component_directories();
		$componentDefs = array_map(function ($dir) {
			return $dir . '\\' . basename($dir) . '.json';
		}, $directories);
		$abstractDefs = $this->get_abstract_def_files();

		foreach ($abstractDefs as $jsonDef) {
			if (!file_exists($jsonDef)) continue;
			$json = json_decode(file_get_contents($jsonDef), true);
			if (isset($json['extends']) && $json['extends'] === $componentName) {
				$children['abstract'][] = '`' . $json['name'] . '`';
			}
		}

		foreach ($componentDefs as $jsonDef) {
			if (!file_exists($jsonDef)) continue;
			$json = json_decode(file_get_contents($jsonDef), true);
			if (isset($json['extends']) && $json['extends'] === $componentName) {
				$children['component'][] = '`' . $json['name'] . '`';
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
