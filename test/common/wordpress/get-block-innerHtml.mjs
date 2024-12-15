import React from 'react';
import ReactDOM from 'react-dom';
global.React = React;
global.ReactDOM = ReactDOM;

// JSDOM environment setup for browser stuff that isn't natively available in Node
import { JSDOM } from 'jsdom';
const dom = new JSDOM('<!DOCTYPE html><html><body></body></html>');
global.window = dom.window;
global.document = dom.window.document;
global.navigator = dom.window.navigator;
global.MutationObserver = dom.window.MutationObserver;
global.requestAnimationFrame = dom.window.requestAnimationFrame;
global.cancelAnimationFrame = dom.window.cancelAnimationFrame;

// WordPress dependencies
import { createRequire } from 'module';
const require = createRequire(import.meta.url);
const { decodeEntities } = require('@wordpress/html-entities');
const { create: createRichText } = require('@wordpress/rich-text');

// Save function mocks that generate the block content like the WordPress editor would save it to the db
import saveBlock from './save-blocks/index.js';
import { removeStrayDivTags } from "./utils.js";

// Get and parse the block information from the command line
const base64Data = process.argv[2];
const jsonString = Buffer.from(base64Data, 'base64').toString();
const data = JSON.parse(jsonString);

const isBasicText = data.content.length === 1 && typeof data.content[0] === 'string';
if (isBasicText) {
	const htmlContent = decodeEntities(data.content[0]);
	const content = createRichText({
		html: htmlContent,
		multilineTag: false,
		preserveWhiteSpace: false,
	});

	const blockAttributes = {
		...data.attributes,
		content: {
			...content,
			toHTMLString: () => htmlContent
		}
	};

	// Generate the HTML that the block editor would save to the database
	const result = saveBlock.heading({attributes: blockAttributes});
	// Remove stray <div> tags that RichText.Content adds here but not in real WordPress
	const cleanedResult = removeStrayDivTags(result);

	// output it to be picked up by the calling script that passes it to the PHP test setup function
	console.log(cleanedResult);
}

console.log('');
