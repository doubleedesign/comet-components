<?php
require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
use Doubleedesign\Comet\Core\Utils;

class TypescriptDefGenerator {
    private array $sourceDirectories;
    private string $outputFile;
    private array $data = [];

    public function __construct() {
        $this->sourceDirectories = [
            dirname(__DIR__, 1) . '/packages/core/src/base/traits',
            dirname(__DIR__, 1) . '/packages/core/src/base/components',
            dirname(__DIR__, 1) . '/packages/core/src/components',
        ];

        $this->outputFile = dirname(__DIR__, 1) . '\packages\core\dist\types.ts';
    }

    /**
     * @throws UnexpectedValueException
     */
    public function run() {
        $this->collect_raw_attributes()->filter_traits()->deduplicate();

        $enum_types_output = $this->build_enum_types();
        $trait_output = $this->build_trait_types();
        $base_component_output = $this->build_base_component_types();
        $component_output = $this->build_component_types();

        // Don't really need to list all the tags from the Tag enum
        $tag_type_output = "export type Tag = keyof HTMLElementTagNameMap;\n";

        // Things I can't be bothered dealing with because they're hardly used / will probably be removed
        $temp = <<<TYPESCRIPT
			type BackgroundCollection = any;
		TYPESCRIPT;

        $final_output = <<<TYPESCRIPT
			$tag_type_output
			$enum_types_output
			$temp
			$trait_output
			$base_component_output
			$component_output
		TYPESCRIPT;

        file_put_contents(
            $this->outputFile,
            $this->format_output_string($final_output)
        );
    }

    /**
     * @throws UnexpectedValueException
     */
    private function get_files($directories): array {
        $files = [];
        foreach ($directories as $dir) {
            $directory = new RecursiveDirectoryIterator($dir);
            $iterator = new RecursiveIteratorIterator($directory);

            foreach ($iterator as $file) {
                // Skip "tests" directories
                if (str_contains($file->getPathname(), "__tests__")) {
                    continue;
                }

                // Only include .json files
                if ($file->isFile() && $file->getExtension() === 'json') {
                    $files[] = $file->getPathname();
                }
            }
        }

        return $files;
    }

    /**
     * Gather all the raw attribute definitions from the JSON files in the source directory.
     *
     * @return self
     * @throws UnexpectedValueException
     */
    private function collect_raw_attributes(): self {
        // Collect all the JSON files within the source directory, including nested component directories
        // But not the test directory
        $files = $this->get_files($this->sourceDirectories);

        // Loop through each JSON file and extract the raw attribute definitions
        foreach ($files as $file) {
            $data = json_decode(file_get_contents($file), true);
            if (!isset($data['name']) || !isset($data['attributes'])) {
                throw new UnexpectedValueException("Invalid JSON definition file: $file");
            }

            $is_trait = str_contains($file, 'traits');
            $name = $data['name'];
            $this->data[$name] = [
                'extends'   => $data['extends'] ?? null,
                'abstract'  => $data['abstract'] ?? false,
                'trait'     => $is_trait,
                'traits'    => $data['traits'] ?? [],
                'fields'    => array_map(fn($details) => $details['type'], $data['attributes']),
            ];
        }

        return $this;
    }

    private function filter_traits(): self {
        $traits_to_include_in_typings = array_keys(array_filter($this->data, fn($item) => $item['trait'] === true));
        array_walk($this->data, function(&$itemData) use ($traits_to_include_in_typings) {
            $itemData['traits'] = array_filter($itemData['traits'], fn($trait) => in_array($trait, $traits_to_include_in_typings));
        });

        return $this;
    }

    /**
     * Remove fields that duplicate those of the type they extend.
     * This is a separate step from the initial collection because it's easier than
     * trying to control the processing order to ensure extended classes are processed before their children.
     *
     * @return self
     */
    private function deduplicate(): self {
        array_walk($this->data, function(&$itemData, $name) {
            $parent = $itemData['extends'];
            if (empty($parent)) {
                return;
            }

            $inheritedFields = $this->get_ancestor_fields_recursively($name);

            $itemData['fields'] = array_filter($itemData['fields'], function($field) use ($inheritedFields) {
                return !in_array($field, $inheritedFields);
            }, ARRAY_FILTER_USE_KEY);
        });

        return $this;
    }

