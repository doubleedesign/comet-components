import { existsSync, readFileSync, mkdirSync, writeFileSync } from 'fs';
import Case from 'case';
import * as path from 'node:path';
import { execSync } from 'child_process';

// Get the component name and type from command line arguments
// Command is in the format: 'npm run generate component -- --name=YourThing --type=complex'
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
	console.log(`Generating ${componentType} component: ${componentName}`);
	// The template for the core PHP class for this component
	const classTemplateFile = readFileSync(`./scripts/templates/${Case.pascal(componentType)}Component.php`, 'utf8');
	// The template for the Blade template file that will be used to render the component
	const bladeTemplateFile = readFileSync('./scripts/templates/template.blade.php', 'utf8');
	// The template for the CSS file for non-trivial comopnents
	const cssTemplateFile = readFileSync('./scripts/templates/template.scss', 'utf8');
	// The template for the sample usage/output file placed in a folder that will be used for testing
	const testFileTemplate = readFileSync(`./scripts/templates/${Case.snake(componentType)}.php`, 'utf8');

	const shortName = Case.kebab(componentName);
	let className = Case.pascal(componentName);
	const reservedWords = ['List'];
	if(reservedWords.includes(className)) {
		className = `${className}Component`;
	}

	const classPath = `./packages/core/src/components/${className}/${className}.php`;
	const templatePath = `./packages/core/src/components/${className}/${shortName}.blade.php`;
	const testFilePath = `./test/browser/components/${shortName}.php`;
	const defFilePath = `./packages/core/src/components/${className}/${className}.json`;

	// Bail if the file already exists
	const classExists = existsSync(classPath);
	const templateExists = existsSync(templatePath);
	const testExists = existsSync(testFilePath);

	if(!classExists) {
		const templateClassName = `${Case.pascal(componentType)}Component`;
		mkdirSync(path.dirname(classPath), { recursive: true }); // Create directory if it doesn't exist
		const classOutput = classTemplateFile
			.replaceAll(templateClassName, className)
			.replaceAll('ThisComponent', className)
			.replaceAll('this-component', shortName);
		writeFileSync(classPath, classOutput, 'utf8');

		if(existsSync(classPath)) {
			console.log(`Class file created successfully at ${classPath}`);
		}
	}
	else {
		console.log(`Class file for ${componentName} already exists, skipping`);
	}

	if(!templateExists) {
		const templateOutput = bladeTemplateFile;
		mkdirSync(path.dirname(templatePath), { recursive: true });
		writeFileSync(templatePath, templateOutput, 'utf8');

		if(existsSync(templatePath)) {
			console.log(`Blade template file created successfully at ${templatePath}`);
		}
	}
	else {
		console.log(`Template file for ${componentName} already exists, skipping`);
	}

	if(componentType !== 'simple') {
		const cssPath = `./packages/core/src/components/${className}/${shortName}.scss`;
		const cssExists = existsSync(cssPath);
		if(!cssExists) {
			const cssOutput = cssTemplateFile.replace('component', shortName);
			writeFileSync(cssPath, cssOutput, 'utf8');

			if(existsSync(cssPath)) {
				console.log(`CSS file created successfully at ${cssPath}`);
			}
		}
		else {
			console.log(`CSS file for ${componentName} already exists, skipping`);
		}
	}

	//execSync('cd packages/core && composer dump-autoload -o');


	// if(!testExists) {
	// 	const templateOutput = testFileTemplate.replaceAll(templateClassName, className);
	// 	mkdirSync(path.dirname(testFilePath), { recursive: true });
	// 	writeFileSync(testFilePath, templateOutput, 'utf8');
	//
	// 	if(existsSync(testFilePath)) {
	// 		console.log(`Test/example usage file created successfully at ${testFilePath}`);
	// 	}
	// }
	// else {
	// 	console.log(`Template file for ${componentName} already exists, skipping`);
	// }
}
