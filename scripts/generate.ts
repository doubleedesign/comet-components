import { existsSync, readFileSync, mkdirSync, writeFileSync } from 'fs';
import Case from 'case';
import * as path from 'node:path';
import { execSync } from 'child_process';

// Get the component name and type from command line arguments
// Command is in the format: 'npm run generate-component name --type=complex'
const args = process.argv.slice(2);
if(args[0] !== 'component' || !args[1] || !args[1].startsWith('--name=')) {
	console.error('Invalid command. Usage: npm run generate component -- --name=<name> --type=<simple or complex>');
	process.exit(1);
}
const componentName = args[1].split('=')[1];
const componentType = args[2].split('=')[1];
if(args[2] && !['simple', 'complex', 'wrapper'].includes(componentType)) {
	console.error('Invalid type. Valid types are "simple", "complex", and "wrapper".');
	console.log('Usage: npm run generate component -- --name=<name> --type=<simple or complex>');
	process.exit(1);
}


/**
 * Generate:
 * 1. A class that will be used to build the output (PascalCase block name without prefix)
 * 2. A template file in the test directory that will be used to render the block using the associated class (lowercase block name without prefix)
 */
generateSkeletonFiles({
	componentName,
	componentType,
});

function generateSkeletonFiles({ componentName, componentType }) {
	// The template for the core PHP class for this component
	const classTemplateFile = readFileSync(`./scripts/templates/${Case.pascal(componentType)}Component.php`, 'utf8');
	const templateClassName = `${Case.pascal(componentType)}Component`;
	// The template for the sample usage/output file placed in a folder that will be used for testing
	const testFileTemplate = readFileSync(`./scripts/templates/${Case.snake(componentType)}.php`, 'utf8');

	const shortName = Case.snake(componentName);
	let className = Case.pascal(componentName);
	const reservedWords = ['List'];
	if(reservedWords.includes(className)) {
		className = `${className}Component`;
	}

	const classPath = `./src/components/${className}/${className}.php`;
	const testFilePath = `./test/browser/components/${shortName}.php`;
	const defFilePath = `./src/components/${className}/${className}.json`;

	// Bail if the file already exists
	const classExists = existsSync(classPath);
	const testExists = existsSync(testFilePath);
	const defExists = existsSync(defFilePath);
	if(classExists && testExists && defExists) {
		console.log(`Skipping ${componentName} because the files already exist`);

		return;
	}

	if(!classExists) {
		// Create directory if it doesn't exist
		mkdirSync(path.dirname(classPath), { recursive: true });
		const classOutput = classTemplateFile.replaceAll(templateClassName, className);
		writeFileSync(classPath, classOutput, 'utf8');

		if(existsSync(classPath)) {
			console.log(`Class file created successfully at ${classPath}`);
		}
	}
	else {
		console.log(`Class file for ${componentName} already exists, skipping`);
	}

	if(!testExists) {
		const templateOutput = testFileTemplate.replaceAll(templateClassName, className);
		mkdirSync(path.dirname(testFilePath), { recursive: true });
		writeFileSync(testFilePath, templateOutput, 'utf8');

		if(existsSync(testFilePath)) {
			console.log(`Test/example usage file created successfully at ${testFilePath}`);
		}
	}
	else {
		console.log(`Template file for ${componentName} already exists, skipping`);
	}

	if(!defExists) {
		const defOutput = execSync(`php ${classPath} --component ${className}`).toString();
		writeFileSync(defFilePath, defOutput, 'utf8');

		if(existsSync(defFilePath)) {
			console.log(`Definition file created successfully at ${defFilePath}`);
		}
	}
	else {
		console.log(`Definition file for ${className} already exists, skipping`);
	}
}
