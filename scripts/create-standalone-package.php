<?php

/**
 * Create a standalone package for a Comet component.
 * This file symlinks everything so we don't duplicate it for dev.
 * To create the package that can be used elsewhere, use the export-standalone-package.php script.
 */
class ComponentStandalonePackageGenerator {
    private string $componentName;
    private string $sourceDirectory;
    private string $targetDirectory;
    private string $powershellPath;

    public function __construct($args) {
        $this->componentName = $args['component'];
        $this->sourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\components';
        $this->targetDirectory = dirname(__DIR__, 1) . '\packages\core-standalone\\' . self::kebab_case($this->componentName) . '\src';
        $this->powershellPath = '"C:\Program Files\PowerShell\7\pwsh.exe"';

        if (!is_dir($this->targetDirectory)) {
            mkdir($this->targetDirectory, 0755, true);
        }

        require_once __DIR__ . '/../vendor/autoload.php';
        require_once __DIR__ . '/../packages/core/vendor/autoload.php';
    }

    public function generate_package(): void {
        $sourcePath = $this->sourceDirectory . DIRECTORY_SEPARATOR . $this->componentName;

        $this->symlink_main_component_files($sourcePath);
        $this->process_php_dependencies($sourcePath);
        $this->symlink_plugins($sourcePath);

        $this->create_composer_file();
    }

    /**
     * Symlink the component directory itself to the same place in the standalone package
     *
     * @param  string  $sourcePath
     *
     * @return void
     * @throws Exception
     */
    private function symlink_main_component_files(string $sourcePath): void {
        $packagePath = $this->targetDirectory . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . $this->componentName;

        // Symlink the source component directory to the matching target one
        $command = "New-Item -ItemType SymbolicLink -Path \"$packagePath\" -Target \"$sourcePath\" -Force";
        try {
            $result = shell_exec($this->powershellPath . ' -Command ' . $command);
            echo $result . "\n";
        }
        catch (Exception $e) {
            throw new Exception("Failed to create symlink for component: " . $e->getMessage());
        }

        // Delete the __docs__ and __tests__ directory symlinks if they exist
        // TODO: Fix this deleting the source files, not just the symlinks
        $docsPath = $packagePath . DIRECTORY_SEPARATOR . '__docs__';
        $testsPath = $packagePath . DIRECTORY_SEPARATOR . '__tests__';
        $command = "Remove-Item -Path \"$docsPath\" -Force -Recurse -ErrorAction SilentlyContinue";
        shell_exec($this->powershellPath . ' -Command ' . $command);
        $command = "Remove-Item -Path \"$testsPath\" -Force -Recurse -ErrorAction SilentlyContinue";
        shell_exec($this->powershellPath . ' -Command ' . $command);

        // Likewise in any subdirectories
        $subfolders = glob($packagePath . DIRECTORY_SEPARATOR . '**', GLOB_ONLYDIR);
        foreach ($subfolders as $subfolder) {
            $command = "Remove-Item -Path \"$packagePath" . DIRECTORY_SEPARATOR . basename($subfolder) . DIRECTORY_SEPARATOR . '__docs__' . "\" -Force -Recurse -ErrorAction SilentlyContinue";
            shell_exec($this->powershellPath . '-Command ' . $command);
            $command = "Remove-Item -Path \"$packagePath" . DIRECTORY_SEPARATOR . basename($subfolder) . DIRECTORY_SEPARATOR . '__tests__' . "\" -Force -Recurse -ErrorAction SilentlyContinue";
            shell_exec($this->powershellPath . ' -Command ' . $command);
        }
    }

    private function process_php_dependencies($sourcePath): void {
        // Get all files in the directory
        $files = array_merge(
            glob($sourcePath . DIRECTORY_SEPARATOR . '*.php', GLOB_BRACE),
            glob($sourcePath . DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . '*.php', GLOB_BRACE)
        );
        // Filter them to only include those that are PHP classes, based on PascalCase naming convention
        $files = array_filter($files, function($file) {
            return is_file($file) && $this->is_pascal_case(str_replace('.php', '', basename($file)));
        });
        // Use those to find the PHP dependencies (parent classes, traits) that we need to include
        $dependencies = [];
        foreach ($files as $file) {
            // For each file, extract the class name and find parent classes, their parent classes, traits, etc.
            $className = basename($file, '.php');
            if ($this->is_pascal_case($className)) {
                $namespace = "Doubleedesign\\Comet\\Core";
                $ancestors = $this->find_dependencies("$namespace\\$className");
                $dependencies = array_merge($dependencies, $ancestors);
            }
        }
        $dependencies = array_values(array_unique($dependencies, SORT_REGULAR));
        try {
            $this->symlink_php_dependencies($dependencies);
        }
        catch (Exception $e) {
            error_log("Error symlinking dependencies for component {$this->componentName}: " . $e->getMessage());
        }
    }

    private function find_dependencies($className, &$dependencies = []): array {
        $reflectionClass = new ReflectionClass($className);
        $traits = $reflectionClass->getTraits();
        foreach ($traits as $trait) {
            array_push($dependencies, [
                'name' => $trait->getName(),
                'type' => 'trait',
                'path' => $trait->getFileName()
            ]);
        }

        $parent = $reflectionClass->getParentClass();
        if ($parent) {
            array_push($dependencies, [
                'name' => $parent->getName(),
                'type' => 'class',
                'path' => $parent->getFileName()
            ]);

            return array_merge($dependencies, $this->find_dependencies($parent->getName(), $dependencies));
        }

        return $dependencies;
    }

