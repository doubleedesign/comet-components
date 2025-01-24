/* global wp */

wp.domReady(() => {
	const { registerBlockType } = wp.blocks;
	const { RichText } = wp.blockEditor;

	registerBlockType('comet/panel-title', {
		edit: function({ attributes, setAttributes }) {
			return wp.element.createElement(RichText, {
				tagName: 'span',
				value: attributes.content,
				onChange: (content) => setAttributes({ content }),
				placeholder: attributes.placeholder
			});
		},
		save: function({ attributes }) {
			return wp.element.createElement(RichText.Content, {
				tagName: 'span',
				value: attributes.content
			});
		}
	});
});
