<?php

/**
 * Generate the tycho-fetch.xsd file from component JSON definitions.
 * The tycho-fetch.xsd file can be used to make IDEs understand the XML component syntax used for "Tycho Template" syntax.
 */
class XmlDefGenerator {
	private string $sourceDirectory;
	private string $outputFile;

	public function __construct() {
		$this->sourceDirectory = dirname(__DIR__, 1) . '\packages\core\src\components';
		$this->outputFile = dirname(__DIR__, 1) . '\packages\core\tycho-fetch.xsd';
	}

	/**
	 * @throws UnexpectedValueException
	 */
	public function run(): void {
		// Collect all the JSON files within the source directory, including nested component directories
		// But not the test directory
		$files = $this->get_files();
		$types = array();
		$components = array();

		foreach($files as $file) {
			$data = json_decode(file_get_contents($file), true);
			if(!isset($data['name']) || !isset($data['attributes'])) {
				throw new UnexpectedValueException("Invalid JSON definition file: $file");
			}

			$name = $data['name'];
			$attributeOutput = array();

			foreach($data['attributes'] as $attribute => $details) {
				$type = $details['type'];
				// Handle booleans
				if($type === 'bool') {
					$type = 'xs:boolean';
				}
				// Keep Tag simple for now by making it just a string because different components allow different tags
				else if($type === 'Tag') {
					$type = 'xs:string';
				}
				// Handle array types
				else if($type === 'array' || str_starts_with($type, 'array')) {
					if($type === "array<string>") {
						$type = 'StringArray';
					}
					else if($attribute === 'style') {
						$types[$type] = <<<EOT
						<xs:complexType name="InlineStyle">
							<xs:attribute name="key" type="xs:string" use="required"/>
							<xs:attribute name="value" type="xs:string" use="required"/>
						</xs:complexType>
						EOT;
					}
					else if($attribute === 'breadcrumbs') {
						$type = "xs:complexType";
					}
					else if($attribute === 'focalPoint') {
						$type = 'FocalPoint';
					}
				}
				// Work around object/array-corresponding-to-object types not working by naming them directly in this script for now
				// and just specifying that they are a complexType (not which one...yet)
				else if(in_array($attribute, ['breadcrumbs', 'caption'])) {
					$type = 'xs:complexType';
				}
				else if($type === 'string|mixed') {
					$type = 'xs:string';
				}
				// If the type is not a primitive or otherwise identified,
				// assume it is something custom and add it to the $types if it's not already there
				else if(!in_array($type, ['string', 'int', 'double', 'float', 'boolean'])) {
					if(str_ends_with($type, '|array')) {
						$type = str_replace('|array', '', $type);
					}

					if(!isset($types[$type]) && isset($details['supported'])) {
						$values = join("\n", array_map(function($value) {
							return "<xs:enumeration value=\"$value\"/>";
						}, $details['supported']));

						$types[$type] = <<<EOT
						<xs:simpleType name="$type">
							<xs:restriction base="xs:string">
								$values
							</xs:restriction>
						</xs:simpleType>
						EOT;
					}
				}
				else {
					$type = "xs:$type";
				}

				if($attribute === 'style') {
					array_push($attributeOutput, <<<EOT
					<xs:attribute name="style" type="InlineStyle"/>
					EOT);
				}
				else {
					array_push($attributeOutput, <<<EOT
					<xs:attribute name="$attribute" type="$type"/>
					EOT);
				}
			}

			// Sort $attributeOutput so that sequences come before attributes, because XML requires that for some reason
			usort($attributeOutput, function($line) {
				if(str_contains($line, '<xs:sequence>')) {
					return 0;
				}
				return 1;
			});
			// Then make it a string to put in the template
			$attributeOutput = join("\n", $attributeOutput);

			$xmlItem = <<<EOT
				<xs:element name="$name">
					<xs:complexType>
						$attributeOutput
					</xs:complexType>
				</xs:element>
			EOT;
			array_push($components, $xmlItem);
		}

		$typeDefs = join("\n", $types);
		$componentDefs = join("\n", $components);

		$fileOutput = <<<EOT
		<?xml version="1.0" encoding="UTF-8"?>
		<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
			$typeDefs
			<xs:simpleType name="StringArray">
				<xs:list itemType="xs:string"/>
			</xs:simpleType>
			<xs:element name="InlineStyle">
				<xs:complexType>
					<xs:attribute name="key" type="xs:string" use="required"/>
					<xs:attribute name="value" type="xs:string" use="required"/>
				</xs:complexType>
			</xs:element>
			<xs:simpleType name="FocalPoint">
				<xs:restriction>
					<xs:simpleType>
						<xs:list itemType="xs:int"/>
					</xs:simpleType>
					<xs:length value="2"/>
				</xs:restriction>
			</xs:simpleType>
			$componentDefs
			<xs:element name="TychoTemplate"/>
		</xs:schema>
		EOT;

		file_put_contents($this->outputFile, $fileOutput);
	}

	/**
	 * @throws UnexpectedValueException
	 */
	private function get_files(): array {
		$files = [];
		$directory = new RecursiveDirectoryIterator($this->sourceDirectory);
		$iterator = new RecursiveIteratorIterator($directory);

		foreach($iterator as $file) {
			// Skip "tests" directories
			if(str_contains($file->getPathname(), "__tests__")) {
				continue;
			}

			// Only include .json files
			if($file->isFile() && $file->getExtension() === 'json') {
				$files[] = $file->getPathname();
			}
		}

		return $files;
	}

}

// Usage: php scripts/generate-xml.php
try {
	$instance = new XmlDefGenerator();
	$instance->run();
	echo "Done!\n";
}
catch(Exception $e) {
	if($e instanceof UnexpectedValueException) {
		echo "Error: " . $e->getMessage() . "\n";
	}
	else {
		print_r($e);
	}
}
