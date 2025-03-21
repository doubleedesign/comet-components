import { defaultTheme } from '@vuepress/theme-default';
import { defineUserConfig } from 'vuepress/cli';
import { viteBundler } from '@vuepress/bundler-vite';
import path from 'path';
import fs from 'fs';
import Case from 'case';

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
			...generateSidebar().map(item => ({
				text: item.text,
				link: item.link
			}))
		],

		sidebar: [
			{
				text: 'Introduction',
				link: '/intro.html',
			},
			...generateSidebar()
		],

		// Auto-open the current section
		sidebarDepth: 1,
	}),

	bundler: viteBundler(),

	head: [
		['link', { rel: 'icon', type: 'image/png', sizes: '32x32', href: '/comet.png' }],
	],
});

// Get the children pages for a specific section folder
function getSectionChildren(folderName) {
	const folderPath = path.join(docsDir, folderName);
	// Check if the folder exists
	if (!fs.existsSync(folderPath) || !fs.statSync(folderPath).isDirectory()) {
		return [];
	}

	return fs
		.readdirSync(folderPath, { withFileTypes: true })
		.filter((child) => child.isFile() && child.name.endsWith('.md'))
		.map((child) => {
			const name = child.name.replace('.md', '');
			if (name === 'README') {
				return '';
			}

			return name;
		})
		.filter(name => name !== '');
}


// Generate structured sidebar items
function generateSidebar() {
	const preferredOrder = ['Usage', 'Development'];
	const items = [];
	const files = fs.readdirSync(docsDir, { withFileTypes: true });

	files.forEach((file) => {
		if (file.isDirectory() && file.name !== '.vuepress') {
			const folderName = file.name;
			const sectionTitle = Case.title(folderName);

			items.push({
				text: sectionTitle,
				link: `/${folderName}/`,
				collapsible: true,  // Make it collapsible
				children: getSectionChildren(folderName).map(child => `/${folderName}/${child}`)
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
