import { BlockEditorProvider, BlockCanvas, BlockInspector } from '@wordpress/block-editor';
import { registerBlockType, createBlock } from '@wordpress/blocks';
import { CometBlockControls } from '@doubleedesign/comet-gutenberg-controls';
import '@doubleedesign/comet-gutenberg-controls/style.css';
import callToAction from '../src/blocks/call-to-action/block.json';
import { useDispatch } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';

function BlockEdit({ name, attributes, setAttributes }) {
	const [previewHtml, setPreviewHtml] = useState('');

	useEffect(() => {
		fetch('http://localhost:7000/render', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				name,
				attributes
			}),
		})
			.then(res => res.text())
			.then(setPreviewHtml);
	}, [attributes]);

	return (
		<CometBlockControls
			BlockEdit={() => (
				<div dangerouslySetInnerHTML={{ __html: previewHtml }} />
			)}
			name={name}
			attributes={attributes}
			setAttributes={setAttributes}
		/>
	);
}

registerBlockType(callToAction, {
	edit: BlockEdit,
	save: () => null
});

const mockBlocks = [createBlock('comet/call-to-action', {
	size: 'narrow'
})];

export function App() {
	return (
		<BlockEditorProvider
			value={mockBlocks}
			onInput={() => {}}
			onChange={() => {}}
			settings={{}}
		>
			<EditorContent />
		</BlockEditorProvider>
	);
}


function EditorContent() {
	const { selectBlock } = useDispatch('core/block-editor');

	useEffect(() => {
		// Select the first block after mount
		selectBlock(mockBlocks[0].clientId);
	}, []);

	return (
		<div className="mock-block-editor">
			<div className="mock-block-editor__canvas">
				<BlockCanvas />
			</div>
			<div className="mock-block-editor__inspector">
				<BlockInspector />
			</div>
		</div>
	);
}
