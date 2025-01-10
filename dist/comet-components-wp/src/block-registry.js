/* global wp */

/**
 * Customisations for blocks themselves
 * Companion for what is done in BlockRegistry.php
 *
 * See BlockEditorAdminAssets.php for where the compiled version of this script is loaded;
 * and the dependencies that may need to be added to ensure future modifications work
 *
 * TODO: Find a way to use the various JSON files (wp-supported-blocks, individual definitions?)
 *       that are generated or used by the PHP classes to keep this aligned with the abstracted implementations
 */

import lodash from 'https://cdn.jsdelivr.net/npm/lodash@4.17.21/+esm';

const { omit } = lodash;

wp.domReady(() => {
	addCoreBlockCustomStyles();
	removeSomeCoreStylesAndVariations();
	customiseCoreBlockAttributes();
});


/**
 * Add some additional style options to some core blocks
 */
function addCoreBlockCustomStyles() {
	wp.blocks.registerBlockStyle('core/paragraph', {
		name: 'lead',
		label: 'Lead',
	});

	wp.blocks.registerBlockStyle('core/heading', {
		name: 'accent',
		label: 'Accent font',
	});
	wp.blocks.registerBlockStyle('core/heading', {
		name: 'small',
		label: 'Small text',
	});
}

/**
 * Unregister unwanted core block styles and variations
 * Note: This is only accounts for blocks that are explicitly allowed by the allowed_block_types_all filter in inc/cms/class-block-editor.php
 */
function removeSomeCoreStylesAndVariations() {
	setTimeout(() => {
		wp.blocks.unregisterBlockStyle('core/image', 'rounded');

		// TODO: Can this be an explicit allow list rather than filtering out?
		// eslint-disable-next-line max-len
		(['wordpress', 'soundcloud', 'spotify', 'slideshare', 'twitter', 'flickr', 'animoto', 'cloudup', 'crowdsignal', 'dailymotion', 'imgur', 'issuu', 'kickstarter', 'mixcloud', 'pocket-casts', 'reddit', 'reverbnation', 'screencast', 'scribd', 'smugmug', 'speaker-deck', 'ted', 'tumblr', 'videopress', 'amazon-kindle', 'wolfram-cloud', 'pinterest', 'wordpress-tv', 'bluesky', 'tiktok']).forEach((embed) => {
			wp.blocks.unregisterBlockVariation('core/embed', embed);
		});
	}, 200);
}


/**
 * Remove unwanted/unused attributes from core blocks
 * and tweak the supports settings for some blocks
 * Note: This doesn't always remove them from the editor sidebar :(
 *       For some things, that can be done in theme.json
 */
function customiseCoreBlockAttributes() {
	wp.hooks.addFilter('blocks.registerBlockType', 'comet/remove-unwanted-block-attributes', function(settings, name) {
		// Attributes I don't want to use in any block
		if (settings.attributes) {
			settings.attributes = omit(settings.attributes, ['isStackedOnMobile', 'textColor']);
			settings.supports = omit(settings.supports, ['typography', 'spacing', 'html']);
		}

		const typographyBlocks = ['core/paragraph', 'core/heading', 'core/list', 'core/quote'];
		if (typographyBlocks.includes(name)) {
			settings.supports = omit(settings.supports, ['spacing', 'color']);
		}

		if (name === 'core/group') {
			// eslint-disable-next-line max-len
			settings.supports = omit(settings.supports, ['__experimentalSettings', 'align', 'background', 'color', 'dimensions', 'layout', 'typography', 'anchor', 'spacing', 'position', 'ariaLabel', 'html']);
		}

		if (name === 'core/columns' || name === 'core/media-text') {
			settings.supports = omit(settings.supports, ['align', 'color', 'spacing', 'typography', 'anchor']);
			settings.supports.layout = {
				...settings.supports.layout,
				allowSwitching: false,
				allowEditing: true,
				allowInheriting: false,
				allowSizingOnChildren: false,
				// eslint-disable-next-line max-len
				allowVerticalAlignment: false, // Note: Setting to true just seems to double up because the setting for column seems to have no effect at the time of writing (it's there regardless)
				allowJustification: true,
				allowOrientation: false,
				default: {
					type: 'flex',
					alignItems: 'center',
					justifyContent: 'center',
					flexWrap: 'wrap',
				},
			};
		}

		if (name === 'core/column') {
			settings.supports = omit(settings.supports, ['align', 'spacing', 'typography']);
			settings.supports.color = {
				background: true,
				button: false,
			};
			settings.supports.layout = {
				...settings.supports.layout,
				allowSwitching: false,
				allowEditing: false,
				allowInheriting: false,
				// eslint-disable-next-line max-len
				allowVerticalAlignment: true, // Note: Setting to false had no effect at the time of writing so just running with it even though I originally wanted to only set this at the columns level, not for an individual colum
				allowJustification: false,
				default: {
					type: 'flow',
				},
			};
		}

		if (name === 'core/cover') {
			// Disable "Inner blocks use content width" setting
			settings.supports = omit(settings.supports, ['layout']);
		}

		if (name === 'core/latest-posts') {
			settings.supports = omit(settings.supports, ['align']);
		}

		if (name === 'core/buttons') {
			settings.supports = omit(settings.supports, ['width', 'color', 'spacing', 'typography']);
		}

		if (name === 'core/button') {
			settings.supports = omit(settings.supports, ['shadow', 'anchor', 'alignWide']);
			settings.attributes = {
				...settings.attributes, ...{
					buttonColor: {
						type: 'string',
						default: 'primary',
					},
				},
			};

			if (settings.supports?.color) {
				settings.supports.color.button = true;
			}
		}

		return settings;
	});
}