    /**
     * For all the class/trait dependencies, symlink from packages/core/src/components
     * to packages/standalone/<component-name> with the same directory structure
     *
     * @param  array  $dependencies
     *
     * @return void
     * @throws Exception
     */
    private function symlink_php_dependencies(array $dependencies): void {
        // Filter out dependencies included in the Launchpad package
        // TODO: Make this dynamic by checking what's actually in the Launchpad package
        $filtered_dependencies = array_filter($dependencies, function($dependency) {
            // Exclude dependencies that are part of the core launchpad package
            return !in_array($dependency['name'], [
                'Doubleedesign\Comet\Core\Renderable',
                'Doubleedesign\Comet\Core\UIComponent'
            ]);
        });

        foreach ($filtered_dependencies as $dependency) {
            $shortPath = str_replace("C:\Users\LeesaWard\PHPStormProjects\comet-components\packages\core\src\\", "", $dependency['path']);
            $targetPath = $this->targetDirectory . DIRECTORY_SEPARATOR . $shortPath;

            $command = "New-Item -ItemType SymbolicLink -Path \"$targetPath\" -Target \"{$dependency['path']}\" -Force";
            try {
                $result = shell_exec($this->powershellPath . ' -Command ' . $command);
                echo $result . "\n";
            }
            catch (Exception $e) {
                throw new Exception("Failed to create symlink for {$dependency['name']}: " . $e->getMessage());
            }
        }
    }

    private function symlink_plugins(string $sourcePath): void {
        // Check if there are any .vue files in the source directory
        $vueFiles = glob($sourcePath . DIRECTORY_SEPARATOR . '*.vue', GLOB_BRACE);
        if (empty($vueFiles)) {
            return; // No Vue files, nothing to do
        }
        // Create the plugins directory in the target package
        $pluginsDirectory = $this->targetDirectory . DIRECTORY_SEPARATOR . 'plugins';
        if (!is_dir($pluginsDirectory)) {
            mkdir($pluginsDirectory, 0755, true);
        }

        // Symlink shared-vue-components and vue-wrapper directories
        $sharedVueComponentsPath = dirname(__DIR__, 1) . '\packages\core\src\plugins\shared-vue-components';
        $vueWrapperPath = dirname(__DIR__, 1) . '\packages\core\src\plugins\vue-wrapper';
        $sharedVueComponentsTarget = $pluginsDirectory . DIRECTORY_SEPARATOR . 'shared-vue-components';
        $vueWrapperTarget = $pluginsDirectory . DIRECTORY_SEPARATOR . 'vue-wrapper';
        $command = "New-Item -ItemType SymbolicLink -Path \"$sharedVueComponentsTarget\" -Target \"$sharedVueComponentsPath\" -Force";
        shell_exec($this->powershellPath . ' -Command ' . $command);
        $command = "New-Item -ItemType SymbolicLink -Path \"$vueWrapperTarget\" -Target \"$vueWrapperPath\" -Force";
        shell_exec($this->powershellPath . ' -Command ' . $command);

        // TODO This should also handle Tippy and any other future JS plugins added
    }

    private function create_composer_file(): void {
        // Go up a level from the standalone package directory
        $rootDir = dirname($this->targetDirectory, 1);
        $composerFilePath = $rootDir . DIRECTORY_SEPARATOR . 'composer.json';

        $coreComposerFilePath = dirname(__DIR__, 1) . '\packages\core\composer.json';
        $coreComposerData = json_decode(file_get_contents($coreComposerFilePath), true);

        $composerData = [
            'name'        => 'doubleedesign/comet-' . self::kebab_case($this->componentName),
            'description' => 'Standalone package for ' . $this->componentName . ' from the Comet Components library',
            'version'     => $coreComposerData['version'],
            'homepage'    => $coreComposerData['homepage'],
            'authors'     => $coreComposerData['authors'],
            'autoload'    => [
                "classmap" => [
                    "dist/src/components/",
                    "dist/src/components/**/"
                ],
                'psr-4' => [
                    "Doubleedesign\\Comet\\Core\\" => [
                        "dist/src/base/types/",
                        "dist/src/base/types/",
                        "dist/src/base/attributes/",
                        "dist/src/base/traits/",
                        "dist/src/base/components/",
                        "dist/src/base/",
                        "dist/src/services/"
                    ]
                ]
            ],
            // TODO: Packaging date stuff needs Ranger, gallery needs BaguetteBox, etc.
            "require" => [
                'doubleedesign/comet-standalone-launchpad' => $coreComposerData['version']
            ]
        ];

        file_put_contents($composerFilePath, json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function is_pascal_case($string): bool {
        return preg_match('/^[A-Z][a-zA-Z0-9]*$/', $string);
    }

    private static function kebab_case(string $value): string {
        // Account for PascalCase
        $value = preg_replace('/([a-z])([A-Z])/', '$1 $2', $value);

        // Convert whitespace to hyphens and make lowercase
        return trim(strtolower(preg_replace('/\s+/', '-', $value)));
    }
}

// Usage: php generate-standalone-package.php --component=MyComponentName
try {
    // Get command line arguments
    $args = getopt('', ['component:']);
    $generator = new ComponentStandalonePackageGenerator($args);
    $generator->generate_package();
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
