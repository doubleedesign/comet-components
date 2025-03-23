<?php

class AbstractClassDocGenerator {
	private string $baseSourceDirectory;
	private string $mainComponentSourceDirectory;
	private string $output;

	private string $outputDirectory;

	public function __construct() {
		$this->baseSourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\base\components';
		$this->mainComponentSourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\components';
		$this->outputDirectory = dirname(__DIR__, 1) . '\docs-site\docs\technical-deep-dives\php-architecture';

		// Ensure output directory exists
		if(!is_dir($this->outputDirectory)) {
			mkdir($this->outputDirectory, 0777, true);
		}

		$this->output = <<< EOT
		# Abstract Component Classes
		Foundational PHP classes for defining common fields and methods for components.
		
		<div id="abstract-classes">
		EOT;
	}

	/**
	 * @throws Error
	 */
	public function runAll(): void {
		$files = $this->get_abstract_def_files();
		// Custom sort order
		usort($files, function($a, $b) {
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

			if($aIndex === false) $aIndex = 999;
			if($bIndex === false) $bIndex = 999;

			return $aIndex - $bIndex;
		});

		foreach($files as $file) {
			$componentName = basename($file);
			$this->runSingle($componentName);
		}

		$this->output .= '</div>';

		$outputFile = "$this->outputDirectory/abstract-classes.md";
		file_put_contents($outputFile, $this->output);
	}

	/**
	 * @throws Error
	 */
	public function runSingle(string $component): void {
		$file = $this->baseSourceDirectory . '\\' . $component;
		if(!file_exists($file)) {
			throw new Error("Component JSON definition for $component not found");
		}

		$this->output .= $this->generateOutput(json_decode(file_get_contents($file), true));
	}

	private function generateOutput(array $json): string {
		$name = $json['name'];
		$extends = $json['extends'] ?? null;
		$extendsOutput = '';
		$inheritedProperties = '';
		if(!empty($extends)) {
			$extendsOutput = "<tr><th scope='row'>Extends</th><td><code>$extends</code></td></tr>";
			$inheritedProperties = "<ul><li>All properties from <code>$extends</code></li></ul>";
		}
		$children = $this->get_child_classes($name);
		$abstract = join('', array_map(function($component) {
			return "<li><code>$component</code></li>";
		}, $children['abstract']));
		$hasChildren = count($children['component']) > 0;
		$component = join('', array_map(function($component) {
			return "<li><code>$component</code></li>";
		}, $children['component']));
		$filteredProperties = array_filter($json['attributes'], function($property) {
			return !isset($property['inherited']) || $property['inherited'] == false;
		});
		$properties = trim(join('', array_map(function($attribute) {
			return "<li><code>$attribute</code></li>";
		}, array_keys($filteredProperties))));
		$hasOwnProperties = count($filteredProperties) > 0;

		$ownPropertiesOutput = $hasOwnProperties ? <<< EOT
			<ul>$properties</ul>
		EOT: '';


		$extendedBy = $hasChildren ? <<<EOT
			<tr>
				<th scope="row" rowspan="2">Extended by</th>
				<td>
					<ul>$abstract</ul>
				</td>
			</tr>
			<tr>
				<td>
					<ul>$component</ul>
				</td>
			</tr>
		EOT: <<< EOT
			<tr>
				<th scope="row">Extended by</th>
				<td>
					<ul>$abstract</ul>
				</td>
			</tr>
		EOT;


		// TODO: Somehow get an auto-generated description in here
		return <<<EOT
		
		<div class="abstract-class-doc" id="$name">
		
		## $name
		
		<table>
			$extendsOutput
			$extendedBy
			<tr>
				<th scope="row" rowspan="2">Properties</th>
				<td>$inheritedProperties</td>
			</tr>
			<tr>
				<td>$ownPropertiesOutput</td>
			</tr>
		</table>
			
		</div>
		EOT;
	}

	private function get_abstract_def_files(): array {
		/** @noinspection PhpUnhandledExceptionInspection */
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->baseSourceDirectory));
		$phpFiles = [];
		foreach($files as $file) {
			if($file->isFile() && $file->getExtension() === 'json') {
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
		$componentDefs = array_map(function($dir) {
			return $dir . '\\' . basename($dir) . '.json';
		}, $directories);
		$abstractDefs = $this->get_abstract_def_files();

		foreach($abstractDefs as $jsonDef) {
			if(!file_exists($jsonDef)) continue;
			$json = json_decode(file_get_contents($jsonDef), true);
			if(isset($json['extends']) && $json['extends'] === $componentName) {
				array_push($children['abstract'], $json['name']);
			}
		}

		foreach($componentDefs as $jsonDef) {
			if(!file_exists($jsonDef)) continue;
			$json = json_decode(file_get_contents($jsonDef), true);
			if(isset($json['extends']) && $json['extends'] === $componentName) {
				array_push($children['component'], $json['name']);
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

		$folders = array_filter($contents, function($dir) {
			return is_dir($this->mainComponentSourceDirectory . '\\' . $dir) && !in_array($dir, ['.', '..']);
		});

		return array_map(function($dir) {
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
}

// Usage: php generate-abstract-docs.php
try {
	$instance = new AbstractClassDocGenerator();
	$instance->runAll();
}
catch(Error|ReflectionException $e) {
	echo "Error: " . $e->getMessage() . "\n";
}
