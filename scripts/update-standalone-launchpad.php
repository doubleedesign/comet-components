<?php

/**
 * Script to generate the Comet Components standalone package dependency "launchpad" package
 * TODO: Put this in a post-commit script so it runs automatically when a component is added or updated.
 */
class ComponentStandaloneLaunchpadPackageGenerator {
    private string $sourceDirectory;
    private string $targetDirectory;
    private string $powershellPath;

    public function __construct() {
        $this->sourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\components';
        $this->targetDirectory = dirname(__DIR__, 1) . '\packages\core-standalone\\launchpad\\src';
        $this->powershellPath = '"C:\Program Files\PowerShell\7\pwsh.exe"';

        require_once __DIR__ . '/../vendor/autoload.php';
        require_once __DIR__ . '/../packages/core/vendor/autoload.php';
    }

    public function generate_package(): void {
        // Symlink some essential files
        // TODO: Clean up this verbose repetitive code
        $utilsPath = dirname(__DIR__, 1) . '\packages\core\src\base\Utils.php';
        $packageUtilsPath = $this->targetDirectory . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'Utils.php';
        $command = "New-Item -ItemType SymbolicLink -Path \"$packageUtilsPath\" -Target \"$utilsPath\" -Force";
        shell_exec($this->powershellPath . ' -Command ' . $command);
        $configPath = dirname(__DIR__, 1) . '\packages\core\src\base\Config.php';
        $packageConfigPath = $this->targetDirectory . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'Config.php';
        $command = "New-Item -ItemType SymbolicLink -Path \"$packageConfigPath\" -Target \"$configPath\" -Force";
        shell_exec($this->powershellPath . ' -Command ' . $command);
        $settingsPath = dirname(__DIR__, 1) . '\packages\core\src\base\Settings.php';
        $packageSettingsPath = $this->targetDirectory . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'Settings.php';
        $command = "New-Item -ItemType SymbolicLink -Path \"$packageSettingsPath\" -Target \"$settingsPath\" -Force";
        shell_exec($this->powershellPath . ' -Command ' . $command);
        $globalCssPath = dirname(__DIR__, 1) . '\packages\core\src\components\global.css';
        $packageGlobalCssPath = $this->targetDirectory . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'global.css';
        $command = "New-Item -ItemType SymbolicLink -Path \"$packageGlobalCssPath\" -Target \"$globalCssPath\" -Force";
        shell_exec($this->powershellPath . ' -Command ' . $command);

        // Symlink the whole services directory to the same place in the standalone package
        $sourceServicesPath = dirname(__DIR__, 1) . '\packages\core\src\services';
        $packageServicesPath = $this->targetDirectory . DIRECTORY_SEPARATOR . 'services';
        $command = "New-Item -ItemType SymbolicLink -Path \"$packageServicesPath\" -Target \"$sourceServicesPath\" -Force";
        shell_exec($this->powershellPath . ' -Command ' . $command);

        // Also attributes, probably just need all of those too
        $sourceAttributesPath = dirname(__DIR__, 1) . '\packages\core\src\base\attributes';
        $packageAttributesPath = $this->targetDirectory . DIRECTORY_SEPARATOR . 'base\attributes';
        $command = "New-Item -ItemType SymbolicLink -Path \"$packageAttributesPath\" -Target \"$sourceAttributesPath\" -Force";
        shell_exec($this->powershellPath . ' -Command ' . $command);

        // Symlink the source base/types directory to the same place in the standalone package
        $sourceBaseTypesPath = dirname(__DIR__, 1) . '\packages\core\src\base\types';
        $packageBaseTypesPath = $this->targetDirectory . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'types';
        $command = "New-Item -ItemType SymbolicLink -Path \"$packageBaseTypesPath\" -Target \"$sourceBaseTypesPath\" -Force";
        shell_exec($this->powershellPath . ' -Command ' . $command);

        // Delete the __tests__ directory symlink for the types folder
        $testsPath = $packageBaseTypesPath . DIRECTORY_SEPARATOR . '__tests__';
        $command = "Remove-Item -Path \"$testsPath\" -Force -Recurse -ErrorAction SilentlyContinue";
        shell_exec($this->powershellPath . ' -Command ' . $command);

        // Symlink Renderable and UIComponent
        $renderablePath = dirname(__DIR__, 1) . '\packages\core\src\base\components\Renderable.php';
        $packageRenderablePath = $this->targetDirectory . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'Renderable.php';
        $command = "New-Item -ItemType SymbolicLink -Path \"$packageRenderablePath\" -Target \"$renderablePath\" -Force";
        shell_exec($this->powershellPath . ' -Command ' . $command);
        $uiComponentPath = dirname(__DIR__, 1) . '\packages\core\src\base\components\UIComponent.php';
        $packageUIComponentPath = $this->targetDirectory . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'UIComponent.php';
        $command = "New-Item -ItemType SymbolicLink -Path \"$packageUIComponentPath\" -Target \"$uiComponentPath\" -Force";
        shell_exec($this->powershellPath . ' -Command ' . $command);

        // Create PreprocessedHtml class in the target components directory
        $preprocessedHtmlPath = $this->targetDirectory . '/base/components/PreprocessedHTML.php';
        $preprocessedHtmlContent = <<<PHP
<?php
namespace Doubleedesign\Comet\Core;

/**
 * Utility class to handle the rendering of preprocessed HTML content
 * so it can be inserted into a Comet component as an "innerComponent"
 */
class PreprocessedHTML {
	private string \$content;

	function __construct(string \$content) {
		\$this->content = \$content;
	}

	public function render(): void {
		echo Utils::sanitise_content(\$this->content);
	}
}
PHP;
        file_put_contents($preprocessedHtmlPath, $preprocessedHtmlContent);

        $this->create_composer_file();

        $manifestCommand = "php " . __DIR__ . DIRECTORY_SEPARATOR . "create-standalone-manifest.php --component=Launchpad";
        shell_exec($manifestCommand);
    }

    private function create_composer_file(): void {
        // Go up a level from the standalone package directory
        $rootDir = dirname($this->targetDirectory, 1);
        $composerFilePath = $rootDir . DIRECTORY_SEPARATOR . 'composer.json';

        $coreComposerFilePath = dirname(__DIR__, 1) . '\packages\core\composer.json';
        $coreComposerData = json_decode(file_get_contents($coreComposerFilePath), true);

        $composerData = [
            'name'        => 'doubleedesign/comet-components-launchpad',
            'description' => 'Base package of common dependencies for Comet Components standalone packages',
            'version'     => $coreComposerData['version'],
            'homepage'    => $coreComposerData['homepage'],
            'authors'     => $coreComposerData['authors'],
            'autoload'    => [
                'psr-4' => [
                    "Doubleedesign\\Comet\\Core\\" => [
                        "src/base/types/",
                        "src/base/attributes/",
                        "src/base/traits/",
                        "src/base/components/",
                        "src/base/",
                        "src/services/"
                    ]
                ]
            ],
            "require" => array_reduce(
                array_keys($coreComposerData['require']),
                function($carry, $dep) use ($coreComposerData) {
                    if (str_starts_with($dep, 'illuminate/') || $dep === 'ezyang/htmlpurifier') {
                        $carry[$dep] = $coreComposerData['require'][$dep];
                    }

                    return $carry;
                },
                []
            )
        ];

        file_put_contents($composerFilePath, json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

// Usage: php update-standalone-launchpad.php
// Followed by php export-standalone-package.php --component=Launchpad to have the published version update on next Git push
try {
    $generator = new ComponentStandaloneLaunchpadPackageGenerator();
    $generator->generate_package();
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
