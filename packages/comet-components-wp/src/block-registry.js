/* global wp */

/**
 * Customisations for blocks themselves
 * Companion for what is done in BlockRegistry.php
 */
wp.domReady(() => {
	removeSomeCoreStylesAndVariations();
});


/**
 * Unregister unwanted core block styles and variations
 * Note: This is only accounts for blocks that are explicitly allowed by the allowed_block_types_all filter
 * At the time of writing, this can't be done in PHP, otherwise I would have.
 */
function removeSomeCoreStylesAndVariations() {
	setTimeout(() => {
		// TODO: Can this be an explicit allow list rather than filtering out?
		// eslint-disable-next-line max-len
		(['wordpress', 'issuu', 'spotify', 'soundcloud', 'flickr', 'animoto', 'cloudup', 'crowdsignal', 'dailymotion', 'imgur', 'kickstarter', 'mixcloud', 'pocket-casts', 'reddit', 'reverbnation', 'screencast', 'scribd', 'smugmug', 'speaker-deck', 'ted', 'tumblr', 'videopress', 'amazon-kindle', 'wolfram-cloud', 'pinterest', 'wordpress-tv']).forEach((embed) => {
			wp.blocks.unregisterBlockVariation('core/embed', embed);
		});
	}, 200);
}
