/* global wp */

wp.domReady(() => {
	const { registerBlockType } = wp.blocks;
	const { useBlockProps, InnerBlocks } = wp.blockEditor;
	const { createElement } = wp.element;

	registerBlockType('comet/panels', {
		edit: ({ attributes }) => {
			const blockProps = useBlockProps({
				className: attributes.variant
			});
			const template = [
				['comet/panel'],
				['comet/panel'],
				['comet/panel']
			];

			return createElement('div',
				blockProps,
				createElement(InnerBlocks, {
					allowedBlocks: ['comet/panel'],
					template: template
				})
			);
		},
		save: () => {
			return createElement('div',
				useBlockProps.save(),
				createElement(InnerBlocks.Content)
			);
		}
	});
});
