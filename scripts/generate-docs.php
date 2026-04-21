<?php
use Doubleedesign\Comet\Core\{Accordion, AllowedTags, Config, DefaultTag, Tag, Utils};

/**
 * This script generates JSON and XML files that summarise the details of component classes written in PHP.
 * JSON files are loaded as part of the documentation in Storybook, can be used for story generation, and other testing/validation purposes.
 * XML files are used to provide IDE autocompletion and hints when using "Tycho Template" syntax.
 * NOTE: This script requires PHP 8.4+.
 *
 * Usage: php generate-docs.php to generate or regenerate all
 *        php generate-docs.php --component MyComponent to generate or regenerate a specific component
 */
class ComponentClassesToJsonDefinitions {
    private string $mainComponentDirectory;
    private string $baseComponentDirectory;
    private array $processedClasses = [];
    private ReflectionClass $currentClass;
    private ReflectionClass $declaringClass;

    public function __construct() {
        require_once __DIR__ . '/../vendor/autoload.php';
        require_once __DIR__ . '/../packages/core/vendor/autoload.php';
        Config::init();

        $this->mainComponentDirectory = dirname(__DIR__, 1) . '\packages\core\src\components';
        $this->baseComponentDirectory = dirname(__DIR__, 1) . '\packages\core\src\base\components';
    }

