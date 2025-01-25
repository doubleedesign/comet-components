/**
 * This script generates the skeleton of the files required for creation of components based on WordPress block definition files.
 * After generation, you still need to go and fill in handling of different blocks' attributes and whatnot,
 * because while we could get them out of block.json, that doesn't account for modifications made elsewhere (plugin or theme) for core blocks.
 * // TODO: Support custom blocks in the same way; in that case attributes may be able to be handled from block.json during generation
 */
import blockSupportConfig from '../packages/comet-components-wp/src/block-support.json' assert { type: 'json' };
const wpIncludes = '../wordpress/wp-includes/blocks'; // Installed via Composer
const coreBlocks = blockSupportConfig.core.supported;
import { execSync } from 'child_process';

const blockTypeMap = await getBlockTypes();

/**
 * For each block in the blockTypeMap, run the component generation script
 */
Object.entries(blockTypeMap).forEach(([blockType, blocks]) => {
	blocks.forEach(blockName => {
		const shortName = blockName.split('/')[1];
		execSync(`npm run generate component -- --name=${shortName} --type=${blockType}`);
	});
});


/**
 * For each core supported block: // TODO: Support custom blocks in the same way
 * 1. Get the block.json file
 * 2. Determine whether this is a "simple", "complex", or "wrapper" block
 *    (simple = stuff like paragraphs; complex = has inner blocks; wrapper = layout blocks that don't have block.json)
 * 3. Add the block to the appropriate group in the blockTypeMap for later processing
 */
async function getBlockTypes() {
	const blockTypeMap = {
		simple: [],
		complex: [],
		wrapper: []
	};


	await Promise.all(Object.keys(coreBlocks).map(async blockName => {
		const blockShortName = blockName.split('/')[1];
		const blockPath = `${wpIncludes}/${blockShortName}/block.json`;

		try {
			const blockDef = await import(blockPath, { assert: { type: 'json' } });
			const isComplex = blockDef?.default?.allowedBlocks || blockDef?.default?.attributes?.allowedBlocks;
			if (isComplex) {
				blockTypeMap.complex.push(blockName);
			}
			else {
				blockTypeMap.simple.push(blockName);
			}
		}
		catch (error) {
			// Probably a layout block such as core/grid or core/stack, which do not have block.json definitions
			blockTypeMap.wrapper.push(blockName);
		}
	}));

	return blockTypeMap;
}
