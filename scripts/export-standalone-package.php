<?php

class ComponentStandalonePackageExporter {
    private string $packageName;
    private string $distPath;
    private string $packagePath;
    private string $powershellPath;

    public function __construct($args) {
        $this->packageName = $args['component'];
        if ($this->packageName === 'Launchpad') {
            $this->packagePath = __DIR__ . '/../packages/core-standalone/launchpad';
        }
        else {
            $this->packagePath = __DIR__ . '/../packages/core-standalone/' . $this->kebab_case($this->packageName);
        }
        $this->powershellPath = '"C:\Program Files\PowerShell\7\pwsh.exe"';

        // Create dist folder
        mkdir($this->packagePath . DIRECTORY_SEPARATOR . 'dist', 0755, true);
        $this->distPath = $this->packagePath . DIRECTORY_SEPARATOR . 'dist';
    }

    public function export(): void {
        // Copy composer.json from core-standalone/<component-name> to dist
        //        $composerJsonPath = $this->packagePath . '/composer.json';
        //        copy($composerJsonPath, $this->distPath . '/composer.json');

        // Convert all the symlinks from the src directory , including recursing into directories,
        // into copies of the real files in the dist directory, keeping the same structure
        $this->copy_symlinked_to_real($this->packagePath . '/src', $this->distPath . '/src');

        // Run composer install in the dist directory
        // shell_exec("$this->powershellPath -Command \"cd {$this->distPath} && composer install --no-dev");

        // And generate autoload files
        // shell_exec("$this->powershellPath -Command \"cd {$this->distPath} && composer dump-autoload -o");
    }

    private function copy_symlinked_to_real(string $sourcePath, string $destPath): void {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourcePath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            // Skip test files and directories
            if (str_ends_with($item->getPathname(), 'Test.php') || str_contains($item->getPathname(), '__tests__')) {
                continue;
            }

            // Calculate relative path from source to current item
            $relativePath = substr($item->getPathname(), strlen($sourcePath) + 1);
            $destItem = $destPath . DIRECTORY_SEPARATOR . $relativePath;

            if ($item->isLink()) {
                // It's a symlink - resolve it and copy the real content
                $realPath = readlink($item->getPathname());

                // Handle relative symlinks by making them absolute
                if (!$this->is_absolute_path($realPath)) {
                    $realPath = dirname($item->getPathname()) . DIRECTORY_SEPARATOR . $realPath;
                }

                if (is_dir($realPath)) {
                    // Symlink points to a directory - recursively copy it
                    $this->copy_directory($realPath, $destItem);
                }
                else {
                    // Symlink points to a file - copy the file
                    $destDir = dirname($destItem);
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0755, true);
                    }
                    copy($realPath, $destItem);
                }
            }
            elseif ($item->isDir()) {
                // Regular directory - create it in destination
                if (!is_dir($destItem)) {
                    mkdir($destItem, 0755, true);
                }
            }
            else {
                // Regular file - copy it
                $destDir = dirname($destItem);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                copy($item->getPathname(), $destItem);
            }
        }
    }

    private function copy_directory(string $source, string $dest): void {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            // Skip test files and directories
            if (str_ends_with($item->getPathname(), 'Test.php') || str_contains($item->getPathname(), '__tests__')) {
                continue;
            }

            // Calculate relative path from source to current item
            $relativePath = substr($item->getPathname(), strlen($source) + 1);
            $destItem = $dest . DIRECTORY_SEPARATOR . $relativePath;

            if ($item->isDir()) {
                if (!is_dir($destItem)) {
                    mkdir($destItem, 0755, true);
                }
            }
            else {
                $destDir = dirname($destItem);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                copy($item->getPathname(), $destItem);
            }
        }
    }

    private function is_absolute_path(string $path): bool {
        return (PHP_OS_FAMILY === 'Windows')
            ? (preg_match('/^[A-Za-z]:/', $path) || str_starts_with($path, '\\\\'))
            : str_starts_with($path, '/');
    }

    private static function kebab_case(string $value): string {
        // Account for PascalCase
        $value = preg_replace('/([a-z])([A-Z])/', '$1 $2', $value);

        // Convert whitespace to hyphens and make lowercase
        return trim(strtolower(preg_replace('/\s+/', '-', $value)));
    }
}

// Usage: php export-standalone-package.php --component=MyComponentName
try {
    // Get command line arguments
    $args = getopt('', ['component:']);
    $generator = new ComponentStandalonePackageExporter($args);
    $generator->export();
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
