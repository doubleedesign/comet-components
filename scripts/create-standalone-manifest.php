<?php

class ComponentManifestGenerator {
    private string $packageName;
    private string $packageNameKebab;
    private string $packagePath;
    private array $manifest = [];

    public function __construct($args) {
        $this->packageName = $args['component'];
        $this->packageNameKebab = $this->kebab_case($this->packageName);
        if ($this->packageName === 'Launchpad') {
            $this->packagePath = __DIR__ . '/../packages/core-standalone/launchpad';
        }
        else {
            $this->packagePath = __DIR__ . '/../packages/core-standalone/' . $this->kebab_case($this->packageName);
        }
    }

    public function generate(): void {
        $this->manifest = [
            'component'    => $this->packageName,
            'generated_at' => date('c'),
            'files'        => []
        ];

        // Scan the src directory for symlinks and regular files
        $this->scan_directory($this->packagePath . '/src', 'src');
        // Filter out things we don't want to copy to the standalone package
        $this->filter_out_docs_and_tests();

        // Write manifest to package directory
        $manifestPath = $this->packagePath . '/build-manifest.json';
        file_put_contents($manifestPath, json_encode($this->manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        echo "Generated manifest at: $manifestPath\n";
        echo "Found " . count($this->manifest['files']) . " files/symlinks\n";
    }

    private function scan_directory(string $sourcePath, string $relativeBase): void {
        if (!is_dir($sourcePath)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourcePath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            // Skip test files and directories
            if (str_ends_with($item->getPathname(), 'Test.php') || str_contains($item->getPathname(), '__tests__')) {
                continue;
            }

            // Calculate relative path from the base source directory
            $relativePath = $relativeBase . '/' . substr($item->getPathname(), strlen($sourcePath) + 1);
            $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath); // Normalize to forward slashes
            $relativePath = 'packages/core-standalone/' . $this->packageNameKebab . "/" . $relativePath; // Ensure it starts with packages/core-standalone/the-package-directory

            if ($item->isLink()) {
                // It's a symlink - record the target
                $realPath = readlink($item->getPathname());

                // Handle relative symlinks by making them absolute, then convert back to relative from repo root
                if (!$this->is_absolute_path($realPath)) {
                    $realPath = dirname($item->getPathname()) . DIRECTORY_SEPARATOR . $realPath;
                }

                // Convert absolute path back to relative from repo root
                $repoRoot = dirname(dirname(__DIR__)); // Assuming script is in scripts/ or similar
                $relativeTarget = $this->get_relative_path($repoRoot, $realPath);
                $relativeTarget = str_replace('comet-components/', '', $relativeTarget);

                $this->manifest['files'][] = [
                    'type'        => $item->isDir() ? 'directory' : 'file',
                    'destination' => $relativePath,
                    'source'      => $relativeTarget,
                ];

                // If it's a directory symlink, we don't need to recurse into it
                // as we'll copy the entire directory in the GitHub Action
            }
            elseif ($item->isDir()) {
                // Regular directory - just record it exists
                $this->manifest['files'][] = [
                    'type'        => 'directory',
                    'destination' => $relativePath,
                ];
            }
            else {
                // Regular file - record it
                $repoRoot = dirname(dirname(__DIR__));
                $relativeSource = $this->get_relative_path($repoRoot, $item->getPathname());

                $this->manifest['files'][] = [
                    'type'        => 'file',
                    'destination' => $relativePath,
                    'source'      => $relativeSource,
                    'is_symlink'  => false
                ];
            }
        }
    }

    private function filter_out_docs_and_tests(): void {
        // Loop through $this->manifest['files'] and remove any entries that are documentation or test files
        $this->manifest['files'] = array_filter($this->manifest['files'], function($file) {
            // Exclude files that are documentation or tests
            return !(str_contains($file['destination'], '__docs__') ||
                     str_ends_with($file['destination'], '__tests__'));
        });
    }

    private function get_relative_path(string $from, string $to): string {
        $from = realpath($from);
        $to = realpath($to);

        if ($from === false || $to === false) {
            return $to; // Fallback to absolute path if realpath fails
        }

        $from = str_replace('\\', '/', $from);
        $to = str_replace('\\', '/', $to);

        $fromParts = explode('/', trim($from, '/'));
        $toParts = explode('/', trim($to, '/'));

        // Find common path
        $commonLength = 0;
        for ($i = 0; $i < min(count($fromParts), count($toParts)); $i++) {
            if ($fromParts[$i] === $toParts[$i]) {
                $commonLength++;
            }
            else {
                break;
            }
        }

        // Build relative path
        $relativeParts = [];

        // Add ../ for each remaining part in $from
        for ($i = $commonLength; $i < count($fromParts); $i++) {
            $relativeParts[] = '..';
        }

        // Add remaining parts from $to
        for ($i = $commonLength; $i < count($toParts); $i++) {
            $relativeParts[] = $toParts[$i];
        }

        return implode('/', $relativeParts);
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

// Usage: php generate-manifest.php --component=MyComponentName
//        or php generate-manifest.php --component=Launchpad for the "Launchpad" dependency package
try {
    $args = getopt('', ['component:']);
    if (!isset($args['component'])) {
        throw new Exception('Component name is required. Usage: php generate-manifest.php --component=MyComponentName');
    }

    $generator = new ComponentManifestGenerator($args);
    $generator->generate();
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
