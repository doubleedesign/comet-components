/* global wp */
import { addFilter } from '@wordpress/hooks';
import { InspectorControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { PanelBody, SelectControl } from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import { CometBlockControls } from '@doubleedesign/comet-gutenberg-controls';

/**
 * Note: This file needs to be compiled (Rollup is configured for this)
 * and the compiled *dist.js loaded into the editor via the enqueue_block_editor_assets PHP hook
 */
wp.domReady(() => {
	addFilter(
		'editor.BlockEdit',
		'comet-plugin-blocks/custom-controls',
		(BlockEdit) => (props) => {
			const BlockEditComponent = useMemo(() => {
				if (props?.name === 'ninja-forms/form') {
					return NinjaFormsControls;
				}

				return CometBlockControls;
			}, [props?.name]);

			const className = getInnerblockClassname(props);

			return (
				<div className={`comet-block-edit-wrapper ${className}`} data-block={props.name}>
					<BlockEditComponent BlockEdit={BlockEdit} {...props} />
				</div>
			);
		});
});

/**
 * If this is an inner block, its parent *should* have its class name and block attributes added around its <InnerBlocks> in BlockRenderer.php.
 * This allows us to add the assumed class name for the children here to mimic the HTML structure of the front-end as closely as possible.
 * There's still some extra wrappers related to editor functionality, so a small amount of CSS is sometimes needed, but this approach keeps that to a minimum.
 *
 * Note: This is only relevant to block -> innerblock structures, because the outer block does not use render.php on the back-end.
 * All other blocks use the same PHP to render the block in the editor preview and on the front-end, so there's no extra steps required.
 *
 * Another note: The parent block needs providesContext:name and inner block needs usesContext:parent
 * in their respective block.jsons for this to work.
 *
 * @param props
 * @returns {string}
 */
function getInnerblockClassname(props) {
	if(!props?.context?.parent) {
		return '';
	}

	if(props.context.parent === 'comet/columns') {
		return 'columns__column';
	}

	const parentShortName = props.context.parent.replace('comet/', '');
	const thisBlockShortName = props.name.replace('comet/', '');

	return `${parentShortName}__${thisBlockShortName}`;
}


/**
 * Recreate the Ninja Form selector dropdown control so that it is wrapped in the same components as our custom controls,
 * and also enables us to use server-side rendering for the block preview instead of BlockEdit
 * (which would render both the default preview + the default form selector)
 * @param {Object} props The block edit props
 *
 * FIXME: This currently gets stuck on the loading spinner when rendering the form.
 * FIXME: This needs colour and size (container width) controls.
 */
function NinjaFormsControls(props) {
	const { name, attributes, setAttributes } = props;
	if (name !== 'ninja-forms/form') {
		return null;
	}

	const options = Object.values(window?.nfFormsBlock?.forms)?.map(({ formID, formTitle }) => ({
		label: `${formTitle} (ID: ${formID})`,
		value: formID,
	}));

	return (
		<>
			<div className="comet-plugin-blocks-custom-controls">
				<InspectorControls>
					<PanelBody title="Content" initialOpen={true}>
						<SelectControl
							label="Form"
							size={'__unstable-large'}
							value={attributes.formID}
							options={options ?? []}
							onChange={(value) => {
								setAttributes({
									formID: parseInt(value),
									formTitle: options.find(option => option.value === parseInt(value))?.label || '',
								});
							}}
						/>
					</PanelBody>
				</InspectorControls>
			</div>
			<ServerSideRender block="ninja-forms/form" attributes={props.attributes}/>
		</>
	);
}
