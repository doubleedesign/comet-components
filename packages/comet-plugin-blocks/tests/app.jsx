import { useState } from '@wordpress/element';
import { BlockEditorProvider, BlockCanvas } from '@wordpress/block-editor';

function App() {
	const [blocks, setBlocks] = useState([]);

	return (
		<BlockEditorProvider
			value={blocks}
			onInput={setBlocks}
			onChange={setBlocks}
			settings={{}}
		>
			<BlockCanvas height="100vh" />
		</BlockEditorProvider>
	);
}