    private function build_enum_types(): string {
        $directory = dirname(__DIR__, 1) . '/packages/core/src/base/types';
        $files = glob("$directory/*.php");
        $types = array_map(fn($filePath) => pathinfo($filePath, PATHINFO_FILENAME), $files);
        $types = array_diff($types, ['Tag', 'BackgroundCollection']);

        // We don't do JSON docs for these, but the autoloader should make the type enums/classes available to load directly
        $namespace = 'Doubleedesign\Comet\Core';
        $enums = [];
        $classes = [];
        foreach ($types as $type) {
            try {
                $instance = new ReflectionEnum("$namespace\\$type");
                $cases = $instance->getCases();
                $enums[$type] = array_map(fn($case) => $case->getValue()->value, $cases);
            }
            catch (ReflectionException $e) {
                if (str_ends_with($e->getMessage(), 'is not an enum')) {
                    $classes[$type] = $this->build_class_type($type);
                }
                else {
                    $this->log($e->getMessage(), 'error', $e);
                }
            }
        }

        $enums_const_values_output = join("\n", array_map(function($enum, $values) {
            $valuesString = join(', ', array_map(fn($value) => "'$value'", $values));
            $constName = Utils::screaming_snake_case($enum) . '_OPTIONS';

            return "export const $constName = [$valuesString];\n";
        }, array_keys($enums), $enums));

        $enums_types_output = join("\n", array_map(function($enum, $values) {
            $valuesString = join(' | ', array_map(fn($value) => "'$value'", $values));

            return "export type $enum = $valuesString; \n";
        }, array_keys($enums), $enums));

        $classes_output = $this->convert_items_to_string($classes);

        return $enums_const_values_output . "\n" . $enums_types_output . "\n" . $classes_output;
    }