    public function runAll(): void {
        // Get all PHP files in the directories
        /** @noinspection PhpUnhandledExceptionInspection */
        $baseComponents = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->baseComponentDirectory));
        /** @noinspection PhpUnhandledExceptionInspection */
        $mainComponents = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->mainComponentDirectory));

        $megaFileData = [];

        foreach ($baseComponents as $file) {
            if ($file->isFile() && $file->getExtension() === 'php' && !str_ends_with($file->getPathname(), 'Test.php') && !str_ends_with($file->getPathname(), '.blade.php')) {
                $result = $this->processFile($file->getPathname());
                if ($result) {
                    array_push($megaFileData, $result);
                }
                else {
                    $this->log("No result generated for base component file: " . $file->getPathname(), 'warning');
                }
            }
        }

        foreach ($mainComponents as $file) {
            if (
                $file->isFile() && $file->getExtension() === 'php'
                && !str_ends_with($file->getPathname(), 'Test.php')
                && !str_ends_with($file->getPathname(), '.blade.php')
                && !str_contains($file->getPathname(), '__tests__')
            ) {
                $result = $this->processFile($file->getPathname());
                if ($result) {
                    array_push($megaFileData, $result);
                }
                else {
                    $this->log("No result generated for main component file: " . $file->getPathname(), 'warning');
                }
            }
        }

        // Write megafile to where it will be used in VuePress
        $outputDir = dirname(__DIR__, 1) . '/docs-site/docs/.vuepress/components';
        $outputPath = $outputDir . '/all-components.json';
        $this->exportToJson($outputPath, $megaFileData);
        $this->log("Exported mega-file of all component definitions to $outputPath", 'success');
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function runSingle($component): void {
        // First try direct path
        $filePath = $this->mainComponentDirectory . '\\' . $component . '\\' . $component . '.php';
        if (file_exists($filePath) && !str_ends_with($filePath, 'Test.php')) {
            $this->processFile($filePath);

            return;
        }

        // If not found, try to find base folder:
        // try by splitting PascalCase into words - e.g., AccordionPanel is inside Accordion
        preg_match_all('/[A-Z][a-z]*/', $component, $matches);
        $baseFolder = $matches[0][0];
        $filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
        if (file_exists($filePath) && !str_ends_with($filePath, 'Test.php')) {
            $this->processFile($filePath);

            return;
        }

        // try the other way around, e.g., Button is inside ButtonGroup
        $folders = scandir($this->mainComponentDirectory);
        $baseFolder = array_find($folders, function($folder) use ($component) {
            return str_starts_with($folder, $component);
        });
        $filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
        if (file_exists($filePath)) {
            $this->processFile($filePath);

            return;
        }

        // try singular to plural, e.g. Column is inside Columns
        $baseFolder = $component . 's';
        $filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
        if (file_exists($filePath)) {
            $this->processFile($filePath);

            return;
        }

        // shortened singular to plural based on PascalCase, e.g. TabPanel is inside Tabs
        $baseFolder = $matches[0][0] . 's';
        $filePath = $this->mainComponentDirectory . '\\' . $baseFolder . '\\' . $component . '\\' . $component . '.php';
        if (file_exists($filePath)) {
            $this->processFile($filePath);

            return;
        }
    }

    public function runSingleBase($baseComponent): void {
        $filePath = $this->baseComponentDirectory . '\\' . $baseComponent . '.php';
        if (file_exists($filePath)) {
            $this->processFile($filePath);
        }
        else {
            throw new RuntimeException("Base component $baseComponent not found");
        }
    }

    private function processFile(string $filePath): ?array {
        // Get file contents
        $content = file_get_contents($filePath);

        $namespace = '';
        if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
            // Extract namespace if exists
            $namespace = $matches[1] . '\\';
        }

        if (preg_match('/class\s+(\w+)/', $content, $matches)) {
            // Extract class name
            $className = $namespace . $matches[1];

            try {
                // Collect the data about the class
                $reflectionClass = new ReflectionClass($className);
                $result = $this->analyseClass($reflectionClass);

                // Check the file path - if it has an extra level between it and the main component directory, it's an inner component
                // we have to go from a folder other than "components" because splitting on "components" will capture the project name in the path too
                $pathParts = explode('packages', $filePath);
                $pathParts = explode("\\", $pathParts[1]);
                $isInner = count($pathParts) > 6;
                if ($isInner) {
                    $result['isInner'] = true;
                    $result['belongsInside'] = $pathParts[4];
                }
                else {
                    $result['isInner'] = false;
                    // TODO: Find a better way to handle this
                    $canBeTopLevel = ['Doubleedesign\Comet\Core\Container', 'Doubleedesign\Comet\Core\PageHeader', 'Doubleedesign\Comet\Core\SiteHeader', 'Doubleedesign\Comet\Core\SiteFooter'];
                    if (in_array($className, $canBeTopLevel)) {
                        $result['belongsInside'] = null;
                    }
                    else {
                        $result['belongsInside'] = 'LayoutComponent';
                    }

                    // Check if there is a Vue component in this component's directory
                    $vueFile = Utils::kebab_case(str_replace('.php', '', basename($filePath)) . '.vue');
                    $vueFilePath = dirname($filePath) . '\\' . $vueFile;
                    if (file_exists($vueFilePath)) {
                        $result['vue'] = true;
                    }
                    // Workaround for ones known to have a common Vue component (i.e., it's not in the same folder as the PHP class)
                    // TODO: Should check the actual folder of these to get this dynamically
                    if (in_array($className, [
                        'Doubleedesign\Comet\Core\Accordion',
                        'Doubleedesign\Comet\Core\Tabs',
                    ])) {
                        $result['vue'] = true;
                    }
                }

                // Sort the result into the desired order
                $result = Utils::sort_associative_array_with_given_key_order(
                    $result,
                    ['name', 'description', 'extends', 'abstract', 'vue', 'isInner', 'belongsInside', 'attributes', 'content', 'innerComponents']
                );

                // Ensure __docs__ folder exists
                $outputDir = dirname($filePath) . '\\__docs__';
                if (!is_dir($outputDir)) {
                    mkdir($outputDir, 0777, true);
                }

                // Export the data to a JSON file
                $outputPath = $outputDir . '\\' . $result['name'] . '.json';
                $this->exportToJson($outputPath, $result);
                $this->log("Exported component definition JSON to $outputPath", 'success');

                // Return it for compilation into mega-file if using runAll
                return $result;
            }
            catch (ReflectionException|Exception $e) {
                $this->log("Error processing class $className: " . $e->getMessage(), 'error');

                return null;
            }
        }

        return null;
    }

    /**
     * @throws ReflectionException
     */
    private function analyseClass(ReflectionClass $reflectionClass): array {
        $className = $reflectionClass->getName();
        $parentClass = $reflectionClass->getParentClass() ?? null;

        if (isset($this->processedClasses[$className])) {
            return $this->processedClasses[$className];
        }

        $this->currentClass = $reflectionClass;
        $properties = [];

        // Collect properties from the class itself
        foreach ($reflectionClass->getProperties() as $property) {
            if ($this->getVisibility($property) !== 'private') {
                $this->declaringClass = $property->getDeclaringClass(); // get the parent class where the property is declared
                $propertyType = $this->getPropertyType($property);
                $propertyName = $property->getName();

                $properties[$propertyName] = $propertyType;
            }
        }

        // Collect properties from traits
        $traits = $reflectionClass->getTraits();
        $traitNames = [];
        foreach ($traits as $trait) {
            $traitData = $this->getTraitData($trait, $reflectionClass);
            $properties = array_merge($properties, $traitData);
            $traitNames[] = $trait->getShortName();
        }

        // And ancestor class traits
        $ancestor = $parentClass;
        while ($ancestor && $ancestor->getName() !== 'Doubleedesign\Comet\Core\Renderable') {
            $ancestorTraits = $ancestor->getTraits();
            foreach ($ancestorTraits as $trait) {
                $traitData = $this->getTraitData($trait, $reflectionClass);
                $properties = array_merge($properties, $traitData);
                $traitNames[] = $trait->getShortName();
            }
            $ancestor = $ancestor->getParentClass();
        }

        // Get the description of the class from the docblock at the top
        // (it should be prefixed by @description)
        $docComment = $reflectionClass->getDocComment();
        $description = $this->getDescription($docComment);

        $finalAttrs = array_filter($properties, function($key) use ($reflectionClass) {
            if ($reflectionClass->isAbstract()) {
                return !in_array($key, ['rawAttributes', 'content', 'innerComponents', 'bladeFile', 'shortName']);
            }

            return !in_array($key, ['rawAttributes', 'content', 'innerComponents', 'bladeFile']);
        }, ARRAY_FILTER_USE_KEY);
        ksort($finalAttrs);

        $result = [
            'name'        => array_reverse(explode('\\', $className))[0],
            'description' => $description ?? null,
            'extends'     => $parentClass
                ? ($parentClass->getName() ? array_reverse(explode('\\', $parentClass->getName()))[0] : null)
                : null,
            'abstract'    => $reflectionClass->isAbstract(),
            'traits'      => $traitNames,
            'attributes'  => $finalAttrs
        ];

        if (isset($properties['content'])) {
            $result['content'] = $properties['content'];
        }
        if (isset($properties['innerComponents']) && $reflectionClass->getName() !== 'Doubleedesign\Comet\Core\Breadcrumbs') {
            $result['innerComponents'] = $properties['innerComponents'];
        }
        if ($reflectionClass->getName() === 'Doubleedesign\Comet\Core\Breadcrumbs') {
            // Get params of constructor
            $constructor = $reflectionClass->getConstructor();
            $params = $constructor ? $constructor->getParameters() : [];
            $paramsToAdd = array_filter($params, fn($param) => $param->getName() !== 'attributes');
            foreach ($paramsToAdd as $param) {
                $paramType = $param->getType();
                $paramName = $param->getName();
                $docComment = $constructor->getDocComment();
                $description = preg_match('/@param\s+' . preg_quote($paramType?->getName() ?? 'mixed', '/') . '\s+\$' . preg_quote($paramName, '/') . '\s+(.+)/', $docComment, $matches);
                $description = $description ? trim($matches[1]) : null;

                if ($paramType) {
                    $result[$paramName] = [
                        'type'        => $paramType instanceof ReflectionNamedType ? $paramType->getName() : 'mixed',
                        'description' => $description,
                        'required'    => true,
                    ];
                }
            }
        }

        if (array_reverse(explode('\\', $className))[0] === 'Image') {
            unset($result['properties']['tag']);
        }

        $this->processedClasses[$className] = $result; // Mark as processed to prevent infinite recursion

        return $result;
    }

    private function getTraitData(ReflectionClass $trait, ReflectionClass $component): array {
        $privatePropertiesToInclude = ['context', 'shortName', 'isNested'];
        $properties = [];

        foreach ($trait->getProperties() as $property) {
            if (($this->getVisibility($property) !== 'private') || (in_array($property->getName(), $privatePropertiesToInclude))) {
                $propertyName = $property->getName();
                $properties[$propertyName] = $this->getPropertyType($property);
            }
        }

        return $properties ?? [];
    }

    private function getDescription($docComment): ?string {
        if ($docComment && preg_match('/@description\s+(.+)/', $docComment, $matches)) {
            // Get the first line based on the @description tag
            $description = trim($matches[1]);

            // Get the next 2 lines and check if they should also be included in the description
            $lines = explode("\n", $docComment);
            $description_line = array_key_first(array_filter($lines, function($line) {
                return str_contains($line, '@description');
            }));
            $maybe_one_or_two_more_lines = array_filter(
                array_slice($lines, $description_line + 1, 2),
                fn($line) => trim($line) !== ''
                    && trim($line) !== '*/'
                    && !str_starts_with(trim($line), "* @")
                    && !str_starts_with(trim($line), '* @dev-notes')
            );
            if (!empty($maybe_one_or_two_more_lines)) {
                $description .= ' ' . trim(implode(' ', array_map(fn($line) => trim(ltrim($line, '* ')), $maybe_one_or_two_more_lines)));
            }

            return trim($description);
        }

        return null;
    }

    private function getVisibility(ReflectionProperty $property): string {
        if ($property->isPrivate()) {
            return 'private';
        }
        if ($property->isProtected()) {
            return 'protected';
        }

        return 'public';
    }

    /**
     * Extracts the default value from constructor calls to set_*_from_attrs methods
     *
     * @param  ReflectionClass  $class  The class to analyze
     * @param  string  $propertyName  The name of the property to find defaults for
     *
     * @return string|null The default value or null if not found
     */
    private function extractDefaultFromConstructor(ReflectionClass $class, string $propertyName): ?string {
        if (!$class->hasMethod('__construct')) {
            return null;
        }

        $constructor = $class->getMethod('__construct');
        $filename = $constructor->getFileName();
        $startLine = $constructor->getStartLine();
        $endLine = $constructor->getEndLine();

        if (!$filename) {
            return null;
        }

        $fileContent = file_get_contents($filename);
        $lines = explode("\n", $fileContent);
        $constructorCode = implode("\n", array_slice($lines, $startLine - 1, $endLine - $startLine + 1));

        // Map property names to their setter method patterns
        $propertyToMethodMap = [
            'colorTheme' => 'set_color_theme_from_attrs',
            'size'       => 'set_size_from_attrs',
            'width'      => 'set_width_from_attrs',
            'alignment'  => 'set_alignment_from_attrs',
            'variant'    => 'set_variant_from_attrs',
            'background' => 'set_background_from_attrs',
            'style'      => 'set_style_from_attrs',
            // Add more mappings as needed
        ];

        // Get the method name for this property (or use a pattern for unknown properties)
        $methodName = $propertyToMethodMap[$propertyName] ?? "set_{$propertyName}_from_attrs";

        // Look for calls to the setter method with a default parameter
        $pattern = '/\$this->' . preg_quote($methodName, '/') . '\s*\(\s*\$\w+\s*,\s*([A-Za-z0-9_\\\\:\'\"]+)\s*\)/i';
        if (preg_match($pattern, $constructorCode, $matches)) {
            $defaultValue = trim($matches[1]);

            // Extract just the enum value if it's in the form EnumClass::VALUE
            if (str_contains($defaultValue, '::')) {
                $parts = explode('::', $defaultValue);

                return trim(strtolower($parts[1]));
            }

            return $defaultValue;
        }

        // If there is no default parameter, look in the component defaults in the Config
        $componentDefaults = Config::getInstance()->get_component_defaults($class->getShortName());
        if ($componentDefaults && isset($componentDefaults[$propertyName])) {
            if (in_array(gettype($componentDefaults[$propertyName]), ['string', 'integer', 'float', 'double', 'boolean'])) {
                return (string)$componentDefaults[$propertyName];
            }

            try {
                // Probably an enum at this point
                return $componentDefaults[$propertyName]->value;
            }
            catch (Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * Extracts the type of a property, including whether it's required, the default value, and the description.
     *
     * @param  ReflectionProperty  $property
     *
     * @return array
     * @throws ReflectionException
     */
    private function getPropertyType(ReflectionProperty $property): array {
        $required = !$property->getType()->allowsNull();
        $type = $property->getType();
        $description = null;
        $defaultValue = $property->hasDefaultValue() ? $property->getDefaultValue() : null; // for enums, this comes from the enum itself
        $supportedValues = null;
        $result = $this->processPropertyType($type);

        // Handle default boolean values
        if ($type instanceof ReflectionNamedType && $type->getName() === 'bool' && $defaultValue === false) {
            $defaultValue = 'false';
        }
        else if ($type instanceof ReflectionNamedType && $type->getName() === 'bool' && $defaultValue === true) {
            $defaultValue = 'true';
        }

        // Special handling for properties that might have defaults set in from_attrs trait methods
        $propertyName = $property->getName();
        $knownTraitProperties = [
            'colorTheme', 'backgroundColor', 'hAlign', 'vAlign', 'size', 'orientation', 'textAlign', 'textColor'
        ];

		// Override the default value if the component has a different default set than the enum default
        if (in_array($propertyName, $knownTraitProperties) || str_contains($propertyName, 'Theme')) {
            $customDefault = $this->extractDefaultFromConstructor($this->currentClass, $propertyName);
            if ($customDefault !== null) {
                $defaultValue = strtolower($customDefault); // this assumes enum cases translate directly from uppercase cases to lowercase values
            }
        }

        // Compute the actual defaults for some properties
        if (!$this->currentClass->isAbstract()) {
            $instance = $this->currentClass->newInstanceArgs(
                array_map(function($param) {
                    try {
                        if (!method_exists($param->getType(), 'getName')) {
                            return '';
                        }

                        if ($param->getType()->getName() === 'Doubleedesign\Comet\Core\PanelGroupComponent') {
                            return new Accordion([], []);
                        }

                        return match ($param->getType()?->getName()) {
                            'array'  => [],
                            'string' => '',
                            'int'    => 0,
                            'float'  => 0.0,
                            'bool'   => false,
                            default  => null
                        };
                    }
                    catch (ReflectionException) {
                        return null;
                    }
                }, $this->currentClass->getConstructor()?->getParameters() ?? [])
            );

            if ($propertyName === 'classes' && method_exists($instance, 'get_filtered_classes')) {
                $classes = $this->currentClass->getMethod('get_filtered_classes')?->invoke($instance) ?? [];
                $defaultValue = $classes;
            }

            if ($propertyName === 'context' && method_exists($instance, 'get_context')) {
                $context = $this->currentClass->getMethod('get_context')?->invoke($instance) ?? null;
                $defaultValue = $context;
            }

            if ($propertyName === 'shortName' && method_exists($instance, 'get_shortname')) {
                $shortName = $this->currentClass->getMethod('get_shortname')?->invoke($instance) ?? null;
                $defaultValue = $shortName;
            }

            if ($propertyName === 'isNested' && method_exists($instance, 'get_is_nested')) {
                $isNested = $this->currentClass->getMethod('get_is_nested')?->invoke($instance) ?? false;
                $defaultValue = $isNested;
            }

        }

        // Get type details from docblock if available
        $docComment = $property->getDocComment();
        if ($docComment && preg_match('/@description\s+(.+)/', $docComment, $matches)) {
            $description = $this->getDescription($docComment);
        }
        // Try to get description from parent class if it exists
        else {
            $parentClass = $this->declaringClass->getParentClass();
            if ($parentClass) {
                try {
                    $parentProperty = $parentClass->getProperty($property->getName());
                    $parentDocComment = $parentProperty->getDocComment();
                    if ($parentDocComment && preg_match('/@description\s+(.+)/', $parentDocComment, $matches)) {
                        $description = $this->getDescription($parentDocComment);
                    }
                }
                catch (ReflectionException $e) {
                    // Property doesn't exist in parent class
                }
            }
        }
        if ($docComment && preg_match('/@supported-values\s+(.+)/', $docComment, $matches)) {
            $supportedValues = array_map('trim', explode(',', $matches[1]));
        }
        // Get default values from docblock if not already set
        if (!isset($defaultValue) && $docComment && preg_match('/@default-value\s+(.+)/', $docComment, $matches)) {
           $defaultValue = trim($matches[1]);
        }
        // Use type from docblock if specified, to use declared types like array<string>
        if ($docComment && preg_match('/@var\s+(\S+)/', $docComment, $matches)) {
            $type = trim($matches[1]);
        }
        else {
            $type = $result['type'];
        }

        // Supported and default values may be set in ways other than docblock - e.g., enum values, class attributes
        // If those are returned from processPropertyType, use them
        if (isset($result['supported'])) {
            $supportedValues = $result['supported'];
        }
        if (isset($result['default'])) {
            $defaultValue = $result['default'];
        }

        // Sort supported values so 'default' is always at the top
        if ($supportedValues) {
            usort($supportedValues, function($a, $b) {
                if ($a === 'default') {
                    return -1;
                }
                if ($b === 'default') {
                    return 1;
                }

                return 0;
            });
        }

        // $required takes care of these rather than having them in the field names
        $trimmedType = str_replace('?', '', $type);
        $trimmedType = str_replace('|null', '', $trimmedType);

        $result = [
            'type'        => $trimmedType,
            'description' => $description,
            'required'    => $required,
            'supported'   => $supportedValues,
            'default'     => $defaultValue,
        ];

        return array_filter($result, function($value, $key) {
            if (in_array($key, ['context', 'shortName'])) {
                return true;
            }

            if ($key === 'required' && $value === false) {
                return false;
            }

            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Processes the type of a property, returning an array with the short type name (no namespace)
     * and supported values if it's an enum.
     *
     * @param  ReflectionNamedType|ReflectionUnionType  $type
     *
     * @return array|string[]
     */
    private function processPropertyType(ReflectionNamedType|ReflectionUnionType $type): array {
        if ($type instanceof ReflectionNamedType) {
            $typeName = $type->getName();

            if (!class_exists($typeName)) {
                return ['type' => $typeName];
            }

            $reflectionType = new ReflectionClass($typeName);
            $namespace = $reflectionType->getNamespaceName();

            // If it's a Tag property, get default and supported tags from the class attributes
            if ($typeName === Tag::class) {
                try {
                    $allowedTagsAttr = $this->currentClass->getAttributes(AllowedTags::class)[0] ?? null;
                    $defaultTagAttr = $this->currentClass->getAttributes(DefaultTag::class)[0] ?? null;
                    $allowedTags = $allowedTagsAttr->newInstance()->tags;
                    $defaultTag = $defaultTagAttr->newInstance()->tag;

                    return [
                        'type'      => str_replace("$namespace\\", '', $typeName),
                        'supported' => array_values(array_map(fn($tag) => $tag->value, $allowedTags)),
                        'default'   => $defaultTag->value
                    ];
                }
                catch (\Throwable $e) {
                    $this->log("Error processing AllowedTags or DefaultTag attributes: " . $e->getMessage(), 'error');

                    return [];
                }
            }

            if ($reflectionType->isEnum()) {
                $cases = $reflectionType->getConstants();
                $supportedValues = array_map(function($case) {
                    return $case->value;
                }, $cases);

                return [
                    'type'      => str_replace("$namespace\\", '', $typeName),
                    'supported' => array_values($supportedValues)
                ];
            }

            // If it's not an enum or the class doesn't exist, return the original type name
            return ['type' => $typeName];
        }
        else if ($type instanceof ReflectionUnionType) {
            $types = $type->getTypes();

            $processedTypes = array_map(function($type) {
                return $this->processPropertyType($type);
            }, $types);

            // If Comet classes are an option, assume that's what we want and just return that
            // e.g., in Table, the caption can be a TableCaption or an array corresponding to a TableCaption, so we just list TableCaption here
            // TODO: Should this allow for more than one type?
            return array_filter($processedTypes, function($type) {
                return str_starts_with($type['type'], 'Doubleedesign\Comet\Core');
            })[0] ?? [];
        }

        return [];
    }

    /**
     * Exports the processed data as a JSON file to the specified output path.
     *
     * @param  string  $outputPath  Where to save the file.
     * @param  array  $data  The array of data to be encoded into JSON and exported.
     *
     * @return void
     */
    public function exportToJson(string $outputPath, array $data): void {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($outputPath, $json);
    }

    private static function log(string $message, string $type): void {
        // ANSI colour codes
        $red = "\033[0;31m";
        $green = "\033[0;32m";
        $yellow = "\033[0;33m";
        $cyan = "\033[0;36m";
        $white = "\033[0;37m";
        $reset = "\033[0m";

        $color = match ($type) {
            'info'    => $cyan,
            'success' => $green,
            'error'   => $red,
            'warning' => $yellow,
            default   => $white,
        };

        echo $color . $message . $reset . "\n";

        if ($type === 'error') {
            \Symfony\Component\VarDumper\VarDumper::dump([
                'message'     => $message,
                'backtrace'   => debug_backtrace()
            ]);
        }
    }
}

// Usage: cd into /scripts first, then:
//        php generate-docs.php
//        or php generate-docs.php --component MyComponent (Bash)
//           php generate-docs.php component MyComponent (PowerShell)
//        or php generate-docs.php --base MyBaseComponent for base abstract component classes (Bash)
//           php generate-docs.php base MyBaseComponent (PowerShell)
try {
    set_error_handler(function($severity, $message, $file, $line) {
        if (str_contains($message, 'Undefined array key')) {
            return;
        }
        throw new ErrorException($message, 0, $severity, $file, $line);
    });

    $instance = new ComponentClassesToJsonDefinitions();
    if (isset($argv[1]) && ($argv[1] === '--component' || $argv[1] === 'component') && isset($argv[2])) {
        $instance->runSingle($argv[2]);
        shell_exec('php scripts/generate-xml.php');
    }
    else if (isset($argv[1]) && ($argv[1] === '--base' || $argv[1] === 'base') && isset($argv[2])) {
        $instance->runSingleBase($argv[2]);
    }
    else {
        $instance->runAll();
        shell_exec('php scripts/generate-xml.php');
    }
    echo "Done!\n";
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    \Symfony\Component\VarDumper\VarDumper::dump($e);
}
finally {
    restore_error_handler();
}
