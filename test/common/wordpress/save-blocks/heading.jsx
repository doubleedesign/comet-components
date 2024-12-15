// This is based on @wordpress/block-library/src/heading/save.js,
// modified to work in my testing environment + add the class name (not sure where that usually happens)
import React from 'react';
import ReactDOMServer from 'react-dom/server';
import clsx from 'clsx';
import { createRequire } from 'module';

const require = createRequire(import.meta.url);
const serializer = require('@wordpress/blocks/build/api/serializer.js');
const RichText = require('@wordpress/block-editor/build/components/rich-text/content.js');

// Create our own useBlockProps with just the save function
const useBlockProps = {
	save: serializer.getBlockProps
};

export default function saveHeading({ attributes }) {
	const { textAlign, content, level, className } = attributes;
	const TagName = 'h' + level;

	const classes = clsx({
		[`has-text-align-${textAlign}`]: textAlign,
		...className
	});

	return ReactDOMServer.renderToString(
		<TagName {...useBlockProps.save({ className })}>
			<RichText.Content value={content} />
		</TagName>
	);
}
