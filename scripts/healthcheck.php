<?php

class Healthcheck {
    private string $componentDir;
    private string $testPageDir;

    public function __construct() {
        require_once __DIR__ . '/../vendor/autoload.php';
        $this->componentDir = dirname(__DIR__, 1) . '\packages\core\src\components\\';
        $this->testPageDir = dirname(__DIR__, 1) . '\test\browser\components\\';
    }

    public function run(): void {
        $missingFiles = $this->get_missing_files();
        $missingBundleScss = $this->get_bundle_missing_scss_imports();

        $this->log_missing_files($missingFiles);
        $this->log_bundle_missing_scss_imports($missingBundleScss);

        $this->log_with_colour("=================================================", 'cyan');
        $this->log_with_colour("Summary: ", 'cyan');
        $this->log_with_colour("Top-level components should have: Blade template (or use its parent's), CSS, JSON definition, browser test page, story, unit test.", 'cyan');
        $this->log_with_colour('Sub-components should have: Blade template, JSON definition.', 'cyan');
        foreach ($missingFiles as $key => $value) {
            if (count($value) > 0) {
                $this->log_with_colour('Missing ' . $key . ': ' . count($value), 'yellow');
            }
            else {
                $this->log_with_colour('Missing ' . $key . ': 0', 'green');
            }
        }

        if (count($missingBundleScss) > 0) {
            $this->log_with_colour('Missing SCSS imports in bundle: ' . count($missingBundleScss), 'yellow');
        }
        else {
            $this->log_with_colour('Missing SCSS imports in bundle: 0', 'green');
        }

        $this->log_with_colour("\nScroll up for details. ", 'cyan');
        $this->log_with_colour("=================================================", 'cyan');
    }

    private function get_missing_files(): array {
        $topLevel = $this->get_top_level_component_directories();
        $all = $this->get_all_component_directories();

        $fileCollections = [
            'JSON'             => [],
            'CSS'              => [],
            'Blade template'   => [],
            'test page'        => [],
            'stories'          => [],
            // TODO: Not all components require both unit and integration tests, so this should be refined
            'unit test'        => [],
            // 'integration test' => [],
        ];

        foreach ($all as $dir) {
            $componentName = basename($dir);
            if (!file_exists($dir . '\\' . '/__docs__/' . $componentName . '.json')) {
                echo $dir . '\\' . $componentName . '/__docs__/' . $componentName . '.json';
                $fileCollections['JSON'][] = $componentName;
            }
            if (!glob($dir . '\\*.blade.php') && $this->get_parent_blade_template($componentName) === null) {
                $fileCollections['Blade template'][] = $componentName;
            }
            if (!file_exists($this->componentDir . '/' . $componentName . '/__tests__/' . $componentName . 'Test.php')) {
                $fileCollections['unit test'][] = $componentName;
            }
        }

        foreach ($topLevel as $dir) {
            $shouldnotHaveOwnCSS = ['Heading', 'ListComponent', 'Paragraph', 'Link', 'Accordion', 'Tabs', 'ResponsivePanels'];
            $componentName = basename($dir);
            if (!file_exists($this->componentDir . $componentName . '\\' . self::kebab_case($componentName) . '.css')) {
                if (!in_array($componentName, $shouldnotHaveOwnCSS)) {
                    $fileCollections['CSS'][] = $componentName;
                }
            }
            if (!file_exists($this->componentDir . $componentName . '/__tests__/' . self::kebab_case($componentName) . '.php')) {
                $fileCollections['test page'][] = $componentName;
            }
            if (!file_exists($this->componentDir . $componentName . '/__tests__/' . self::kebab_case($componentName) . '.stories.ts')) {
                $fileCollections['stories'][] = $componentName;
            }
            //            if (!file_exists($this->componentDir . '__tests__' . $componentName . '.spec.ts')) {
            //                $fileCollections['integration test'][] = $componentName;
            //            }
        }

        return $fileCollections;
    }

    private function log_missing_files($fileCollections): void {
        $topLevel = $this->get_top_level_component_directories();
        $all = $this->get_all_component_directories();
        $this->log_with_colour("You have " . count($topLevel) . " top level components and " . count($all) - count($topLevel) . " sub-components", 'green');

        foreach ($fileCollections as $key => $value) {
            if (count($value) > 0) {
                $this->log_with_colour(count($value) . ' missing ' . $key . ':', 'yellow');
                print_r($value);
            }
        }
    }

    private function get_parent_blade_template($componentName): ?string {
        $class = new ReflectionClass("Doubleedesign\Comet\Core\\" . $componentName);
        $parent = $class->getParentClass();
        if ($parent) {
            $parentName = $parent->getShortName();
            $parentDir = $this->componentDir . '\\' . $parentName;
            $bladePath = $parentDir . '\\' . self::kebab_case($parentName) . '.blade.php';
            if (file_exists($bladePath)) {
                return $bladePath;
            }
            else if ($parent->getParentClass()) {
                return $this->get_parent_blade_template($parent->getParentClass()->getShortName());
            }
        }

        return null;
    }

    private function get_scss_files(): array {
        $all = $this->get_all_component_directories();
        $scssFiles = [];

        foreach ($all as $dir) {
            $componentName = basename($dir);
            $scssFileName = self::kebab_case($componentName) . '.scss';
            if (file_exists($dir . '\\' . $scssFileName)) {
                $scssFiles[] = trim($scssFileName);
            }
        }

        return $scssFiles;
    }

    private function get_bundle_missing_scss_imports(): array {
        $scssFiles = $this->get_scss_files();
        $bundleFile = dirname(__DIR__, 1) . '\packages\core\bundle.scss';
        $fileContents = file_get_contents($bundleFile);
        $imported = explode("\n", $fileContents);
        array_walk($imported, function(&$value) {
            $value = array_reverse(explode('/', $value))[0];
            $value = trim(str_replace('";', '', $value));
        });

        return array_diff($scssFiles, $imported);
    }

    private function log_bundle_missing_scss_imports(array $missingImports): void {
        if (count($missingImports) > 0) {
            $this->log_with_colour('The following SCSS files exist but are not imported in the bundle.scss file:', 'yellow');
            print_r($missingImports);
        }
    }

    /**
     * Get top-level component directories
     *
     * @return array
     */
    private function get_top_level_component_directories(): array {
        $contents = scandir($this->componentDir);

        $folders = array_filter($contents, function($dir) {
            return is_dir($this->componentDir . '\\' . $dir) && !in_array($dir, ['.', '..', '_blade-partials']);
        });

        return array_map(function($dir) {
            return $this->componentDir . '\\' . $dir;
        }, array_values($folders));
    }

    /**
     * Get component directories up to two levels deep
     *
     * @return array
     */
    private function get_all_component_directories(): array {
        $topLevelDirs = $this->get_top_level_component_directories();
        $allDirs = $topLevelDirs;

        foreach ($topLevelDirs as $dir) {
            if (basename($dir) === '_blade-partials') {
                continue;
            }

            $contents = scandir($dir);

            if (basename($dir) === 'Columns') {

                $subDirs = array_values(array_filter($contents, function($maybeSubdir) use ($dir) {
                    if (in_array($maybeSubdir, ['.', '..', '__tests__', '__docs__'])) {
                        return false;
                    }

                    return is_dir($dir . DIRECTORY_SEPARATOR . $maybeSubdir);
                }));

                foreach ($subDirs as $subDir) {
                    $allDirs[] = $dir . DIRECTORY_SEPARATOR . $subDir;
                }
            }
        }

        sort($allDirs);

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
catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
