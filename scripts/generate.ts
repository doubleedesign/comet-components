import { existsSync, readFileSync, mkdirSync, writeFileSync } from 'fs';
import Case from 'case';
import * as path from 'node:path';
import { execSync } from 'child_process';

// Get the component name and type from command line arguments
// WSL command: 'npm run generate component -- --name=YourThing --type=complex'
// PowerShell command: 'npm run generate component -- --name YourThing --type complex' (no equals signs)
const args = process.argv.slice(2);
if(args[0] !== 'component' || args.length < 3) {
	console.error('Invalid command. Usage: \n Bash: npm run generate component -- --name=<name> --type=<simple or complex> \n PowerShell: npm run generate component -- --name <name> --type <simple or complex>');
	process.exit(1);
}

// Split is for the Bash command, PowerShell command doesn't need it because it doesn't use equals signs
const componentName = args[1].split('=')[1] ?? args[2];
const componentType = args[2].split('=')[1] ?? args[4];
if(componentType && !['simple', 'complex', 'wrapper'].includes(componentType)) {
	console.error('Invalid type. Valid types are "simple", "complex", and "wrapper".');
	console.log('Usage: npm run generate component -- --name=<name> --type=<simple or complex>');
	process.exit(1);
}

function generateSkeletonFiles({ componentName, componentType }) {
	console.log(`Generating ${componentType} component: ${componentName}`);
	// The template for the core PHP class for this component
	const classTemplateFile = readFileSync(`./scripts/templates/${Case.pascal(componentType)}Component.php`, 'utf8');
	// The template for the Blade template file that will be used to render the component
	const bladeTemplateFile = readFileSync('./scripts/templates/template.blade.php', 'utf8');
	// The template for the CSS file for non-trivial components
	const cssTemplateFile = readFileSync('./scripts/templates/template.scss', 'utf8');

	const shortName = Case.kebab(componentName);
	let className = Case.pascal(componentName);
	const reservedWords = ['List'];
	if(reservedWords.includes(className)) {
		className = `${className}Component`;
	}

	const classPath = `./packages/core/src/components/${className}/${className}.php`;
	const templatePath = `./packages/core/src/components/${className}/${shortName}.blade.php`;

	// Bail if the file already exists
	const classExists = existsSync(classPath);
	const templateExists = existsSync(templatePath);

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

// Then run updates to supplementary files and configuration
console.log('Updating XML definition for Tycho Template syntax');
execSync('php scripts/generate-xml.php');
console.log('IMPORTANT: Remember to update the Composer autoloader manually, as this script cannot do it yet.');
//console.log('Updating autoloader');
//execSync('cd packages/core && composer dump-autoload -o');
//execSync('cd ../..');
