/* global wp */

/**
 * Block editor customisations regarding blocks themselves
 * Companion for what is done in BlockEditorConfig.php
 *
 * See BlockEditorAdminAssets.php for where the compiled version of this script is loaded;
 * and the dependencies that may need to be added to ensure future modifications work
 */


wp.domReady(() => {
	allowSomeBlocksOnlyOncePerPage();
	addCoreBlockParents();
	restrictCoreBlockChildren();
	customiseBlockCategories();
	updateCoreBlockLabelsAndDescriptions();
});


/**
 * Customise the categories that some core blocks appear in
 * Note: Customisation of the categories themselves *could* be done here but is done in PHP
 * @see BlockEditorConfig.php
 */
function customiseBlockCategories() {
	wp.hooks.addFilter('blocks.registerBlockType', 'comet/customise-block-categories', function(settings, name) {
		const layoutBlocks = ['core/columns', 'core/group', 'core/grid', 'core/separator', 'core/spacer'];
		if (layoutBlocks.includes(name)) {
			return { ...settings, category: 'layout' };
		}

		const mediaBlocks = ['core/embed'];
		if (mediaBlocks.includes(name)) {
			return { ...settings, category: 'media' };
		}

		const textBlocks = ['core/heading', 'core/paragraph', 'core/list', 'core/quote', 'core/table', 'core/buttons', 'core/button'];
		if (textBlocks.includes(name)) {
			return { ...settings, category: 'formatting' };
		}

		const contentBlocks = ['core/latest-posts'];
		if (contentBlocks.includes(name)) {
			return { ...settings, category: 'content' };
		}

		const formBlocks = ['ninja-forms/form'];
		if (formBlocks.includes(name)) {
			return { ...settings, category: 'forms' };
		}

		return settings;
	});
}


/**
 * Restrict some blocks to only be used once per page
 */
function allowSomeBlocksOnlyOncePerPage() {
	wp.hooks.addFilter('blocks.registerBlockType', 'comet/allow-some-blocks-only-once-per-page', function(settings, name) {
		if (['comet/page-header'].includes(name)) {
			settings.supports.multiple = false;
		}

		return settings;
	});
}


/**
 * Restrict the blocks that can be added as innerBlocks of some core blocks
 */
function restrictCoreBlockChildren() {
	wp.hooks.addFilter('blocks.registerBlockType', 'comet/restrict-core-block-children', function (settings, name) {

		if(name === 'core/cover') {
			return {
				...settings,
				attributes: {
					...settings.attributes,
					allowedBlocks: {
						type: 'array',
						default: [
							'core/heading',
							'core/paragraph',
							'core/list',
							'core/buttons',
							'core/separator'
						],
					},
				},
			};
		}

		return settings;
	});
}


/**
 * Limit availability of some core to specific parent blocks by adding the parent setting
 * (For custom blocks, the parent can be set in block.json)
 * NOTE: This can be overridden in ACF-powered custom blocks by using a block as an allowed and/or default block in that specific context
 */
function addCoreBlockParents() {
	wp.hooks.addFilter('blocks.registerBlockType', 'comet/add-core-block-parents', function(settings, name) {
		if(name === 'core/column') {
			settings.parent = ['core/columns'];
		}
		if(name === 'core/list-item') {
			settings.parent = ['core/list'];
		}

		return settings;
	});
}


/**
 * Change descriptions of core blocks
 */
function updateCoreBlockLabelsAndDescriptions() {
	wp.hooks.addFilter('blocks.registerBlockType', 'comet/update-core-block-descriptions', function(settings, name) {
		if (name === 'core/media-text') {
			settings.description = '';
		}

		if(name === 'core/details') {
			settings.title = 'Accordion';
			settings.description = 'Put content into expandable/collapsible sections.';
		}

		if (name === 'core/columns') {
			// eslint-disable-next-line max-len
			settings.description = 'Combine content into a multi-column layout. Note: Columns will stack on small screens automatically. Column widths will kick in when the visitor\'s viewport is large enough to accommodate them.\'';
		}

		if (name === 'core/column') {
			// eslint-disable-next-line max-len
			settings.description = `${settings.description} Note: Columns will stack on small screens automatically. Column widths will kick in when the visitor\'s viewport is large enough to accommodate them.'`;
		}

		return settings;
	});
}
