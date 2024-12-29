import { JSDOM } from 'jsdom';
import { createRequire } from 'module';

// JSDOM environment setup for browser stuff not natively available in Node and are required by the WP dependencies used here
const dom = new JSDOM('<!DOCTYPE html><html><body></body></html>');
global.window = dom.window;
global.document = dom.window.document;
global.navigator = dom.window.navigator;
global.MutationObserver = dom.window.MutationObserver;

// Full credit to Lanny for coming back to their post to share their solution for this
// which is so much simpler and more complete than what I had been trying
// @see https://wordpress.stackexchange.com/a/415253/137166
// (I have made minor modifications to suit the Node environment but the core of the solution is theirs)
const require = createRequire(import.meta.url);
const { createBlock, serialize } = require('@wordpress/blocks');
const { registerCoreBlocks } = require('@wordpress/block-library');
registerCoreBlocks();

// Get and parse the block information from the command line
const base64Data = process.argv[2];
const jsonString = Buffer.from(base64Data, 'base64').toString();
const data = JSON.parse(jsonString);
const block = createBlock(data.blockName, data.attributes, data.innerBlocks);
const serializedBlock = serialize(block);

// Output to be picked up by the calling script
console.log(serializedBlock);