    /**
     * Build the type string for a PHP "type" definition that is a class, not an enum as most of them are
     * (we don't do JSON docs for these)
     *
     * @param  $className
     *
     * @return array
     */
    private function build_class_type($className): array {
        $namespace = 'Doubleedesign\Comet\Core';
        try {
            $reflectionClass = new ReflectionClass("$namespace\\$className");
            $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PROTECTED);
            $output = [];
            foreach ($properties as $property) {
                $propertyName = $property->getName();
                $propertyType = $property->getType();
                $output[$propertyName] = str_replace("$namespace\\", "", $propertyType->getName());
            }

            return $output;
        }
        catch (ReflectionException $e) {
            $this->log($e->getMessage(), 'error', $e);

            return [];
        }
    }

    private function build_trait_types(): string {
        $traits = array_filter($this->data, fn($item) => $item['trait'] === true);
        $output = [];
        foreach ($traits as $name => $data) {
            $fields = $data['fields'];
            $nameToUse = $this->get_converted_trait_name($name);
            $output[$nameToUse] = array_map(fn($field) => $this->map_property_type($field), $fields);
        }

        return $this->convert_items_to_string($output);
    }

    private function build_base_component_types(): string {
        $components = array_filter($this->data, fn($item) => $item['abstract'] === true);
        $output = array_map(fn($data) => $this->map_properties($data), $components);

        return $this->convert_items_to_string($output);
    }

    private function build_component_types(): string {
        $components = array_filter($this->data, fn($item) => $item['abstract'] === false && $item['trait'] === false);
        $output = array_map(fn($data) => $this->map_properties($data), $components);

        return $this->convert_items_to_string($output, true);
    }

    private function map_properties(array $data): array {
        $fields = $data['fields'];

        return array_map(fn($type) => $this->map_property_type(trim($type)), $fields);
    }

    private function get_converted_trait_name(string $trait): string {
        if ($trait === 'LayoutContainerSize') {
            return 'WithContainerSize';
        }

        return "With" . $trait;
    }

    private function get_ancestor_fields_recursively(string $component): array {
        $itemData = $this->data[$component] ?? null;
        $parent = $itemData['extends'];
        if (!isset($parent)) {
            return $itemData['fields'] ?? []; // Probably Renderable, so we want to return its fields to its children
        }

        $traitFields = array_map(fn($trait) => array_keys($this->data[$trait]['fields']) ?? [], $itemData['traits']);
        $traitFields = Utils::array_flat($traitFields);
        $parentFields = array_keys($this->data[$parent]['fields']) ?? [];
        $parentTraits = $this->data[$parent]['traits'] ?? [];
        $parentTraitFields = array_keys(Utils::array_flat(array_map(fn($trait) => $this->data[$trait]['fields'] ?? [], $parentTraits)) ?? []);

        $fields = array_unique(array_merge($parentFields, $parentTraitFields, $traitFields));

        return array_merge(
            $this->get_ancestor_fields_recursively($parent),
            $fields
        );
    }

    private function map_property_type(string $type): string {
        if ($type === 'bool') {
            return 'boolean';
        }

        if ($type === 'int' || $type === 'float' || $type === 'double') {
            return 'number';
        }

        if (str_starts_with($type, 'array<string,')) {
            $innerType = str_replace(['array<string,', '>'], '', $type);
            $mappedInnerType = $this->map_property_type(trim($innerType));

            return "Record<string, $mappedInnerType>";
        }

        if (str_starts_with($type, 'array<') && str_ends_with($type, '>')) {
            $innerType = str_replace(['array<', '>'], '', $type);

            return $this->map_property_type(trim($innerType)) . '[]';
        }

        if (str_starts_with($type, 'array{')) {
            $innerBit = str_replace(['array{', '}'], '', $type);
            $bits = explode(',', $innerBit);
            $mappedBits = array_map(function($bit) {
                list($key, $value) = explode(':', $bit);
                $mappedValue = $this->map_property_type(trim($value));

                return trim($key) . ': ' . $mappedValue;
            }, $bits);

            return "{ " . implode('; ', $mappedBits) . " }";
        }

        return $type;
    }

    private function convert_items_to_string(array $output, $exported_type = false): string {
        $output_string = "";
        foreach ($output as $name => $fields) {
            $fields_string = "";
            foreach ($fields as $field_name => $field_type) {
                $fields_string .= "$field_name: $field_type;\n\t";
            }
            // Remove the last newline and tab characters from the fields string so we don't get an extra blank line before the closing brace
            $fields_string = rtrim($fields_string, "\n\t");

            $extends = "";

            if (isset($this->data[$name]['extends'])) {
                $extends .= $this->data[$name]['extends'];
            }

            if (!empty($this->data[$name]['traits'])) {
                if (!empty($extends)) {
                    $extends .= " & ";
                }

                $traits = array_map(fn($trait) => $this->get_converted_trait_name($trait), $this->data[$name]['traits']);
                $extends .= implode(' & ', array_unique($traits));
            }

            $prefix = $exported_type ? "\nexport " : "\n";
            $extends = !empty($extends) ? "$extends &" : "";
			$name = $exported_type ? "{$name}Props" : $name;

            if (empty($fields_string)) {
                $output_string .= "{$prefix}type $name = $extends; \n";
            }
            else {
                $output_string .= <<<TYPESCRIPT
					{$prefix}type $name = $extends {
						$fields_string
					}; \n
				TYPESCRIPT;
            }
        }

        return str_replace(" &;", ";", $output_string);
    }

    private function format_output_string($string): string {
        $lines = explode("\n", $string);
        array_walk($lines, function(&$line) {
            $trimmed = trim($line);
            if (!str_starts_with($trimmed, 'type') && !str_starts_with($trimmed, 'export') && !str_starts_with($trimmed, '}')) {
                $line = "\t" . $trimmed;
            }
            else {
                $line = $trimmed;
            }
        });

        return implode("\n", $lines);
    }

    private static function log(string $message, string $type, ?Exception $ex = null): void {
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
                'backtrace'   => debug_backtrace(),
                'exception'   => $ex,
            ]);
        }
    }
}

// Usage: php scripts/generate-types.php
try {
    $instance = new TypescriptDefGenerator();
    $instance->run();
    echo "Done!\n";
}
catch (Exception $e) {
    if ($e instanceof UnexpectedValueException) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    else {
        print_r($e);
    }
}
