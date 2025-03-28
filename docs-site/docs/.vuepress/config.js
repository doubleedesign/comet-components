import { defaultTheme } from '@vuepress/theme-default';
import { defineUserConfig } from 'vuepress/cli';
import { viteBundler } from '@vuepress/bundler-vite';
import path from 'path';
import fs from 'fs';
import Case from 'case';
import { markdownTabPlugin } from '@vuepress/plugin-markdown-tab';
import { markdownExtPlugin } from '@vuepress/plugin-markdown-ext';

const docsDir = path.resolve(__dirname, '../');

export default defineUserConfig({
	lang: 'en-AU',

	title: 'Comet Components',
	description: 'A front-end user interface library for PHP-driven websites',

	theme: defaultTheme({
		logo: '/comet.png',
		repo: 'doubleedesign/comet-components',
		repoLabel: 'GitHub',
		navbar: [
			'/',
			{
				text: 'Introduction',
				link: '/intro.html',
			},
			{
				text: 'Getting started',
				link: '/getting-started/wordpress.html',
			},
			{
				text: 'Using and extending',
				link: '/usage/overview.html'
			},
			{
				text: 'Development',
				link: '/development/overview.html',
			}
		],
		sidebar: [
			{
				text: 'Introduction',
				link: '/intro.html',
			},
			...generateSidebar()
		],
		sidebarDepth: 1,
		markdown: {
			lineNumbers: true,
			prism: {
				theme: 'prism-themes/themes/prism-atom-dark.css'
			},
		},
	}),

	plugins: [
		markdownTabPlugin({
			tabs: true,
		}),
		markdownExtPlugin({
			gfm: true,
			footnote: true
		}),
	],

	bundler: viteBundler(),

	head: [
		['link', { rel: 'icon', type: 'image/png', sizes: '32x32', href: '/comet.png' }],
	],
});

// Generate structured sidebar items
function generateSidebar() {
	const preferredOrder = ['Getting Started', 'Usage', 'Development', 'Technical Deep Dives', 'About'];
	const items = [];
	const files = fs.readdirSync(docsDir, { withFileTypes: true });

	files.forEach((file) => {
		if (file.isDirectory() && file.name !== '.vuepress') {
			const folderName = file.name;
			// Check if there's a README.md file for the main section
			const readmePath = path.join(docsDir, folderName, 'README.md');
			const hasReadme = fs.existsSync(readmePath);

			// Try to extract title from README if it exists
			let sectionTitle = Case.title(folderName).replace('Js', 'JS').replace('Php', 'PHP');
			if (hasReadme) {
				const extractedTitle = extractTitleFromMarkdown(readmePath);
				if (extractedTitle) {
					sectionTitle = extractedTitle;
				}
			}

			items.push({
				text: sectionTitle,
				link: hasReadme ? `/${folderName}/` : undefined,
				collapsible: true,
				children: getSectionChildren(folderName)
			});
		}
	});

	// Sort according to preferred order
	return items.sort((a, b) => {
		const aIndex = preferredOrder.indexOf(a.text);
		const bIndex = preferredOrder.indexOf(b.text);
		if (aIndex === -1 && bIndex === -1) {
			return a.text.localeCompare(b.text);
		}
		if (aIndex === -1) {
			return 1;
		}
		if (bIndex === -1) {
			return -1;
		}

		return aIndex - bIndex;
	});
}

