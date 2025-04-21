/* global wp */

document.addEventListener('DOMContentLoaded', async function() {
	wp.data.subscribe(() => {
		const { select, dispatch } = wp.data;
		const editor = select('core/editor');
		const currentTemplate = editor.getEditedPostAttribute('template');
		const groupTemplates = ['template-page-with-nav-sidebar.php'];

		// Transform top-level Containers to Groups if the new template is in the list
		if(groupTemplates.includes(currentTemplate)) {
			const blocks = select('core/block-editor').getBlocks(); // this is hierarchical
			blocks.forEach((block => {
				if (block.name === 'comet/container') {
					// Transform this block to a Group, keeping all existing attributes and inner blocks intact
					const newBlock = {
						...block,
						name: 'core/group',
					};

					dispatch('core/block-editor').replaceBlock(block.clientId, newBlock);
				}
			}));
		}

		// If switching back to the default template, change top-level Groups back to Containers
		if(currentTemplate === '') {
			const blocks = select('core/block-editor').getBlocks(); // this is hierarchical
			blocks.forEach((block => {
				if (block.name === 'core/group') {
					// Transform this block to a Container, keeping all existing attributes and inner blocks intact
					const newBlock = {
						...block,
						name: 'comet/container',
					};

					dispatch('core/block-editor').replaceBlock(block.clientId, newBlock);
				}
			}));
		}
	});
});