// Get the child pages for a specific section folder, including nested subfolders
function getSectionChildren(folderName) {
	const folderPath = path.join(docsDir, folderName);
	// Check if the folder exists
	if (!fs.existsSync(folderPath) || !fs.statSync(folderPath).isDirectory()) {
		return [];
	}

	const children = [];

	// Get all items in the directory
	const items = fs.readdirSync(folderPath, { withFileTypes: true });

	// Process files first
	const filesWithMetadata = items
		.filter((item) => item.isFile() && item.name.endsWith('.md'))
		.map((file) => {
			const name = file.name.replace('.md', '');
			if (name !== 'README') {
				const filePath = path.join(folderPath, file.name);
				const title = extractTitleFromMarkdown(filePath) ?? Case.title(name);
				const position = extractPagePositionFromMarkdown(filePath);

				return {
					text: title,
					link: `/${folderName}/${name}`,
					position: position !== null ? parseInt(position, 10) : null
				};
			}

			return null;
		})
		.filter(Boolean);

	// Sort files by position first, then by title
	filesWithMetadata.sort((a, b) => {
		// If both have positions, sort numerically
		if (a.position !== null && b.position !== null) {
			return a.position - b.position;
		}
		// If only a has position, it comes first
		if (a.position !== null) {
			return -1;
		}
		// If only b has position, it comes first
		if (b.position !== null) {
			return 1;
		}

		// If neither has position, sort alphabetically
		return a.text.localeCompare(b.text);
	});

	// Add the sorted files to children
	children.push(...filesWithMetadata);

	// Process subfolders
	items
		.filter((item) => item.isDirectory())
		.forEach((subfolder) => {
			const subfolderName = subfolder.name;
			const subfolderPath = path.join(folderPath, subfolderName);
			const subfolderItems = [];

			// Get markdown files in the subfolder
			const subfolderFilesWithMetadata = fs.readdirSync(subfolderPath, { withFileTypes: true })
				.filter((subItem) => subItem.isFile() && subItem.name.endsWith('.md'))
				.map((subFile) => {
					const name = subFile.name.replace('.md', '');
					if (name !== 'README') {
						const filePath = path.join(subfolderPath, subFile.name);
						const title = extractTitleFromMarkdown(filePath) ?? Case.title(name);
						const position = extractPagePositionFromMarkdown(filePath);

						return {
							text: title,
							link: `/${folderName}/${subfolderName}/${name}`,
							position: position !== null ? parseInt(position, 10) : null
						};
					}

					return null;
				})
				.filter(Boolean);

			// Sort subfolder files by position first, then by title
			subfolderFilesWithMetadata.sort((a, b) => {
				// If both have positions, sort numerically
				if (a.position !== null && b.position !== null) {
					return a.position - b.position;
				}
				// If only a has position, it comes first
				if (a.position !== null) {
					return -1;
				}
				// If only b has position, it comes first
				if (b.position !== null) {
					return 1;
				}

				// If neither has position, sort alphabetically
				return a.text.localeCompare(b.text);
			});

			// Add the sorted subfolder files
			subfolderItems.push(...subfolderFilesWithMetadata);

			// Check if subfolder has README for its title
			const subfolderReadmePath = path.join(subfolderPath, 'README.md');
			let subfolderTitle = Case.title(subfolderName)
				.replace('Js', 'JS')
				.replace('Php', 'PHP');

			if (fs.existsSync(subfolderReadmePath)) {
				const extractedTitle = extractTitleFromMarkdown(subfolderReadmePath);
				if (extractedTitle) {
					subfolderTitle = extractedTitle;
				}
			}

			// Add the subfolder with its children if it has any content
			if (subfolderItems.length > 0) {
				children.push({
					text: subfolderTitle,
					collapsible: true,
					children: subfolderItems
				});
			}
		});

	// Sort the top-level items - folders still come after files
	return children.sort((a, b) => {
		// If it's a file vs subfolder, files come first
		if (!a.children && b.children) return -1;
		if (a.children && !b.children) return 1;

		// If both are files, they've already been sorted by position and title
		if (!a.children && !b.children) {
			return 0; // Keep the existing order from our previous sort
		}

		// If both are folders, sort alphabetically
		return a.text.localeCompare(b.text);
	});
}

// Function to extract title from markdown file
function extractTitleFromMarkdown(filePath) {
	try {
		const content = fs.readFileSync(filePath, 'utf8');

		// Look for the first heading in the file
		const titleMatch =
			content.match(/^title:\s*(.+)$/m) // Match YAML frontmatter title: Title
			|| content.match(/^#\s+(.+)$/m); // Match # Title

		if (titleMatch && titleMatch[1]) {
			return titleMatch[1].trim();
		}

		return null;
	}
	catch (error) {
		console.error(`Error reading file ${filePath}:`, error);

		return null;
	}
}

function extractPagePositionFromMarkdown(filePath) {
	try {
		const content = fs.readFileSync(filePath, 'utf8');

		const positionMatch =
			content.match(/^position:\s*(.+)$/m); // Match YAML frontmatter position: number

		if (positionMatch && positionMatch[1]) {
			return positionMatch[1].trim();
		}

		return null;
	}
	catch (error) {
		console.error(`Error reading file ${filePath}:`, error);

		return null;
	}
}
